<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
//1. Preciso obter informações dos materiais da reserva
$pdo = conectar("siscautela");
$consulta = $pdo->prepare("SELECT * FROM listamat");
$consulta->execute();
$itemName = $consulta->fetchAll(PDO::FETCH_BOTH);
$item_map = array();
foreach($itemName as $item) { $item_map += array($item['id'] => $item['descricao']); }

//2. Agora preciso obter informações da reserva de material
$pdo = conectar("siscautela");
$consulta = $pdo->prepare("SELECT * FROM depositos WHERE id = {$_SESSION['auth_data']['nivelacessocautela'][0]}");
$consulta->execute();
$deps = $consulta->fetchAll(PDO::FETCH_BOTH);

//2.1 consulto agora a informação do chefe do depósito
$consulta = conectar("membros")->prepare("SELECT * FROM usuarios WHERE nivelacessocautela LIKE '" . $_SESSION['auth_data']['nivelacessocautela'][0] . "S'");
$consulta->execute();
$chefe = $consulta->fetchAll(PDO::FETCH_BOTH)[0];

//3. Preciso Obter a lista de usuarios que possuem esse item cautelado com informações adicionais
$pdo = conectar("siscautela");
$consulta = $pdo->prepare("SELECT * FROM cautela WHERE extravio = 0 AND id_deposito = ". $_SESSION['auth_data']['nivelacessocautela'][0] . " ORDER BY material");
$consulta->execute();
$cautelas = $consulta->fetchAll(PDO::FETCH_BOTH);
?>
<!doctype html>
<html>
<head>
    <style>
    table, th, td { border: 1px solid black; border-collapse: collapse; text-align: center; font-size: 12px; }
    table { width: 100%; page-break-inside:auto; }
    tr { page-break-inside:avoid; page-break-after:auto; }
    thead { display:table-header-group; }
    tfoot { display:table-footer-group; }
    h4, h5, h6, p { padding: 0px; margin: 0px; }
    @media print { @page { margin: 1.6; } table th:last-child {display:none} table td:last-child {display:none} }
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
    <div><br><p>Relação de material cautelado:</p><br></div>
<?php if(count($cautelas) > 0) { ?>
<table style="border: 1px solid black;">
  <thead>
    <tr>
      <th>Ord</th>
      <th>Post/Grad</th>
      <th>Nome de Guerra</th>
      <th>Qtde</th>
      <th>Material</th>
      <th>Nr série</th>
      <th>Data da Cautela</th>
      <th>Operador</th>
      <th>Ações</th>
    </tr>
  <thead>
    <?php
    $pdo = conectar("membros");
    $ord = 0;
    foreach($cautelas as $cautela) {
        $ord++;
        $selectedUser = read_usuario_by_id($cautela['militar']);
        //5. Obterei agora o nome do operador (quem realizou a cautela do material em questão)
        $operador = '-';
        if($cautela['operador'] != '0') {
            $pdo = conectar("membros");
            $consulta = $pdo->prepare("SELECT id, nomeguerra, idpgrad FROM usuarios WHERE id = ".$cautela['operador']);
            $consulta->execute();
            $op = $consulta->fetchAll(PDO::FETCH_ASSOC);
            $operador = getPGrad($op[0]["idpgrad"]).' '. $op[0]['nomeguerra'];
        } ?>
    <tr>
        <td><?= $ord?></td>
        <td><?= getPGrad($selectedUser["idpgrad"] ?? 30) ?></td>
        <td><?= $selectedUser['nomeguerra'] ?? '<b>Erro, militar não cadastrado nessa cautela</b>' ?></td>
        <td><?= $cautela['quantidade']?></td>
        <td><?= $item_map[$cautela['material']]?></td>
        <td><?= $cautela['nr_serie']?></td>
        <td><?= date_format(date_create($cautela['data_cautela']), 'd/m/Y') ?></td>
        <td><?= $operador ?></td>
        <td>
            <a href="manip_cautela.php?func=minus1&qtd=<?= $cautela['quantidade']?>&caut_id=<?= $cautela['id'] ?>" onclick="return confirm('Você tem certeza?')">Reduzir 1</a> | 
            <a href="manip_cautela.php?func=minusall&qtd=<?= $cautela['quantidade']?>&caut_id=<?= $cautela['id'] ?>" onclick="return confirm('Você tem certeza?')">Devolver</a>
        </td>
      </tr>
      <?php } ?>
  </table>
  <?php } else { echo '<h4>Esse depósito não possui cautelas neste momento</h4>'; } ?>
  <div style="text-align: center;">
    <br><br><br>
    <h5><?= consultaMilitarAssinatura($chefe['id']) ?></h5>
      <h6><?= $deps[0]['func_responsavel']?></h6>
  </div>
</body>