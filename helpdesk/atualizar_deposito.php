<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
if ($_SESSION['nivel_helpdesk'] != "Administrador") {
    header('Location: index.php');
    exit();
}
$p1 = conectar("membros");
$p2 = conectar("siscautela");


// Verifique se o formulário foi enviado
if (isset($_POST['responsavel']) && isset($_POST['nome_dep']) && isset($_POST['dep_id'])) {

    // Prepare a consulta SQL para atualizar as informações do depósito
   
    $stmt = $p2->prepare("UPDATE depositos SET responsavel=?, func_responsavel=?, nome_dep=? WHERE id=?");
    $responsavel = $_POST["responsavel"];
    $func_responsavel = $_POST["func_responsavel"];
    $nome_dep = $_POST["nome_dep"];
    $dep_id = $_POST["dep_id"];

    // Execute a consulta SQL com os valores dos parâmetros
    if ($stmt->execute([$responsavel, $func_responsavel, $nome_dep, $dep_id])) {
      // As informações do depósito foram atualizadas com sucesso
      header('Location: deps.php?token2="'. base64_encode('As informações do depósito foram atualizadas com sucesso.') .'"');
      exit();
    } else {
      header('Location: deps.php?token="'. base64_encode('Ocorreu um erro ao atualizar as informações do depósito.') .'"');
      exit();
    }
  }

?>