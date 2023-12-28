<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if ($_SESSION['auth_data']['contarancho'] < "3") {
  header('Location: index.php');
  exit();
}

$p1 = conectar("membros");
$p2 = conectar("arranchamento");

$sistema = base64_encode('SISTEMA DE ARRANCHAMENTO');
$idmembro = $_SESSION['auth_data']['id'];
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
      <?php render_content_header('Arranchamento Individual', 'fa fa-user-tag'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-6">
            <div class="panel"><?php render_cabecalho_painel('ARRANCHAR USUARIO INDIVIDUAL:', 'fas fa-user-tag', true); ?>
              <div class="panel-content">
                <form action="panelindiv.php" method="post">
                  <div class="row">
                    <div class="col-md-12"><?php render_militar_ativo_select('tkusr', '', true, true) ?></div>
                    <div class="col-md-12"><hr><button type="submit" name="action" class="btn btn-warning">ARRANCHAR</button></div>
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
  <?php include '../recursos/views/footer.php'; ?>
</body>

</html>