<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
$p1 = conectar("agenda");
if($_SESSION['nivel_fatos_observados'] == 'Sem Acesso') {
    header('Location: ../sistemas');
}

$sql = "SELECT * FROM evento 
        WHERE ((MONTH(datahorainicio) >= " . $_GET['mes'] . "
        AND YEAR(datahorainicio) = " . $_GET['ano'] . ")
        OR (MONTH(datahorainicio) = 1
        AND YEAR(datahorainicio) = " . strval($_GET['ano'] + 1) . "))";

$stmt = $p1->prepare($sql);
$eventos = $stmt->execute();
$eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
// print_r($eventos);

$eventos_liberados = array();

foreach($eventos as $evento) {
    $evento['viz'] = unserialize($evento['viz']);
    // print_r($evento['viz']);
    if($evento['autor'] == $_SESSION['auth_data']['id']) {
        array_push($eventos_liberados, $evento);
        continue;
    } else {
        foreach($evento['viz'] as $perm) {
            if($evento['autor'] == $_SESSION['auth_data']['id'] || $perm == 'TODOS DA OM' || $perm == $_SESSION['auth_data']['id']) {
                array_push($eventos_liberados, $evento);
                continue;
            }
        }
    }
    
}

print_r(json_encode($eventos_liberados));

?>