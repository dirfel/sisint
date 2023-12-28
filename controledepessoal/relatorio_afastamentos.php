<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if ($_SESSION['nivel_plano_chamada'] != "Administrador" && $_SESSION['nivel_plano_chamada'] != "Supervisor") {
    header('location: livro_afastamentos.php?token='. base64_encode('Usuário sem permissão!'));
    exit();
}

$p1 = conectar("membros");

$motivo = ['Missão', "Descanso", "Tratamento de saúde", "Outro"];

//1. obtenho a lista dos afastamentos futuros

$cons = $p1->prepare("SELECT * FROM afastamentos INNER JOIN usuarios ON afastamentos.militar = usuarios.id ORDER BY idpgrad,nomeguerra,afastamentos.af_id ASC");
$cons->execute();
$cons = $cons->fetchAll(PDO::FETCH_ASSOC);


?>
<!doctype html>
<html>
<head>
    <style>
    table, th, td {
      border: 1px solid black;
      border-collapse: collapse;
      text-align: center;
      font-size: 12px;
      
    }
    table {
        width: 100%;
        page-break-inside:auto;
    }

    tr { 
        page-break-inside:avoid; 
        page-break-after:auto;
    }

    thead {
        display:table-header-group;
    }
    
    tfoot {
        display:table-footer-group;
    }

    h4, h5, h6, p {
    padding: 0px;
    margin: 0px;
    }

    @media print {
        @page { margin: 1.6; }
        table th:last-child {display:none}
        table td:last-child {display:none}
    }
    </style>
</head>
    <body>
    <div style="align-items: center; justify-content: center; text-align: center; margin-bottom: 0;">
    <?php render_cabecalho_documento(); ?>
    <h3>REGISTROS DO LIVRO DE AFASTAMENTO</h3>
</div>

<?php
$consulta = array();
foreach($cons as $reg) {
    if(date_converter($reg['fim']) > date('Y-m-d')){
        array_push($consulta, $reg);
    }
}

// $consulta = $cons;
if(count($consulta) > 0) {
?>

<table style="border: 1px solid black;">
  <thead>
    <tr>
      <th>Ord</th>
      <th>Post/Grad</th>
      <th>Nome de Guerra</th>
      <th>Início</th>
      <th>Fim</th>
      <th>Destino</th>
      <th>Fone Celular</th>
      <th>Motivo</th>
      <th>Observação</th>
    </tr>
  <thead>
    <?php
    $ord = 0;
    foreach($consulta as $registro) {
        $ord++;
    ?>
    <tr>
        <td><?= $ord?></td>
        <td><?= getPGrad($registro["idpgrad"])?></td>
        <td><?= $registro['nomeguerra'] ?></td>
        <td><?= $registro['inicio']?></td>
        <td><?= $registro['fim'] ?></td>
        <td><?= $registro['destino']?></td>
        <td><?= $registro['fonecelular']?></td>
        <td><?= $motivo[$registro['motivo']]?></td>
        <td><?= $registro['obs'] ?></td>
      </tr>
      <?php
    }
    ?>
  </table>
  <?php } else {
        echo '<h4>Não há registros neste momento</h4>';
     } ?>
  <div style="text-align: center;">
    <br>
    <br>
    <br>
    <h5><?= $_SESSION['auth_data']['nomecompleto'] ?> - <?= getPGrad($_SESSION['auth_data']["idpgrad"]) ?></h5>
    <p><?= 'Relatório gerado em: ' .  diaPorExtenso(date('d'), date('m'), date('Y')) ?></p>
  </div>
</body>
<!doctype html>