<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if (!isset($_POST['action'])) {
  header('Location: index.php');
  exit();
}

$pdo = conectar("helpdesk");
$idsolicitante = $_SESSION['auth_data']['id'];
$idservico = filter_input(INPUT_POST, "servico", FILTER_SANITIZE_NUMBER_INT);
$idsecao = filter_input(INPUT_POST, "secao", FILTER_SANITIZE_NUMBER_INT);
$idetiqueta = filter_input(INPUT_POST, "etiqueta", FILTER_SANITIZE_NUMBER_INT);
$dataabertura = date("d/m/Y");
$horaabertura = date("H:i:s");
$assunto = filter_input(INPUT_POST, "assunto", FILTER_SANITIZE_STRING);
$chamado = filter_input(INPUT_POST, "chamado", FILTER_SANITIZE_STRING);
$numchamado = time() . "" . $_SESSION['auth_data']['id']; //strrev() inverte a string
$arquivo = isset($_FILES["arquivo"]) ? $_FILES["arquivo"] : FALSE;

$caminho_arquivo = processa_upload($numchamado, $arquivo);

$situacao = "1"; // 1 - Em Espera, 2 - Em Atendimento, 3 - Finalizado
$chamado = $chamado . "\n Criado em " . $dataabertura . " às " . $horaabertura . " pelo " . getPGrad($_SESSION['auth_data']['idpgrad']) . " " . $_SESSION['auth_data']['nomeguerra'] . " (ID:" . $_SESSION['auth_data']['id'] . ")";

try {
  $gravchmdo = $pdo->prepare("INSERT INTO chamado(numchamado, situacao, idservico, idsolicitante, idsecao, dataabertura, horaabertura, assunto, idetiqueta) "
    . "VALUES (:numchamado, :situacao, :idservico, :idsolicitante, :idsecao, :dataabertura, :horaabertura, :assunto, :idetiqueta)");
  $gravchmdo->bindParam(":numchamado", $numchamado, PDO::PARAM_STR);
  $gravchmdo->bindParam(":situacao", $situacao, PDO::PARAM_STR);
  $gravchmdo->bindParam(":idservico", $idservico, PDO::PARAM_STR);
  $gravchmdo->bindParam(":idsolicitante", $idsolicitante, PDO::PARAM_STR);
  $gravchmdo->bindParam(":idsecao", $idsecao, PDO::PARAM_STR);
  $gravchmdo->bindParam(":dataabertura", $dataabertura, PDO::PARAM_STR);
  $gravchmdo->bindParam(":horaabertura", $horaabertura, PDO::PARAM_STR);
  $gravchmdo->bindParam(":assunto", $assunto, PDO::PARAM_STR);
  $gravchmdo->bindParam(":idetiqueta", $idetiqueta, PDO::PARAM_INT);
  $executa = $gravchmdo->execute();

  $grav = $pdo->prepare("INSERT INTO historico(numchamado, texto, anexo, data, hora) VALUES (:numchamado, :texto, :anexo, :data, :hora)");
  $grav->bindParam(":numchamado", $numchamado, PDO::PARAM_STR);
  $grav->bindParam(":texto", $chamado, PDO::PARAM_LOB);
  $grav->bindParam(":anexo", $caminho_arquivo, PDO::PARAM_STR);
  $grav->bindParam(":data", $dataabertura, PDO::PARAM_STR);
  $grav->bindParam(":hora", $horaabertura, PDO::PARAM_STR);

  $somachamado1 = "qtdservico+1"; // tabela servico
  $somachamado2 = "qtdchamados+1"; // tabela secao
  $totalchamados = "totalchamados+1"; // tabela etiquetas

  $gravsv = $pdo->prepare("UPDATE servico SET qtdservico = $somachamado1 WHERE id = :idservico");
  $gravsv->bindParam(":idservico", $idservico, PDO::PARAM_INT);

  $gravsec = $pdo->prepare("UPDATE secao SET qtdchamados = $somachamado2 WHERE id = :idsecao");
  $gravsec->bindParam(":idsecao", $idsecao, PDO::PARAM_INT);

  $gravetq = $pdo->prepare("UPDATE etiqueta SET totalchamados = $totalchamados WHERE id = :idetiqueta");
  $gravetq->bindParam(":idetiqueta", $idetiqueta, PDO::PARAM_INT);

  if ($executa) {
    $grav->execute();
    $execsv = $gravsv->execute();
    $execsec = $gravsec->execute();
    $execetq = $gravetq->execute();
  } else {
    $msgerro = base64_encode('Erro na base de dados!');
    header('Location: meu_cham.php?token=' . $msgerro);
    exit();
  }
} catch (PDOException $e) {
  echo $e->getMessage();
}
$msgsuccess = base64_encode('Chamado ' . $numchamado . ' enviado com sucesso!');
header("Location: meu_cham.php?token2=" . $msgsuccess);
exit();
