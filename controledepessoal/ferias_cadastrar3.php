<?php
//para inserir férias futuras é usado esse arquivo
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
if ($_SESSION['nivel_plano_chamada'] == "Sem Acesso" || $_SESSION['nivel_plano_chamada'] == "Usuário comum") {
    $msgerro = base64_encode('Usuário não possui acesso!');
    header('Location: ../sistemas/index.php?token=' . $msgerro);
    exit();
}

$gozado = 0;
if($_POST['finalizado'] == 'on') {
    $gozado = 1;
} else if($_POST['iniciado'] == 'on') {
    $gozado = -1;
}

// obter data de fim
$dataFim = date('Y-m-d', strtotime('+ '.$_POST['duracao'].' days', strtotime($_POST['datainicio'])));

$p1 = conectar('controlepessoal');
$sql = 'INSERT INTO ferias (id_usr, tipo, anoref, datainicio, datafim, gozado) VALUES ('.$_GET['id_usr'].', '.$_POST['tipo'].', '.$_POST['anoref'].', "'.$_POST['datainicio'].'", "'.$dataFim.'", '.$gozado.')';
$stmt = $p1->prepare($sql);
$res = $stmt->execute();
header('location: ferias_cadastrar1.php?id_usr='.$_GET['id_usr'].'&token2='.base64_encode('Informação registrada'));
?>