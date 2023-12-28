<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if($_SESSION['auth_data']['nivelacessocautela'][0] == 0) {
    die('Você não possui permissão para isso');
}

$pdo = conectar("siscautela");

if($_GET['func'] == 'minusall' || $_GET['qtd'] == 1) {
    $sql = "UPDATE cautela SET extravio = 1 WHERE id = ". $_GET['caut_id'];

} else if($_GET['func'] == 'minus1') {
    $sql = "UPDATE cautela SET quantidade = quantidade - 1 WHERE id = '{$_GET['caut_id']}'";
}

$consulta = $pdo->prepare($sql);
$consulta->execute();
$cautelas = $consulta->fetchAll(PDO::FETCH_BOTH);

//obter user do relatorio id
$sql = "SELECT militar, material FROM cautela WHERE id = ". $_GET['caut_id'];
$consulta = $pdo->prepare($sql);
$consulta->execute();
$mil = $consulta->fetchAll(PDO::FETCH_BOTH);
if(str_ends_with($_SERVER['HTTP_REFERER'], 'relatorio_pessoal.php')) {
    header('Location: relatorio_pessoal.php?dep='.$_SESSION['auth_data']['nivelacessocautela'][0].'&relatorio_pessoal='.$mil[0]['militar']);
} else if(str_ends_with($_SERVER['HTTP_REFERER'], 'relatorio_material.php')) {
    header('Location: relatorio_material.php?dep='.$_SESSION['auth_data']['nivelacessocautela'][0].'&relatorio_material='.$mil[0]['material']);
} else {
    header('Location: '.$_SERVER['HTTP_REFERER']);
}
exit();
?>
