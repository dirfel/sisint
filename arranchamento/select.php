<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if ($_SESSION['auth_data']['contarancho'] < "2") {
  header('Location: index.php');
  exit();
}

?>
<!doctype html>
<html lang="pt-BR" class="fixed left-sidebar-collapsed">

<head>
  <?php include '../recursos/views/cabecalho.php'; ?>
</head>

<body>
  <div class="wrap">
    <div class="page-header">
    <?php render_painel_usu('ARRANCHAMENTO', $_SESSION['nivel_arranchamento']); ?>
    </div>
    <div class="page-body">
      <div class="left-sidebar">
        <?php include 'menu_opc.php'; ?>
      </div>
      <div class="content">
      <?php render_content_header('Arranchamento por Seleção', 'fa fa-group'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-4">
            <div class="row">
              <div class="panel"><?php render_cabecalho_painel('ARRANCHAR POR SELEÇÃO DE MILITARES:', 'fa fa-group', true); ?>
                <div class="panel-content">
                  <form class="form-horizontal form-stripe" action="select2.php" method="post">
                    <div class="row">
                      <div class="col-md-12"><?php render_data_field('datarancho', true, 'Data:', null); ?></div>
                      <div class="col-md-12"><hr><button type="submit" name="action" class="btn btn-warning">SELECIONAR MILITARES</button></div>
                    </div>
                  </form>
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