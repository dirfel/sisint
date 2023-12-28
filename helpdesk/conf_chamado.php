<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if (!isset($_POST['action'])) {
  header('Location: index.php');
  exit();
}

$pdo = conectar("helpdesk");
$dataabertura = date("d/m/Y");
$horaabertura = date("H:i:s");
$chamado = filter_input(INPUT_POST, "chamado", FILTER_SANITIZE_STRING);
$arquivo = isset($_FILES["arquivo"]) ? $_FILES["arquivo"] : FALSE;
$numchamado = base64_decode(filter_input(INPUT_GET, "out"));
$criptolink = base64_encode($numchamado);
$diretorio = "anexo/" . $numchamado;
$chamado = $chamado . "\n Criado em " . $dataabertura . " às " . $horaabertura . " pelo " . getPGrad($_SESSION['auth_data']['idpgrad']) . " " . $_SESSION['auth_data']['nomeguerra'] . " (ID:" . $_SESSION['auth_data']['id'] . ")";
$situacao = filter_input(INPUT_POST, "action", FILTER_SANITIZE_STRING);
if ($situacao == "Finalizar Chamado") {
  $situacao = '3';
} else {
  $situacao = '2';
}

if (!empty($arquivo["name"])) {
  if (!file_exists($diretorio)) {
    mkdir($diretorio, 0777);
  }
  $extensao = strtolower(end(explode(".", $arquivo["name"])));
  // Gera um nome Ãºnico para a imagem
  $nome_imagem = md5(uniqid(time())) . "." . $extensao;
  // Caminho de onde ficará a imagem
  $caminho_imagem = $diretorio . "/" . $nome_imagem;
  // Faz o upload da imagem para seu respectivo caminho
  move_uploaded_file($arquivo["tmp_name"], $caminho_imagem);
  // Insere os dados no banco com arquivo inclusive
} else {
  $caminho_imagem = "";
}
try {
  $grav = $pdo->prepare("INSERT INTO historico(numchamado, texto,"
    . " anexo, data, hora) "
    . "VALUES (:numchamado, :texto,"
    . " :anexo, :data, :hora)");
  $grav->bindParam(":numchamado", $numchamado, PDO::PARAM_STR);
  $grav->bindParam(":texto", $chamado, PDO::PARAM_LOB);
  $grav->bindParam(":anexo", $caminho_imagem, PDO::PARAM_STR);
  $grav->bindParam(":data", $dataabertura, PDO::PARAM_STR);
  $grav->bindParam(":hora", $horaabertura, PDO::PARAM_STR);
  $executa = $grav->execute();

  $gravsv = $pdo->prepare("UPDATE chamado SET situacao = :situacao WHERE numchamado = :numchamado");
  $gravsv->bindParam(":situacao", $situacao, PDO::PARAM_INT);
  $gravsv->bindParam(":numchamado", $numchamado, PDO::PARAM_STR);

  $gravtec = $pdo->prepare("UPDATE chamado SET tecnico = :tecnico WHERE numchamado = :numchamado");
  $gravtec->bindParam(":tecnico", $_SESSION['auth_data']['id'], PDO::PARAM_INT);
  $gravtec->bindParam(":numchamado", $numchamado, PDO::PARAM_STR);

  $gravTermino = $pdo->prepare("UPDATE chamado SET datafechamento = :data WHERE numchamado = :numchamado");
  $gravTermino->bindParam(":data", $dataabertura, PDO::PARAM_STR);
  $gravTermino->bindParam(":numchamado", $numchamado, PDO::PARAM_STR);

  if ($executa) {
    $gravsv->execute();
    if ($_SESSION['auth_data']['contahd'] == '3') {
      $gravtec->execute();
    }
    if ($situacao == '3') {
      $gravTermino->execute();
    }
  } else {
    $msgerro = base64_encode('Erro na base de dados!');
    header('Location: chamado.php?out=' . $criptolink . '&token=' . $msgerro);
    exit();
  }
} catch (PDOException $e) {
  echo $e->getMessage();
}
$msgsuccess = base64_encode('Chamado ' . $numchamado . ' enviado com sucesso!');
header('Location: chamado.php?out=' . $criptolink . '&token2=' . $msgsuccess);
exit();
