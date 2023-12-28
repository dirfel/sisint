<?php
session_start();
require "../recursos/models/conexao.php";
$pdo = conectar("siscautela");

//var_dump($_POST['material']);
//obtendo quantidade total existente
$sql = 'SELECT descricao, quant, inclusao FROM listamat WHERE id = '. ($_POST["material"]);
$stmt = $pdo->prepare($sql);
$stmt->execute();
$total = $stmt->fetchAll(PDO::FETCH_ASSOC);
$total = $total[0];

print(json_encode($total));
?>