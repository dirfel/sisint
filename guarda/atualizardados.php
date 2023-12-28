<?php
require "../recursos/models/conexao.php";
$pdo1 = conectar("membros");
$totalmembros = "SELECT * FROM usuarios";
$stmt1 = $pdo1->prepare($totalmembros);
$stmt1->execute();
$result1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
echo(count($result1));
