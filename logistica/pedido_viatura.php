<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
if($_SESSION['nivel_fatos_observados'] == 'Sem Acesso') { //TOOD: Verificar permissões
    header('Location: ../sistemas');
    exit();
}

if(count($_POST) > 0){
    if($_POST['btn_pedido'] == 'Cadastrar Pedido') {
        $executa = registrar_pedido_vtr($_POST);
        $token2 = '';
        if($executa) { $token2 = 'Pedido de Viatura cadastrado com sucesso';  }
    }
}

$consulta2 = listar_viaturas(); ?>

<!doctype html>
<html lang="pt-BR" class="fixed">
<head><?php include '../recursos/views/cabecalho.php'; ?></head>
<body>
  <div class="wrap">
    <div class="page-header"><?php render_painel_usu('LOGÍSTICA', $_SESSION['nivel_fatos_observados']); ?></div>
        <div class="page-body">
            <div class="left-sidebar"><?php include 'menu_opc.php'; ?></div>
                <div class="content">
                    <?php render_content_header('Pedido de Viatura', 'fa fa-truck'); ?>
                    <div class="row animated fadeInUp">
                        <?php include '../recursos/views/token.php'; ?>
                        <div class="col-sm-12 col-md-12">
                        <div class="panel"><?php render_cabecalho_painel('CADASTRAR PEDIDO DE VIATURA:', 'fa fa-add', true); ?>
                            <div class="panel-content">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form id="inline-validation" action="#" method="post">
                                            <div class="col-md-6"><table id="responsive-table" class="data-table table table-striped table-hover responsive nowrap">
                                            <thead><tr><th>Selecione as Viaturas desejadas:</th></tr></thead>
                                        <tbody>
                                                <?php foreach($consulta2 as $viatura) {
                                                    echo '<tr><td>';
                                                    render_checkbox($viatura['modelo'] . ' (' . $viatura['placa'] . ') - '. $viatura['total_ocupantes'] . ' ocupantes', 'vtr'.$viatura['id'], 'vtr[]', $viatura['id'], false);
                                                    echo '</td></tr>';
                                                } ?></table></div>
                                            <div class="col-md-6">
                                            <div class="col-md-12">
                                            <?php render_custom_input('Natureza do Deslocamento:', 'natureza', 'natureza', '', 50, 'Ex: Operação Ágata', true, false, 'fa fa-road'); ?></div>
                                            <div class="col-md-12"><?php render_custom_input('Itinerário:', 'itinerario', 'itinerario', '', 50, 'Ex: Três Lagoas - Dourados - Três Lagoas', true, false, 'fa fa-map'); ?></div>
                                            <div class="col-md-6"><?php render_custom_input('Distância estimada (total em Km):', 'distancia', 'distancia', '', 4, 'Ex: 650', true, false, 'fa fa-map'); ?></div>
                                            <div class="col-md-6"><?php render_custom_input('Total passageiros:', 'total_passageiros', 'total_passageiros', '2', 2, '', true, false, 'fa fa-users'); ?></div>
                                            <div class="col-md-6"><?php render_data_field('data_saida', true, 'Data saída:', null); ?></div>
                                            <div class="col-md-6"><?php render_hora_field('hora_saida', true, 'Hora saída:', false); ?></div>
                                            <div class="col-md-6"><?php render_data_field('data_chegada', true, 'Data chegada:', null); ?></div>
                                            <div class="col-md-6"><?php render_hora_field('hora_chegada', true, 'Hora chegada:', false); ?></div>
                                            <div class="col-md-12"><hr></div>
                                            <div class="col-md-12"><?php render_checkbox('Necessita apoio de abastecimento?', 'abastecimento', 'abastecimento', 'S', false); ?></div>
                                            <div class="col-md-12"><?php render_checkbox('Necessita apoio de alojamento?', 'alojamento', 'alojamento', 'S', false); ?></div>
                                            <div class="col-md-12"><?php render_checkbox('Necessita apoio de arranchamento?', 'arranchamento', 'arranchamento', 'S', false); ?></div>
                                            <div class="col-md-12">
                                                <div class="form-group"><hr>
                                                <button name="btn_pedido" type="submit" class="btn btn-primary" value="Cadastrar Pedido">CADASTRAR</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="panel"><?php render_cabecalho_painel('MEUS PEDIDOS:', 'fa fa-table', true); ?>
                                <div class="panel-content">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="panel"><?php render_cabecalho_painel('PEDIDOS NÃO FINALIZADOS:', 'fa fa-close', true); ?>
                                <div class="panel-content">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="panel"><?php render_cabecalho_painel('PEDIDOS FINALIZADOS:', 'fa fa-check', true); ?>
                                <div class="panel-content">
                                    <table class="data-table table table-striped table-hover responsive nowrap">
                                        <thead>
                                            <tr><td>Natureza</td><td>Itinerário</td><td>Viatura</td><td>Passageiros</td><td>Solicitante</td><td>Situação</td></tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $query = 'SELECT * FROM pedido_vtr';
                                            $stmt = conectar('guarda')->prepare($query);
                                            $stmt->execute();
                                            $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                            foreach($pedidos as $pedido) {
                                                echo '<tr>';
                                                echo '<td>' . $pedido['natureza'] . '</td>';
                                                echo '<td>' . $pedido['itinerario'] . '</td>';
                                                echo '<td>';
                                                foreach(unserialize($pedido['id_viatura']) as $vtr) {
                                                    echo $vtr . '<br>';
                                                }
                                                echo '</td>';
                                                echo '<td>' . $pedido['total_passageiros'] . '</td>';
                                                echo '<td>' . $pedido['id_solicitante'] . '</td>';
                                                echo '<td>' . $pedido['situacao'] . '</td>';
                                                echo '</tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php include '../recursos/views/scroll_to_top.php'; ?>
        </div>
    </div>
  <?php include '../recursos/views/footer.php'; ?>
</body>
</html>