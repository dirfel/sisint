<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
if($_SESSION['nivel_fatos_observados'] == 'Sem Acesso') { //TOOD: Verificar permissões
    header('Location: ../sistemas');
    exit();
}
if(isset($_POST['btn_add_vtr'])){
    if($_POST['btn_add_vtr'] == 'Editar Viatura') {
        try{
            edit_param_viatura($_POST['viatura'], 'modelo', $_POST['modelo'], PDO::PARAM_STR);
            edit_param_viatura($_POST['viatura'], 'marca', $_POST['marca'], PDO::PARAM_STR);
            edit_param_viatura($_POST['viatura'], 'placa', $_POST['placa'], PDO::PARAM_STR);
            edit_param_viatura($_POST['viatura'], 'tipo', $_POST['tipo'], PDO::PARAM_STR);
            edit_param_viatura($_POST['viatura'], 'consumo', $_POST['consumo'], PDO::PARAM_INT);
            edit_param_viatura($_POST['viatura'], 'combustivel', $_POST['combustivel'], PDO::PARAM_STR);
            edit_param_viatura($_POST['viatura'], 'total_ocupantes', $_POST['total_ocupantes'], PDO::PARAM_INT);
            edit_param_viatura($_POST['viatura'], 'odometro', $_POST['odometro'], PDO::PARAM_INT);
            $token2 = 'Viatura atualizada com sucesso!';
        } catch (Error $e) {
            $token = 'Erro ao atualizar a Viatura!';
        }
    } else if($_POST['btn_add_vtr'] == 'Ressussitar Viatura') {
        edit_param_viatura($_POST['viatura'], 'baixada', 0, PDO::PARAM_INT);
    } else if($_POST['btn_add_vtr'] == 'Baixar Viatura') {
        edit_param_viatura($_POST['viatura'], 'baixada', 1, PDO::PARAM_INT);
    }
}

$consulta2 = listar_viaturas(); 
$viatura = get_viatura_by_id($_POST['viatura']); ?>

<!doctype html>
<html lang="pt-BR" class="fixed">
<head><?php include '../recursos/views/cabecalho.php'; ?></head>
<body>
  <div class="wrap">
    <div class="page-header"><?php render_painel_usu('LOGÍSTICA', $_SESSION['nivel_fatos_observados']); ?></div>
        <div class="page-body">
            <div class="left-sidebar"><?php include 'menu_opc.php'; ?></div>
                <div class="content">
                    <?php render_content_header('Editar Viaturas', 'fa fa-edit'); ?>
                    <div class="row animated fadeInUp">
                        <?php include '../recursos/views/token.php'; ?>
                        <div class="col-sm-12 col-md-6"><?php render_cadastro_viatura_form($viatura); ?></div>
                        <div class="col-sm-6">
                            <div class="panel"><?php render_cabecalho_painel('ALTERAR DISPONIBILIDADE:', 'fa fa-check', true); ?>
                                <div class="panel-content">
                                    <div class="col-12">
                                        <h4>A viatura está <?= $viatura['baixada'] ? '<b class="text-danger">BAIXADA</b>' : '<b class="text-success">DISPONÍVEL</b> ' ?>.</h4>
                                        <p>Viaturas baixadas não são exibidas aos usuários para solicitação de viatura.</p>
                                    </div>
                                    <div class="col-12">
                                        <form id="disponibilidade" action="#" method="post">
                                            <input type="hidden" name="viatura" value="<?= $viatura['id'] ?>">
                                            <button name="btn_add_vtr" type="submit" class="btn btn-primary" value="<?= ($viatura['baixada'] == 1 ? 'Ressussitar' : 'Baixar') ?> Viatura"><?= ($viatura['baixada'] == 1 ? 'DISPONIBILIZAR' : 'BAIXAR') ?></button>
                                        </form>
                                    </div>
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