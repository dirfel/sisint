<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

$p1 = conectar("agenda");
if($_SESSION['nivel_fatos_observados'] == 'Sem Acesso') {
    header('Location: ../sistemas?token='. base64_encode('Erro! Você não possui permissão para acessar.'));
    exit();
}
if(!isset($_GET['id'])) {
    header('Location: ./index.php?token='. base64_encode('Erro! Você não possui permissão para editar esse evento.'));
    exit();
}
$sql = "SELECT * FROM evento WHERE id = ".$_GET['id'];
$stmt = $p1->prepare($sql);
$stmt->execute();
$consulta = $stmt->fetchAll(PDO::FETCH_ASSOC);
if((count($consulta) == 0) || $consulta[0]['autor'] != $_SESSION['auth_data']['id']) {
    header('Location: ./index.php?token='. base64_encode('Erro! Você não possui permissão para editar esse evento.'));
    exit();
}

if($_POST['acao'] == 'EXCLUIR') {
    $sql = "DELETE FROM evento WHERE id = ".$_GET['id'];
    $stmt = $p1->prepare($sql);
    $stmt->execute();
    header('location: index.php?token2='.base64_encode('Excluído com sucesso'));
    exit();
} else if ($_GET['acao'] == 'trocaupload') {
    $numchamado = time() . "" . $_SESSION['auth_data']['id']; 
    $arquivo = isset($_FILES["arquivo"]) ? $_FILES["arquivo"] : FALSE;
    $caminho_arquivo = processa_upload($numchamado, $arquivo);

    if($consulta[0]['anexo'] != '') {
        remove_arquivo($consulta[0]['anexo']);
    }

    $sql = 'UPDATE evento SET anexo = :anexo WHERE id = :id';
    $stmt = $p1->prepare($sql);
    $stmt->bindValue(':anexo', $caminho_arquivo, PDO::PARAM_STR);
    $stmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $stmt->execute();
    header('location: edit_evento.php?id='.$_GET['id'].'&token2='.base64_encode('Anexo atualizado'));
    exit();
} else if ($_GET['acao'] == 'remover_anexo') {
try{
    remove_arquivo($consulta[0]['anexo']);

    $sql = 'UPDATE evento SET anexo = "" WHERE id = :id';
    $stmt = $p1->prepare($sql);
    $stmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $stmt->execute();
    header('location: edit_evento.php?id='.$_GET['id'].'&token2='.base64_encode('Excluído com sucesso'));
    exit();
} catch (Error $e) {print_r($e);}
}
if($_POST["datahorainicio"] > $_POST["datahorafim"] || $_POST["datahorafim"] == '') {
    $_POST["datahorafim"] = $_POST["datahorainicio"];
}

$sql = "UPDATE evento SET autor = ".$_SESSION["auth_data"]["id"].", titulo = '".$_POST["titulo"]."', 
descricao = '".$_POST["descricao"]."', datahorainicio = '".$_POST["datahorainicio"]."', datahorafim = '".$_POST["datahorafim"]."', viz = '".serialize($_POST["viz"])."' WHERE id = ".$_GET['id'];
$stmt = $p1->prepare($sql);
$stmt->execute();

header('location: edit_evento.php?id='.$_GET['id'].'&token2='.base64_encode('Atualizado com sucesso'));
exit();

?>
