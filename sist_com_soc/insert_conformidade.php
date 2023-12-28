<?php

include "../recursos/models/conexao.php";
require "../recursos/models/versession.php";
if ($_SESSION['nivel_com_soc'] == "Sem Acesso") {
    $msgerro = base64_encode('Usuário não possui acesso!');
    header('Location: ../sistemas/index.php?token=' . $msgerro);
    exit();
}
$pdo3 = conectar("sistcomsoc");

$action = $pdo3->prepare('UPDATE conformador SET id_conformador = '.$_POST["conformador"]);
$action->execute();

$action = $pdo3->prepare('INSERT INTO  conformidade 
(status_ug1, descricao_ug1, status_ug2, descricao_ug2) 
VALUES("'.$_POST["status_ug1"].'", "'.$_POST["descricao_ug1"].'", "'.$_POST["status_ug2"].'", "'.$_POST["descricao_ug2"].'")');
$action->execute();

header('Location: conformidade.php');
?>