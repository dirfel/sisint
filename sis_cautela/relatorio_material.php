<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
if(isset($_GET['relatorio_material'])) {
    $_POST['relatorio_material'] = $_GET['relatorio_material'];
}
if(isset($_GET['dep'])) {
    $_POST['dep'] = $_GET['dep'];
}
//1. Preciso obter informações do material pelo id
$pdo = conectar("siscautela");
$p1 = conectar("membros");
$consulta = $pdo->prepare("SELECT * FROM listamat WHERE id = {$_POST['relatorio_material']}");
$consulta->execute();
$itemName = $consulta->fetchAll(PDO::FETCH_BOTH);

//2. Agora preciso obter informações da reserva de material
$pdo = conectar("siscautela");
$consulta = $pdo->prepare("SELECT * FROM depositos WHERE id = {$_SESSION['auth_data']['nivelacessocautela'][0]}");
$consulta->execute();
$deps = $consulta->fetchAll(PDO::FETCH_BOTH);

//2.1 consulto agora a informação do chefe do depósito
$consulta = $p1->prepare("SELECT * FROM usuarios WHERE nivelacessocautela LIKE '" . $_POST['dep'][0] . "S'");
$consulta->execute();
$chefe = $consulta->fetchAll(PDO::FETCH_BOTH)[0];

//3. Preciso Obter a lista de usuarios que possuem esse item cautelado com informações adicionais
$pdo = conectar("siscautela");
$consulta = $pdo->prepare("SELECT * FROM cautela WHERE extravio = 0 AND material = {$_POST['relatorio_material']}");
$consulta->execute();
$cautelas = $consulta->fetchAll(PDO::FETCH_BOTH);

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
        body { margin: 1.6cm }
        table th:last-child {display:none}
        table td:last-child {display:none}
    }
    </style>
</head>
    <body>

    <div style="
align-items: center; 
/* border: 1px solid black;  */
justify-content: center;
text-align: center;
margin-bottom: 0;
">

    <img src="../recursos/assets/brasao_armas.gif" style="width:80px;">
    <h4>MINISTÉRIO DA DEFESA</h4>
    <h4>EXÉRCITO BRASILEIRO</h4>
    <h4><?=strtoupper(NOME_OM)?></h4>
    <h4>(<?=ORIGEM?>)</h4>
    <h4><?=strtoupper(NOME_HISTORICO_OM)?></h4>
    
    <h3>CAUTELA DE MATERIAL - <?= $deps[0]['nome_dep']?></h3>
</div>
<div>
    <br>
    <p>Militares que possuem em sua cautela: <strong><?= $itemName[0]['descricao'] ?></strong></p>
    <br>
</div>
<?php
if(count($cautelas) > 0) {
?>

<table style="border: 1px solid black;">
    <thead><tr>
      <th>Ord</th>
      <th>Post/Grad</th>
      <th>Nome de Guerra</th>
      <th>Qtde</th>
      <th>Nr série</th>
      <th>Data da Cautela</th>
      <th>Operador</th>
      <th>Ações</th>
    </tr></thead>
    <?php
    $pdo = conectar("membros");

    $ord = 0;
    foreach($cautelas as $cautela) {
        $ord++;
        $consulta = $pdo->prepare("SELECT id, nomecompleto, nomeguerra, idpgrad FROM usuarios WHERE id = ".$cautela['militar']);
        $consulta->execute();
        $reg = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $selectedUser = $reg[0];

        //5. Obterei agora o nome do operador (quem realizou a cautela do material em questão)
        $operador = '-';
        if($cautela['operador'] != '0') {
            
            $pdo = conectar("membros");
            
            $consulta = $pdo->prepare("SELECT id, nomeguerra, idpgrad FROM usuarios WHERE id = ".$cautela['operador']);
            $consulta->execute();
            $op = $consulta->fetchAll(PDO::FETCH_ASSOC);
            $operador = getPGrad($op[0]["idpgrad"]).' '. $op[0]['nomeguerra'];
        }
    ?>
    <tr>
        <td><?= $ord?></td>
        <td><?= getPGrad($selectedUser["idpgrad"])?></td>
        <td><?= $selectedUser['nomeguerra'] ?></td>
        <td><?= $cautela['quantidade']?></td>
        <td><?= $cautela['nr_serie']?></td>
        <td><?= date_format(date_create($cautela['data_cautela']), 'd/m/Y') ?></td>
        <td><?= $operador ?></td>
        <td>
            <a href="manip_cautela.php?func=minus1&qtd=<?= $cautela['quantidade']?>&caut_id=<?= $cautela['id'] ?>" onclick="return confirm('Você tem certeza?')">Reduzir 1</a> | 
            <a href="manip_cautela.php?func=minusall&qtd=<?= $cautela['quantidade']?>&caut_id=<?= $cautela['id'] ?>" onclick="return confirm('Você tem certeza?')">Devolver</a>
        </td>
      </tr>
      <?php
    }
    ?>
  </table>
  <?php
    } else {
        echo '<h4>Esse material não possui cautelas neste momento</h4>';
     }
    ?>



  <div style="text-align: center;">
    <br>
    <br>
    <br>
    <h5><?= $chefe['nomecompleto'] ?> - <?= getPGrad($chefe["idpgrad"]) ?></h5>
      <h6><?= $deps[0]['func_responsavel']?></h6>
  </div>
</body>
<!doctype html>