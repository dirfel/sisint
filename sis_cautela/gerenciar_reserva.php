<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

?>

<!doctype html>
<html>
<head>
    <script>
        var mat = 0;
    </script>
    <script src="../recursos/vendor/jquery/jquery-1.12.3.min.js"></script>
    <script>
        // script ajax para solicitar a quantidade de determinado item ao selecinar um material para editar
        function getQuant(id) {
            
            let data = {material: id.value};
            $.post(
                'ajax_get_quant.php', 
                data,
                function(result){
                    $('#newname').val(JSON.parse(result).descricao);
                    $('#newquant').val(JSON.parse(result).quant);
                    $('#newdate').val(JSON.parse(result).inclusao);
                }
            ); 
        }
    </script>
<style>
    .form-class {
        float: left;
    }

    table, th, td {
      border: 1px solid black;
      border-collapse: collapse;
      text-align: center;
      
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
        @page { margin: 5; }
        body { margin: 1.6cm }
        table th:last-child {display:none}
        table td:last-child {display:none}
        form {display:none}
        .non-print {display:none}
    }
    </style>
</head>
<body>
    <a class="non-print" href="index.php">VOLTAR</a>
<?php


$pdo = conectar("siscautela");
$consulta = $pdo->prepare("SELECT nome_dep FROM depositos WHERE id = {$_SESSION['auth_data']['nivelacessocautela'][0]}");
$consulta->execute();
$nomedep = $consulta->fetchAll(PDO::FETCH_BOTH);
$consulta = $pdo->prepare("SELECT * FROM listamat WHERE quant > 0 AND dep_id = {$_SESSION['auth_data']['nivelacessocautela'][0]} ORDER BY descricao");
$consulta->execute();
$itens = $consulta->fetchAll(PDO::FETCH_BOTH);    
?>


    <h1 style="text-align: center;"><?= $nomedep[0][0] ?></h1>
    <div style="align-top">
        <span style="width: 40%; display: inline-block;">
        <form id="add_form" action="edit_reserva.php" method="post">
                <div><h3>Cadastrar Material</h3></div>
                <div class="form-class">                
                    <label for="descr">Descrição:</label>
                    <input id="descr" type="text" name="descricao"><br><br>
                    <label for="quant">Quantidade existente:</label>
                    <input id="quant" type="number" name="quant"><br><br>
                    <label for="inclusao">Inclusão na carga:</label>
                    <input id="inclusao" type="text" name="inclusao"><br><br>
                    <button type="submit" name="action" value="criar_item" class="btn btn-primary">Adicionar item na reserva</button>
                </div>
            </form>
        </span>
        
        <span style="width: 40%; display: inline-block;">
        <form id="edit_form" action="edit_reserva.php" method="post"> 
            <div><h3>Editar material</h3></div>
            <div class="form-class">
                <label for="oldname">Selecione o material:</label>
                <select id="oldname" name="oldname" onchange="mat = this; getQuant(mat);"> 
                    <option value="0">Selecione</option>
                    <?php foreach($itens as $item){
                        print('<option value="'.$item['id'].'">'.$item['descricao'].'</option>');
                    } ?>
                </select><br><br>
                <label for="newname">Novo nome:</label>
                <input id="newname" type="text" name="newname"><br><br>
                <label for="newquant">Nova quantidade:</label>
                <input id="newquant" type="number" name="newquant"><br><br>
                <label for="newdate">Inclusão na carga:</label>
                <input id="newdate" type="text" name="newdate"><br><br>
                <button type="submit" name="action" value="renomear_item" class="btn btn-primary">Editar material</button><br>
            </div><br><br>
        </form>
    </span>
    </div><br><br>

    <table class="searchable sortable">
    <thead>
    <tr>
      <th>Material descrição</th>
      <th>Inclusão em Carga</th>
      <th>Quantidade existente</th>
      <th>Quantidade cautelado</th>
      <th>Quantidade Disponivel</th>
      <th class="non-print">Ações</th>
    </tr>
    </thead>
    <tbody>

    <?php
    $hoje = date('Y-m-d');
    foreach($itens as $item) {
        $consulta1 = $pdo->prepare("SELECT sum(quantidade) FROM cautela WHERE id_deposito = {$_SESSION['auth_data']['nivelacessocautela'][0]} and extravio = 0 and material = {$item['id']}");
        $consulta1->execute();
        $itemCount = $consulta1->fetchAll(PDO::FETCH_BOTH);
     ?>
    <tr>
        
        <td><?= $item['descricao'] ?></td>
        <td class="col-data"><?= $item['inclusao'] ?></td>
        <td><?= $item['quant'] ?></td>
        <td><?= $itemCount[0]['sum(quantidade)'] ?? 0 ?></td>
        <td><?= $item['quant'] - ($itemCount[0]['sum(quantidade)'] ?? 0) ?></td>
        <td class="non-print">
            <form action="edit_reserva.php" method="post">
                <input type="hidden" name="item" value="<?=$item['id'] ?>">
                <button onclick="return confirm('Você realmente quer remover 1 na linha <?= $item['descricao'] ?>?')" type="submit" name="action" value="remove1">-1</button>
                <button onclick="return confirm('Você realmente quer adicionar 1 na linha <?= $item['descricao'] ?>?')" type="submit" name="action" value="add1">+1</button>
                <button onclick="return confirm('Você realmente quer remover todos da linha <?= $item['descricao'] ?>?')" type="submit" name="action" value="remove_all">Excluir</button>
            </form>
        </td>
        
    </tr>
    <?php } ?>
    </tbody>
    </table>
    <br>
    
</body>
</html>