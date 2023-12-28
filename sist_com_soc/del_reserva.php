<?php

// Esse Arquivo remove uma reserva feita e recarrega a pagina hospedes_ht

// importar arquivos necessários para executar o código
include "../recursos/models/conexao.php";
require "../recursos/models/versession.php";
if ($_SESSION['nivel_com_soc'] == "Sem Acesso") {
    $msgerro = base64_encode('Usuário não possui acesso!');
    header('Location: ../sistemas/index.php?token=' . $msgerro);
    exit();
}
if(!isset($_GET['id'])) {
    header('Location: hospedes_ht.php?token=' . base64_encode('Erro inesperado, contate o administrador'));
}

$sql = 'DELETE FROM reservas WHERE id =' . $_GET['id'];

$pdo = conectar("sistcomsoc");
$action = $pdo->prepare($sql);
$action->execute();

header('Location: hospedes_ht.php?token2=' . base64_encode('Reserva apagada com sucesso'));
?>