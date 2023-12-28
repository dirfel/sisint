<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
if ($_SESSION['nivel_helpdesk'] != "Administrador") {
    header('Location: index.php');
    exit();
}
$p1 = conectar("membros");
$p2 = conectar("siscautela");


// obter o menor ID de 1 dígito que ainda não está em uso
$stmt = $p2->prepare('SELECT MIN(id + 1) AS next_id FROM depositos WHERE id+1 NOT IN (SELECT id FROM depositos)');
$stmt->execute();
$next_id = $stmt->fetch(PDO::FETCH_ASSOC)['next_id'];

// inserir o novo depósito no banco de dados
$stmt = $p2->prepare('INSERT INTO depositos (id, nome_dep, responsavel, func_responsavel) VALUES (:id, :nome_dep, :responsavel, :func_responsavel)');
$stmt->bindParam(':id', $next_id);
$stmt->bindParam(':nome_dep', $_POST['nome_dep']);
$stmt->bindParam(':responsavel', $_POST['responsavel']);
$stmt->bindParam(':func_responsavel', $_POST['func_responsavel']);
$result = $stmt->execute();

// verificar se a inserção foi bem-sucedida
if ($result) {
  $msg_sucesso = "Depósito criado com sucesso!";
  header("Location: deps.php?token2=" . base64_encode($msg_sucesso));
} else {
  $msg_erro = "Erro ao criar depósito.";
  header("Location: deps.php?token=" . base64_encode($msg_erro));
}
?>