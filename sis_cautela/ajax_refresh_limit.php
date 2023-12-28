<?php
session_start();
require "../recursos/models/conexao.php";
$pdo = conectar("siscautela");


//obtendo quantidade total existente
$sql = 'SELECT quant FROM listamat WHERE id = '. ($_POST["material"]);
$stmt = $pdo->prepare($sql);
$stmt->execute();
$total = $stmt->fetchAll(PDO::FETCH_ASSOC);
$total = $total[0]['quant'] ?? 0;


// obtendo total cautelado:
$sql2 = "SELECT sum(quantidade) FROM cautela WHERE id_deposito = {$_SESSION['auth_data']['nivelacessocautela'][0]} and material = {$_POST['material']} and extravio = 0";
$stmt2 = $pdo->prepare($sql2);
$stmt2->execute();
$cautelados = $stmt2->fetchAll(PDO::FETCH_ASSOC)[0]['sum(quantidade)'] ?? 0;


echo(intval($total) - intval($cautelados));
?>