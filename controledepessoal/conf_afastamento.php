<?php

require "../recursos/models/versession.php";
require "../recursos/models/conexao.php";

// se os parâmetros não forem passados corretamente, avisa o usuario que houve erro
if (!isset($_POST['inicio']) || !isset($_POST['fim']) || !isset($_POST['cidade']) || !isset($_POST['motivo']) ) {
    header('Location: livro_afastamento.php?token='.base64_encode('Erro desconhecido'));
    exit();
}

$p1 = conectar("membros");
  
$stmte = $p1->prepare('INSERT INTO afastamentos (inicio, fim, destino, fonecelular, motivo, obs, militar) VALUES (:inicio, :fim, :cidade, :fonecelular, :motivo, :obs, :militar)');
$stmte->bindParam(":inicio", $_POST['inicio'], PDO::PARAM_STR);
$stmte->bindParam(":fim", $_POST['fim'], PDO::PARAM_STR);
$stmte->bindParam(":cidade", $_POST['cidade'], PDO::PARAM_STR);
$stmte->bindParam(":motivo", $_POST['motivo'], PDO::PARAM_INT);
$stmte->bindParam(":fonecelular", $_POST['fonecelular'], PDO::PARAM_STR);
$stmte->bindParam(":obs", $_POST['obs'], PDO::PARAM_STR);
$stmte->bindParam(":militar", $_SESSION['auth_data']['id'], PDO::PARAM_INT);

$executa = $stmte->execute();

if ($executa) {
    $sistema = base64_encode("CONTROLE DE PESSOAL");
    $obs = "Afastamento registrado";

    $executa2 = gerar_log_usuario($sistema, $obs);
    $msgsucc = base64_encode('Cadastrado com sucesso!');

    header("Location: livro_afastamento.php?token2=" . $msgsucc);
  } else {
    $msgerro = base64_encode('Erro na base de dados!');
    header("Location: livro_afastamento.php?token=" . $msgerro);
    exit();
  }

?>