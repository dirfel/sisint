<?php

require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";


if($_SESSION['nivel_sis_cautela'] != "Enc Mat") {
    die('Usuario sem permissão');
}

$pdo = conectar("membros");
$consulta = $pdo->prepare("UPDATE usuarios SET nivelacessocautela = '{$_SESSION['auth_data']['nivelacessocautela'][0]}A' WHERE id = {$_POST['relatorio_pessoal']}");
$consulta->execute();
$acao = $consulta->fetchAll(PDO::FETCH_BOTH);

header('location: gerenciar_equipe.php');

?>