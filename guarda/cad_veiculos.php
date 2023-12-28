<?php
require "../recursos/models/versession.php";
require "../recursos/models/conexao.php";
if (!isset($_POST['action'])) {
  header('Location: visitantes1.php');
  exit();
}
$tipo = filter_input(INPUT_POST, "tipo", FILTER_SANITIZE_STRING);
$marca = filter_input(INPUT_POST, "marca", FILTER_SANITIZE_STRING);
$cor = filter_input(INPUT_POST, "cor", FILTER_SANITIZE_STRING);
$placa = filter_input(INPUT_POST, 'placa', FILTER_SANITIZE_STRING);
//$placa = mb_strtoupper($placa, 'UTF-8');
$modelo = filter_input(INPUT_POST, "modelo", FILTER_SANITIZE_STRING);
//$modelo = mb_strtoupper($modelo, 'UTF-8');
$situacao = "0";
$tabela = "veiculo";

$pdo = conectar("guarda");
$pdo2 = conectar("membros");

$sistema = base64_encode('GUARDA');
$obs = "Cadastrou Veículo: " . $placa . " - " . $modelo . " - " . $marca . " - (" . $tipo . ")";
$data = date("d/m/Y");
$hora = date("H:i:s");

$consulta = $pdo->prepare("SELECT * FROM veiculo WHERE placa = :placa");
$consulta->bindParam(":placa", $placa, PDO::PARAM_STR);
$consulta->execute();
while ($reg = $consulta->fetch(PDO::FETCH_ASSOC)) {
  $consulta_placa = $reg["placa"];
}

$gravddos = $pdo->prepare("INSERT INTO $tabela(placa, tipo, marca, modelo, cor, situacao)"
  . "VALUES (:placa, :tipo, :marca, :modelo, :cor, :situacao)");
$gravddos->bindParam(":placa", $placa, PDO::PARAM_STR);
$gravddos->bindParam(":tipo", $tipo, PDO::PARAM_STR);
$gravddos->bindParam(":marca", $marca, PDO::PARAM_STR);
$gravddos->bindParam(":modelo", $modelo, PDO::PARAM_STR);
$gravddos->bindParam(":cor", $cor, PDO::PARAM_STR);
$gravddos->bindParam(":situacao", $situacao, PDO::PARAM_INT);

if ($placa == $consulta_placa) {
  $msgerro = base64_encode('Veículo já cadastrado!');
  header('Location: visitantes1.php?token=' . $msgerro);
  exit();
} else {
  $executa = $gravddos->execute();
  $executa2 = gerar_log_usuario($sistema, $obs);
  $msgsuccess = base64_encode('Veículo cadastro realizado com sucesso!');
  header('Location: visitantes1.php?token2=' . $msgsuccess);
  exit();
}
