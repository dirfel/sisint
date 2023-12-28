<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";


if($_SESSION['nivel_sis_cautela'] != "Enc Mat") {
    die('Usuario sem permissão.');
}
$pdo = conectar("siscautela");
$consulta1 = $pdo->prepare("SELECT dep_id FROM listamat WHERE id = ".$_POST['item']);
$consulta1->execute();
$dep_id_item = $consulta1->fetchAll(PDO::FETCH_BOTH);





if($_POST['action'] == 'criar_item') {
    $consulta = $pdo->prepare("INSERT INTO listamat (descricao, quant, dep_id, inclusao) VALUES('".$_POST['descricao']."', ".$_POST['quant'].", ".$_SESSION['auth_data']['nivelacessocautela'][0].", '".$_POST['inclusao']."')");
//} else if($_SESSION['user_numconta'] != $dep_id_item[0][0]) {
//    die('Você não tem permissão.');
} else if($_POST['action'] == 'remove1') {
    $consulta = $pdo->prepare("UPDATE listamat SET quant = quant - 1 WHERE id = ".$_POST['item']);
    
} else if($_POST['action'] == 'remove_all') {
    $consulta = $pdo->prepare("UPDATE listamat SET quant = 0 WHERE id = ".$_POST['item']);
    
} else if($_POST['action'] == 'add1') {
    $consulta = $pdo->prepare("UPDATE listamat SET quant = quant + 1 WHERE id = ".$_POST['item']);
    
} else if($_POST['action'] == 'renomear_item') {
    $consulta = $pdo->prepare("UPDATE listamat SET descricao = '".$_POST['newname']."', quant = '".$_POST['newquant']."', inclusao = '".$_POST['newdate']."' WHERE id = ".$_POST['oldname']);
}
$consulta->execute();
$acao = $consulta->fetchAll(PDO::FETCH_BOTH);

header('location: gerenciar_reserva.php');

//por fim redirecionar à pagina gerenciar_reserva.php

?>