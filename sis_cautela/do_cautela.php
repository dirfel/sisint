<?php

require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if($_SESSION['auth_data']['nivelacessocautela'] == 0) {
    die('Você não possui permissão para isso');
}


$pdo = conectar("siscautela");

    $sql = "INSERT INTO cautela 
(id_deposito, militar, material, quantidade, nr_serie, situacao_cautela, operador)
VALUES (".intval($_SESSION['auth_data']['nivelacessocautela'][0]).", ".intval($_POST['militar']).", ".intval($_POST['material']).", ".intval($_POST['quant']).", '".$_POST['nr_serie']."', '".$_POST['alteracao']."', ".intval($_SESSION['auth_data']['id']).")";
    
$consulta = $pdo->prepare($sql);
$consulta->execute();
$cautelas = $consulta->fetchAll(PDO::FETCH_BOTH);



?>
<script>
    alert('Cautela realizada com sucesso');
    window.close();
</script>