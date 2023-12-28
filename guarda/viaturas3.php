<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if (!isset($_POST['action'])) {
  header('Location: viaturas1.php');
  exit();
}

$idvtr_encode = filter_input(INPUT_POST, "tkvtr", FILTER_SANITIZE_SPECIAL_CHARS);
$idvtr_encode = preg_replace('/[0-9]+/', '', $idvtr_encode);
$idvtr_encode = str_replace(".", "", $idvtr_encode);
$idvtr = base64_decode($idvtr_encode);
$odometro = filter_input(INPUT_POST, "odometro", FILTER_SANITIZE_NUMBER_INT);
//$convertdata = filter_input(INPUT_POST, "data", FILTER_SANITIZE_STRING);
//$data = implode('/', array_reverse(explode('-', $convertdata)));
$data = filter_input(INPUT_POST, "data", FILTER_SANITIZE_STRING);
$data2 = date_converter($data);
$converthora = filter_input(INPUT_POST, "hora", FILTER_SANITIZE_STRING);
if (strlen($converthora) == 4) {
  $converthora = ("0" . $converthora);
}
$hora = ($converthora . ':00');
$situacao = filter_input(INPUT_POST, "action", FILTER_SANITIZE_STRING);

if ($situacao == 'Entrou no Aquartelamento') {
  $situacao2 = 0;
  $idchvtr2 = 0;
  $idmtr2 = 0;
} else {
  $situacao2 = 1;
}

$pdo1 = conectar("guarda");
$pdo2 = conectar("membros");

$select_vtr = $pdo1->prepare("SELECT * FROM viatura WHERE id = :idvtr ORDER BY tipo, marca, modelo, placa ASC");
$select_vtr->bindParam(":idvtr", $idvtr, PDO::PARAM_INT);
$select_vtr->execute();
while ($reg1 = $select_vtr->fetch(PDO::FETCH_ASSOC)) {
  $placa_vtr = $reg1['placa'];
  $tipo_vtr = $reg1['tipo'];
  $modelo_vtr = $reg1['modelo'];
  $idchvtr = $reg1['idchvtr'];
  $idmtr = $reg1['idmtr'];
}

$select_usuarios = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE id = :idchvtr");
$select_usuarios->bindParam(":idchvtr", $idchvtr, PDO::PARAM_INT);
$select_usuarios->execute();
while ($reg2 = $select_usuarios->fetch(PDO::FETCH_ASSOC)) {
  $nome_usuarios = $reg2['nomeguerra'];
  $pg_usuarios = getPGrad($reg2['idpgrad']);
}

$select_rel = $pdo1->prepare("SELECT id, ficha, destino, odometro FROM rel_viaturas WHERE idvtr = :idvtr ORDER BY id DESC limit 1");
$select_rel->bindParam(":idvtr", $idvtr, PDO::PARAM_INT);
$select_rel->execute();
while ($reg3 = $select_rel->fetch(PDO::FETCH_ASSOC)) {
  $ficha = $reg3['ficha'];
  $destino = $reg3['destino'];
  $odometro2 = $reg3['odometro'];
  $idsaida = $reg3['id'];
}

$sistema = base64_encode('GUARDA');
$obs = "Lançou Vtr " . $placa_vtr . " - " . $modelo_vtr . " - (" . $tipo_vtr . "), Ch Vtr " . $pg_usuarios . " " . $nome_usuarios . ": " . $situacao . ", odometro: " . $odometro;

$dataatual = date("d/m/Y");
$dataatual2 = date("Y-m-d");
$horaatual = date("H:i:s");

$stmtez = $pdo1->prepare("INSERT INTO rel_viaturas(idvtr, data, hora, idusuario, situacao, ficha, idchvtr, idmtr, odometro, destino, idsaida) "
  . "VALUES (:idvtr, :data, :hora, :idusuario, :situacao, :ficha, :idchvtr, :idmtr, :odometro, :destino, :idsaida)");
$stmtez->bindParam(":idvtr", $idvtr, PDO::PARAM_INT);
$stmtez->bindParam(":data", $data, PDO::PARAM_STR);
$stmtez->bindParam(":hora", $hora, PDO::PARAM_STR);
$stmtez->bindParam(":idusuario", $_SESSION['auth_data']['id'], PDO::PARAM_STR);
$stmtez->bindParam(":situacao", $situacao, PDO::PARAM_STR);
$stmtez->bindParam(":ficha", $ficha, PDO::PARAM_STR);
$stmtez->bindParam(":idchvtr", $idchvtr, PDO::PARAM_INT);
$stmtez->bindParam(":idmtr", $idmtr, PDO::PARAM_INT);
$stmtez->bindParam(":odometro", $odometro, PDO::PARAM_INT);
$stmtez->bindParam(":destino", $destino, PDO::PARAM_STR);
$stmtez->bindParam(":idsaida", $idsaida, PDO::PARAM_INT);

$stmtez2 = $pdo1->prepare("UPDATE viatura SET situacao = :situacao, idchvtr = :idchvtr, idmtr = :idmtr, odometro = :odometro WHERE id = :idvtr"); 
$stmtez2->bindParam(":idvtr", $idvtr, PDO::PARAM_INT);
$stmtez2->bindParam(":situacao", $situacao2, PDO::PARAM_INT);
$stmtez2->bindParam(":idchvtr", $idchvtr2, PDO::PARAM_INT);
$stmtez2->bindParam(":idmtr", $idmtr2, PDO::PARAM_INT);
$stmtez2->bindParam(":odometro", $odometro, PDO::PARAM_INT);

if (
  $idvtr == '' or $idvtr == ' ' or $idvtr == '0' or
  $odometro == '' or $odometro == ' ' or $odometro == '0'
) {
  header('Location: viaturas1.php');
  exit();
} else if ($odometro < $odometro2) {
  $msgerro = base64_encode('Erro na tentativa de registrar o acesso, odômetro inválido!');
  header('Location: viaturas1.php?token=' . $msgerro);
  exit();
} else if (strtotime($dataatual2) == strtotime($data2) and strtotime($horaatual) >= strtotime($hora)) {
  $executa = $stmtez->execute();
  $executa2 = gerar_log_usuario($sistema, $obs);
  $executa3 = $stmtez2->execute();
  $msgsuccess = base64_encode('Viatura lançada com sucesso!');
  header('Location: viaturas1.php?token2=' . $msgsuccess);
  exit();
} else if (strtotime($dataatual2) > strtotime($data2)) {
  $executa = $stmtez->execute();
  $executa2 = gerar_log_usuario($sistema, $obs);
  $executa3 = $stmtez2->execute();
  $msgsuccess = base64_encode('Viatura lançada com sucesso!');
  header('Location: viaturas1.php?token2=' . $msgsuccess);
  exit();
} else {
  $msgerro = base64_encode('Erro na tentativa de registrar o acesso, data e hora inválida!');
  header('Location: viaturas1.php?token=' . $msgerro);
  exit();
}
