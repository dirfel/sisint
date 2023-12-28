<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";


if($_SESSION['nivel_sis_cautela'] != "Enc Mat") {
    die('Usuario sem permissão');
}

$pdo = conectar("siscautela");
$consulta = $pdo->prepare("INSERT INTO listamat (descricao, quant, dep_id) VALUES('".$_POST['descricao']."', ".$_POST['quant'].", ".$_SESSION['auth_data']['nivelacessocautela'][0].")");
$consulta->execute();
$acao = $consulta->fetchAll(PDO::FETCH_BOTH);

header('location: gerenciar_reserva.php');


?>