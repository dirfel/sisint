<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if($_SESSION['nivel_fatos_observados'] == 'Sem Acesso') {
    header('Location: ../sistemas?token=' . $msgerro);
    exit();
}
if (!isset($_POST['action'])) {
  $msgerro = base64_encode('Você não possui permissão para acessar esse sistema!');
  $msgerro = base64_encode('Ocorreu algum erro!');
  header('Location: index.php?token=' . $msgerro);
  exit();
}

$idmembro = $_SESSION['auth_data']['id'];
$id_usuario = base64_decode(filter_input(INPUT_POST, "id_usuario", FILTER_SANITIZE_SPECIAL_CHARS));
$tipo = filter_input(INPUT_POST, "tipo", FILTER_SANITIZE_STRING);
$obs = filter_input(INPUT_POST, "obs", FILTER_SANITIZE_STRING);
$data = date("Y-m-d");
$hora = date("H:i:s");
$datahora = ($data . " " . $hora);
$foativo = "S";

try {
  $insertFO = conectar("membros")->prepare("INSERT INTO fo (idmembro, id_usuario, tipo, obs, datahora, foativo)
    VALUES (:idmembro, :id_usuario, :tipo, :obs, :datahora, :foativo)");
  $insertFO->bindParam(":idmembro", $idmembro, PDO::PARAM_INT);
  $insertFO->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
  $insertFO->bindParam(":tipo", $tipo, PDO::PARAM_STR);
  $insertFO->bindParam(":obs", $obs, PDO::PARAM_STR);
  $insertFO->bindParam(":datahora", $datahora, PDO::PARAM_STR);
  $insertFO->bindParam(":foativo", $foativo, PDO::PARAM_STR);
  $executa = $insertFO->execute();

  if ($executa) {
    $log = "FO registrado no ID" . $id_usuario . " " . consultaMilitar($id_usuario);
    gerar_log_usuario('Fatos Observados', $log);
  } else {
    $msgerro = base64_encode('Erro na base de dados!');
    header('Location: index.php?token=' . $msgerro);
    exit();
  }
} catch (PDOException $e) {
  echo $e->getMessage();
}
$msgsuccess = base64_encode('FO registrado com sucesso!');
$last = base64_encode($obs);
header("Location: index.php?token2=" . $msgsuccess . "&last=".$last);
exit();
