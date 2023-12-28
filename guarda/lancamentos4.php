<?php
// Esse script deletará movimento de viaturas militares cadastrado incorretamente

// 1. Importando recursos ncessários
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

// verificação de segurança
if (!isset($_POST['action'])) {
  header('Location: lancamentos1.php');
  exit();
}

// 2. definindo variáveis iniciais
$id_rel = base64_decode(filter_input(INPUT_GET, "id", FILTER_SANITIZE_SPECIAL_CHARS));
$rel = base64_decode(filter_input(INPUT_GET, "rel", FILTER_SANITIZE_SPECIAL_CHARS));
$situacao_excluir = filter_input(INPUT_POST, "action", FILTER_SANITIZE_STRING);

$dataatual = date("d/m/Y");
$dataatual2 = date("Y-m-d");
$horaatual = date("H:i:s");
$zerar = 0;

$pdo1 = conectar("guarda");
$pdo2 = conectar("membros");

$cad_viatura = 'viatura';

// 3. Obter dados da viatura
$select_relatorio = $pdo1->prepare("SELECT $rel.idusuario, $rel.idvtr, $rel.data, $rel.hora, $rel.situacao, $rel.ficha, 
        $rel.idchvtr, $rel.idmtr, $rel.odometro, $rel.destino, $rel.idsaida, $cad_viatura.placa, $cad_viatura.modelo, $cad_viatura.marca, $cad_viatura.tipo, $cad_viatura.situacao as situacao_vtr 
        FROM $rel LEFT JOIN $cad_viatura ON ($rel.idvtr = $cad_viatura.id)");
$select_relatorio->bindParam(":id", $id_rel, PDO::PARAM_INT);
$select_relatorio->execute(); // <- ?? essa função está baixando uma base de dados gigantesca pra nada?

// 4. define informações do movimento de vtr      <- ?? essa função armazenando somente o ultimo movimento de viaturas?
while ($reg = $select_relatorio->fetch(PDO::FETCH_ASSOC)) {
  $idvtr = $reg['idvtr'];
  $idusuario = $reg['idusuario'];
  $idmtr = $reg['idmtr'];
  $idchvtr = $reg['idchvtr'];
  $situacao = $reg['situacao'];
  $placa_vtr = $reg['placa'];
  $modelo_vtr = $reg['modelo'];
  $tipo_vtr = $reg['tipo'];
  $odometro = $reg['odometro'];
  $idsaida = $reg['idsaida'];
  $situacao_vtr = $reg['situacao_vtr'];
}

// 5. obtem informação do motorista da viatura
$select_usuarios = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE id = :idusuario");
$select_usuarios->bindParam(":idusuario", $idusuario, PDO::PARAM_INT);
$select_usuarios->execute();
while ($reg2 = $select_usuarios->fetch(PDO::FETCH_ASSOC)) {
    $nome_usuarios = $reg2['nomeguerra'];
    $pg_usuarios = getPGrad($reg2['idpgrad']);
}

// 6. obtem informação do chefe da viatura
$select_usuarios2 = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE id = :idchvtr");
$select_usuarios2->bindParam(":idchvtr", $idchvtr, PDO::PARAM_INT);
$select_usuarios2->execute();
while ($reg2 = $select_usuarios2->fetch(PDO::FETCH_ASSOC)) {
  $nome_usuarios_chvtr = $reg2['nomeguerra'];
  $pg_usuarios_chvtr = getPGrad($reg2['idpgrad']);
}

// 7. Obtem a informação do usuario logado
$sistema = base64_encode('GUARDA');
$obs = $situacao_excluir . " ID" . $id_rel . ": " . $placa_vtr . " - " . $modelo_vtr . " - (" . $tipo_vtr . "), Ch Vtr " . $pg_usuarios_chvtr . " " . $nome_usuarios_chvtr . ": " . $situacao . ", odometro: " . $odometro . ". Lançado pelo: " . $pg_usuarios . " " . $nome_usuarios;

// 8. exclui movimento de viatura de rel_viatura de entrada e saída
$excluir_rel = $pdo1->prepare("DELETE FROM $rel WHERE id = :id OR idsaida = :id");
$excluir_rel->bindParam(":id", $id_rel, PDO::PARAM_INT);

// 10. Muda o status da viatura para sabermos que ela está na OM
$stmtez2 = $pdo1->prepare("UPDATE $cad_viatura SET situacao = :situacao, idchvtr = :idchvtr, idmtr = :idmtr WHERE id = :idvtr");
$stmtez2->bindParam(":idvtr", $idvtr, PDO::PARAM_INT);
$stmtez2->bindParam(":situacao", $zerar, PDO::PARAM_INT);
$stmtez2->bindParam(":idchvtr", $zerar, PDO::PARAM_INT);
$stmtez2->bindParam(":idmtr", $zerar, PDO::PARAM_INT);

try{
// 11. hora de definir quais ações serão executadas
if (
  $id_rel == '' or $id_rel == ' ' or $id_rel == '0' or
  $rel == '' or $rel == ' '
) {
  $msgerro = base64_encode('Erro na tentativa de excluir o lançamento!');
  header('Location: lancamentos1.php?token=' . $msgerro);
  exit();
} else if ($idsaida != null) {
  $excluir_rel->execute();
  gerar_log_usuario($sistema, $obs);
  $msgsuccess = base64_encode('Lançamento excluído com sucesso!');
  header('Location: lancamentos1.php?token2=' . $msgsuccess);
  exit();
} else {
  $stmtez2->execute();
  $excluir_rel->execute();
  gerar_log_usuario($sistema, $obs);
  $msgsuccess = base64_encode('Lançamento excluído com sucesso!');
  header('Location: lancamentos1.php?token2=' . $msgsuccess);
  exit();
}

} catch (Error $e) {print_r($e);die();}