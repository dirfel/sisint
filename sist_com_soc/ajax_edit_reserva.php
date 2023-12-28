<?php
header('Content-Type: application/json; charset-utf-8');
include "../recursos/models/conexao.php";
require "../recursos/models/versession.php";
if ($_SESSION['nivel_com_soc'] == "Sem Acesso") {
    $msgerro = base64_encode('Usuário não possui acesso!');
    header('Location: ../sistemas/index.php?token=' . $msgerro);
    exit();
}
if(!isset($_GET['id'])) {
    die('');
}

try{

    $pdo3 = conectar("sistcomsoc");
    
    $consulta3 = $pdo3->prepare('SELECT * FROM reservas WHERE id = '. $_GET['id']);
    
    $consulta3->execute();
    $reg3 = $consulta3->fetchAll(PDO::FETCH_ASSOC);
    // print(json_encode($reg3[0], JSON_FORCE_OBJECT));
    print_r(json_encode($reg3[0]));
}catch (Error $e) {
    die('');
}
    
?>