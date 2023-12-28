<?php

require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
if(isset($_GET['relatorio_pessoal'])) {
    $_POST['relatorio_pessoal'] = $_GET['relatorio_pessoal'];
}
if(isset($_GET['dep'])) {
    $_POST['dep'] = $_GET['dep'];
}
if(!isset($_POST['relatorio_pessoal'])) {
    header('location: index.php?token2='.base64_decode('Erro! O ID não foi informado ao acessar o relatório pessoal.'));
    exit();
}
$p2 = conectar("siscautela");

//1. Primeiro irei obter informações do militar pesquisado
$selectedUser = read_usuario_by_id($_POST['relatorio_pessoal']);

//2. Agora preciso obter informações da reserva de material
$consulta = $p2->prepare("SELECT * FROM depositos WHERE id = ".$_POST['dep'][0]);
$consulta->execute();
$deps = $consulta->fetchAll(PDO::FETCH_ASSOC);

//2.1 consulto agora a informação do chefe do depósito
$consulta = conectar("membros")->prepare("SELECT * FROM usuarios WHERE nivelacessocautela LIKE '" . $_POST['dep'][0] . "S'");
$consulta->execute();
$chefe = $consulta->fetchAll(PDO::FETCH_ASSOC)[0] ?? '';

//3. Hora de obter a lista de material cautelado
$consulta = $p2->prepare("SELECT * FROM cautela WHERE id_deposito = ".$_POST['dep']." AND extravio = 0 AND militar = ".$selectedUser['id']);
$consulta->execute();
$listmat = $consulta->fetchAll(PDO::FETCH_BOTH);
?>

<head>
    <title>Cautelas de <?= $selectedUser['nomeguerra'] ?></title>
    <style>
    table, th, td { border: 1px solid black; border-collapse: collapse; text-align: center; font-size: 12px; }
    table { width: 100%; page-break-inside:auto; }
    tr { page-break-inside:avoid;  page-break-after:auto; }
    thead { display:table-header-group; }
    tfoot { display:table-footer-group; }
    h4, h5, h6, p { padding: 0px; margin: 0px; }
    @media print { @page { margin: 1.6; } table th:last-child { display:none } table td:last-child { display:none } }
    </style>
</head>
<body>

<div style="align-items: center; justify-content: center; text-align: center; margin-bottom: 0;">

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
    <p><strong>Post/Grad:</strong> <?= getPGrad($selectedUser["idpgrad"])?></p>
    <p><strong>Nome de Guerra:</strong> <?= $selectedUser['nomeguerra'] ?></p>  
    <p><strong>Nome Completo:</strong> <?= $selectedUser['nomecompleto'] ?></p>
    <br>
</div>
<?php if(count($listmat) > 0) { ?>
<table style="border: 1px solid black;">
    <tr>
      <th>Ord</th>
      <th>Material</th>
      <th>Nr Série</th>
      <th>Qtde</th>
      <th>Data Cautela</th>
      <th>Observação</th>
      <th>Operador</th>
      <?php if($_SESSION['auth_data']['nivelacessocautela'][0] == $_POST['dep'][0]) { ?>
      <th>Ações</th>
      <?php } ?>
    </tr>
    <?php $ord = 0;
    foreach($listmat as $mat) {
        $ord++;
        //4. obterei o nome do material
        $consulta = $p2->prepare("SELECT * FROM listamat WHERE id = {$mat['material']}");
        $consulta->execute();
        $matname = $consulta->fetchAll(PDO::FETCH_BOTH);
        //5. Obterei agora o nome do operador (quem realizou a cautela do material em questão)
        $operador = '-';
        if($mat['operador'] != '0') { $operador = ImprimeConsultaMilitar2(read_usuario_by_id($mat['operador'])); } ?>
        <tr>
            <td><?= $ord?></td>
            <td><?= $matname[0]['descricao'] ?></td>
            <td><?= $mat['nr_serie'] ?></td>
            <td><?= $mat['quantidade'] ?></td>
            <td><?= date_format(date_create($mat['data_cautela']), 'd/m/Y') ?></td>
            <td><?= $mat['situacao_cautela'] ?></td>
            <td><?= $operador ?></td>
            <?php if($_SESSION['auth_data']['nivelacessocautela'][0] == $_POST['dep'][0]) { ?>
            <td>
            <a href="manip_cautela.php?func=minus1&qtd=<?= $mat['quantidade']?>&caut_id=<?= $mat['id'] ?>" onclick="return confirm('Você tem certeza?')">Reduzir 1</a> | 
            <a href="manip_cautela.php?func=minusall&qtd=<?= $mat['quantidade']?>&caut_id=<?= $mat['id'] ?>" onclick="return confirm('Você tem certeza?')">Devolver</a>
        </td>
        <?php } ?>
        </tr>
    <?php } ?>         
  </table>
<?php } else { echo '<h4>Esse militar não possui cautela neste depósito</h4>'; } ?>
<br><br><br>
<div style="display: flex;">
  <span style="text-align: center; width: 50%;">
      <h5><?= consultaMilitarAssinatura($selectedUser['id']) ?></h5>
      <h6>Militar que cautelou</h6>
</span>
  <span style="text-align: center; width: 50%;">
      <h5><?= consultaMilitarAssinatura($chefe['id']) ?></h5>
      <h6><?= $deps[0]['func_responsavel']?></h6>
  </span>
</div>
</body>