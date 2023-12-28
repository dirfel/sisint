<?php

require "../recursos/models/versession.php";

include "../recursos/models/conexao.php";
if ($_SESSION['nivel_helpdesk'] != "Administrador") {
    header('Location: index.php');
    exit();
  }
$pdo = conectar('guarda');
$dt = date_converter($_POST["dataLivro"]);
echo $dt;
$sql = 'UPDATE liv_partes_ofdia 
        SET editar = 1
        WHERE data = "'.$dt.'"';
$consulta = $pdo->prepare($sql);
$consulta->execute();
$acao = $consulta->fetchAll(PDO::FETCH_BOTH);

$token2 = 'sucesso';
header('location: desbloq.php');

?>