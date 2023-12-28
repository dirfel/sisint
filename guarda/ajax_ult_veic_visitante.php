<?php

require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if (!($_SESSION['nivel_guarda'] == "Anotador Gda" || $_SESSION['nivel_guarda'] == "Cabo Gda" || $_SESSION['nivel_guarda'] == "Oficial e Sargento" || $_SESSION['nivel_guarda'] == "Supervisor" || $_SESSION['nivel_guarda'] == "Administrador")) {
  $msgerro = base64_encode('Usuário não possui acesso! Somente usuários: Anotador Gda e Cabo Gda!');
  header('Location: index.php?token=' . $msgerro);
  exit();
}

$pdo = conectar("guarda");

$id_visitante = base64_decode($_GET['id_visitante']);

$sql = 'SELECT id, idveiculo FROM visitante WHERE id = ' . $id_visitante;
$stmt = $pdo->prepare($sql);
$stmt->execute();
$veic = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]['idveiculo'];
print(base64_encode($veic));

?>