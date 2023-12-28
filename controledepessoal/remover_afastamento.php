<?php

require "../recursos/models/versession.php";
require "../recursos/models/conexao.php";

$p1 = conectar("membros");
$stmte = $p1->prepare('SELECT * FROM afastamentos WHERE af_id = ' . base64_decode($_GET['id']));
$stmte->execute();
$afastamento = $stmte->fetchAll(PDO::FETCH_ASSOC)[0];



if(count($afastamento) == 0) { // se não encontar o id solicitado, retorna esse erro
    header('location: livro_afastamento.php?token='. base64_encode('Erro desconhecido!'));
    exit();
} else if($_SESSION['nivel_plano_chamada'] == 'Administrador' || $_SESSION['nivel_plano_chamada'] == 'Supervisor') { // administrador e supervisor conseguem apagar qualquer registro
    $stmt = $p1->prepare('DELETE FROM afastamentos WHERE af_id = '. base64_decode($_GET['id']));
    $stmt->execute();
    header('location: livro_afastamento.php?token2='. base64_encode('Lançamento removido com sucesso!!'));
    exit();
} else if($afastamento['militar'] == $_SESSION['auth_data']['id']) { // o próprio militar só pode apagar afastamentos futuros
    if(date_converter($afastamento['fim']) > date('Y-m-d')) {
        $stmt = $p1->prepare('DELETE FROM afastamentos WHERE af_id = '. base64_decode($_GET['id']));
        $stmt->execute();
        header('location: livro_afastamento.php?token2='. base64_encode('Lançamento removido com sucesso!!'));
        exit();
    } else {
        header('location: livro_afastamento.php?token='. base64_encode('Você não possui permissão para fazer essa ação!'));
        exit();
    }
} else { // qualquer outra situação vai rejeitar a ação
    header('location: livro_afastamento.php?token='. base64_encode('Você não possui permissão para fazer essa ação!'));
    exit();
}


?>