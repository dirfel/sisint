<?php
// para criar registro de ferias é usado esse arquivo
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
if ($_SESSION['nivel_plano_chamada'] == "Sem Acesso") {
    $msgerro = base64_encode('Usuário não possui acesso!');
    header('Location: ../sistemas/index.php?token=' . $msgerro);
    exit();
}
$p1 = conectar('controlepessoal');
$sql = 'INSERT INTO ferias (id_usr, tipo, anoref, datainicio) VALUES ('.$_POST['id_usr'].', 0, '.$_POST['anoref'].', "'.$_POST['datainicio'].'")';
$stmt = $p1->prepare($sql);

$res = $stmt->execute();
header('location: ferias_cadastrar1.php?id_usr='.$_POST['id_usr'].'&token2='.base64_encode('Informação registrada'));
?>