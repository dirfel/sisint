<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
if($_SESSION['nivel_fatos_observados'] == 'Sem Acesso') { //TOOD: Verificar permissões
    header('Location: ../sistemas');
    exit();
}

if(count($_POST) > 0){
    if($_POST['btn_add_vtr'] == 'Cadastrar Viatura') {
        add_viatura( $_POST['placa'], $_POST['tipo'],  $_POST['modelo'], $_POST['marca'], $_POST['consumo'], $_POST['combustivel'], $_POST['total_ocupantes'], $_POST['odometro']);
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
                    <?php render_content_header('Gestão de Viaturas', 'fa fa-home'); ?>
                    <div class="row animated fadeInUp">
                        <?php include '../recursos/views/token.php'; ?>
                        <div class="col-sm-12 col-md-6">
                            <?php render_cadastro_viatura_form(); ?>
                            <div class="panel"><?php render_cabecalho_painel('EDITAR VIATURA:', 'fa fa-edit', true); ?>
                                <div class="panel-content">
                                    <form id="inline-validation" action="editar_viaturas.php" method="post">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php $list_vtr = array();
                                                    foreach($consulta2 as $vtr) { $list_vtr[$vtr['id']] = $vtr['modelo'] . ' (EB ' . $vtr['placa'] . ')'; }
                                                    render_default_select('viatura', '', true, 'Selecione a viatura:', 'Selecione', 'fa fa-car', $list_vtr, ''); ?>
                                                    <div class="form-group"><hr>
                                                    <button name="btn_edit_vtr" type="submit" class="btn btn-primary">EDITAR</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="panel"><?php render_cabecalho_painel('TABELA DE VIATURAS:', 'fa fa-table', true); ?>
                                <div class="panel-content">
                                    <table id="responsive-table" class="data-table table table-striped table-hover responsive nowrap" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                            <th>Placa</th><th>Modelo</th><th>Situação</th><th>Disponibilidade</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($consulta2 as $reg2) {
                                            echo ("<tr class='text-center'>");
                                            echo ("<td>" . $reg2['placa'] . "</td>");
                                            echo ("<td>" . $reg2['modelo'] . "</td>");
                                            echo ("<td" . ($reg2['situacao'] == 0 ? '>Dentro da OM' : ' class="text-danger"><b>Fora da OM</b>') . "</td>");
                                            echo ("<td" . ($reg2['baixada'] == 0 ? '>Disponível' : ' class="text-danger"><b>Baixada</b>') . "</td>");
                                            echo ("</tr>");
                                            } ?>
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