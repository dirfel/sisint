<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
$p1 = conectar("agenda");
if($_SESSION['nivel_fatos_observados'] == 'Sem Acesso') {
    header('Location: ../sistemas');
}

if($_POST["datahorainicio"] > $_POST["datahorafim"] || $_POST["datahorafim"] == '') {
    $_POST["datahorafim"] = $_POST["datahorainicio"];
}

$numchamado = time() . "" . $_SESSION['auth_data']['id']; //strrev() inverte a string
$arquivo = isset($_FILES["arquivo"]) ? $_FILES["arquivo"] : FALSE;
$caminho_arquivo = processa_upload($numchamado, $arquivo);

$sql = "INSERT INTO evento (autor, titulo, descricao, anexo, datahorainicio, datahorafim, viz) 
VALUES (".$_SESSION["auth_data"]["id"].", '".$_POST["titulo"]."', '".$_POST["descricao"]."', 
'".$caminho_arquivo."', '".$_POST["datahorainicio"]."', '".$_POST["datahorafim"]."', '".serialize($_POST["viz"])."')";
    $stmt = $p1->prepare($sql);
    print($stmt->execute());

header('location: index.php?token2='.base64_encode('Cadastrado com sucesso'));
?>