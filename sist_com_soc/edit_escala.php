<?php
include "../recursos/models/conexao.php";
require "../recursos/models/versession.php";
if ($_SESSION['nivel_com_soc'] == "Sem Acesso") {
    $msgerro = base64_encode('Usuário não possui acesso!');
    header('Location: ../sistemas/index.php?token=' . $msgerro);
    exit();
}
$pdo2 = conectar("sistcomsoc");

$_POST['date'] = $_POST['date'][6].$_POST['date'][7].$_POST['date'][8].$_POST['date'][9].'-'.$_POST['date'][3].$_POST['date'][4].'-'.$_POST['date'][0].$_POST['date'][1];

$sql = 'DELETE FROM escala_permanencia WHERE date = '.$_POST['date'];
$acao = $pdo2->prepare($sql);
$acao->execute();

$sql = 'INSERT INTO escala_permanencia (id_perm, date) VALUES ('.$_POST['id_perm'].', "'.$_POST['date'].'")';
$acao = $pdo2->prepare($sql);
$acao->execute();
header('Location: escala_ht.php');

?>