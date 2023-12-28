<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
if (!isset($_POST['action'])) {
  header('Location: visitantes1.php');
  exit();
}
$idvisitante = base64_decode(filter_input(INPUT_POST, "tkvisit", FILTER_SANITIZE_SPECIAL_CHARS));
//$convertdata = filter_input(INPUT_POST, "data", FILTER_SANITIZE_STRING);
//$data = implode('/', array_reverse(explode('-', $convertdata)));
$data = filter_input(INPUT_POST, "data", FILTER_SANITIZE_STRING);
$data2 = date_converter($data);
$converthora = filter_input(INPUT_POST, "hora", FILTER_SANITIZE_STRING);
if (strlen($converthora) == 4) {
  $converthora = ("0" . $converthora);
}
$hora = ($converthora . ':00');
$situacao_entrou_saiu = filter_input(INPUT_POST, "action", FILTER_SANITIZE_STRING);
if ($situacao_entrou_saiu == 'Saiu do Aquartelamento') {
  $situacao2 = 0;
  $cracha = 0;
} else {
  $situacao2 = 1;
}

$pdo1 = conectar("guarda");
$pdo2 = conectar("membros");

$select_visitante = $pdo1->prepare("SELECT * FROM visitante WHERE id = :idvisitante");
$select_visitante->bindParam(":idvisitante", $idvisitante, PDO::PARAM_INT);
$select_visitante->execute();
while ($reg = $select_visitante->fetch(PDO::FETCH_ASSOC)) {
  $nome_visitante = $reg['nomecompleto'];
  $idveiculo = $reg['idveiculo'];
}

$select_veiculo = $pdo1->prepare("SELECT * FROM veiculo WHERE id = :idveiculo");
$select_veiculo->bindParam(":idveiculo", $idveiculo, PDO::PARAM_INT);
$select_veiculo->execute();
while ($reg2 = $select_veiculo->fetch(PDO::FETCH_ASSOC)) {
  $placa_veiculo = $reg2['placa'];
}

$sistema = base64_encode('GUARDA');
if ($idveiculo > 0) {
  $obs = "Lançou Visitante ID" . $idvisitante . " " . $nome_visitante . ", veículo placa " . $placa_veiculo . ": " . $situacao_entrou_saiu;
} else {
  $obs = "Lançou Visitante ID" . $idvisitante . " " . $nome_visitante . ", nenhum veículo: " . $situacao_entrou_saiu;
}

$dataatual = date("d/m/Y");
$dataatual2 = date("Y-m-d");
$horaatual = date("H:i:s");

$stmtez = $pdo1->prepare("INSERT INTO rel_visitantes(idvisitante, data, hora, idusuario, situacao, idveiculo) "
  . "VALUES (:visitante, :data, :hora, :idusuario, :situacao, :idveiculo)");
$stmtez->bindParam(":visitante", $idvisitante, PDO::PARAM_INT);
$stmtez->bindParam(":data", $data, PDO::PARAM_STR);
$stmtez->bindParam(":hora", $hora, PDO::PARAM_STR);
$stmtez->bindParam(":idusuario", $_SESSION['auth_data']['id'], PDO::PARAM_STR);
$stmtez->bindParam(":situacao", $situacao_entrou_saiu, PDO::PARAM_STR);
$stmtez->bindParam(":idveiculo", $idveiculo, PDO::PARAM_INT);

$stmtez2 = $pdo1->prepare("UPDATE visitante SET situacao = :situacao, idveiculo = :idveiculo, cracha = :cracha WHERE id = :idvisitante");
$stmtez2->bindParam(":situacao", $situacao2, PDO::PARAM_INT);
$stmtez2->bindParam(":idveiculo", $idveiculo, PDO::PARAM_INT);
$stmtez2->bindParam(":idvisitante", $idvisitante, PDO::PARAM_INT);
$stmtez2->bindParam(":cracha", $cracha, PDO::PARAM_STR);

$stmtez3 = $pdo1->prepare("UPDATE veiculo SET situacao = :situacao WHERE id = :idveiculo");
$stmtez3->bindParam(":situacao", $situacao2, PDO::PARAM_INT);
$stmtez3->bindParam(":idveiculo", $idveiculo, PDO::PARAM_INT);

if (
  $idvisitante == '' or $idvisitante == ' ' or $idvisitante == '0' or
  $idveiculo == '' or $idveiculo == ' ' or $idveiculo < '0' or
  $converthora == '' or $converthora == ' ' or $converthora == '0'
) {
  $msgerro = base64_encode('Erro na tentativa de registrar o acesso!');
  header('Location: visitantes1.php?token=' . $msgerro);
  exit();
} else if (strtotime($dataatual2) == strtotime($data2) and strtotime($horaatual) >= strtotime($hora)) {
  $executa = $stmtez->execute();
  $executa2 = gerar_log_usuario($sistema, $obs);
  $executa3 = $stmtez2->execute();
  $executa4 = $stmtez3->execute();
  $msgsuccess = base64_encode('Visitante lançado com sucesso!');
  header('Location: visitantes1.php?token2=' . $msgsuccess);
  exit();
} else if (strtotime($dataatual2) > strtotime($data2)) {
  $executa = $stmtez->execute();
  $executa2 = gerar_log_usuario($sistema, $obs);
  $executa3 = $stmtez2->execute();
  $executa4 = $stmtez3->execute();
  $msgsuccess = base64_encode('Visitante lançado com sucesso!');
  header('Location: visitantes1.php?token2=' . $msgsuccess);
  exit();
} else {
  $msgerro = base64_encode('Erro na tentativa de registrar o acesso, data e hora inválida!');
  header('Location: visitantes1.php?token=' . $msgerro);
  exit();
}
