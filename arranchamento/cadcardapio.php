<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if ($_SESSION['auth_data']['contarancho'] < "3") {
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
      <?php render_content_header('Cardápio', 'fa fa-hamburger'); ?>
        <div class="row animated fadeInUp">
          <div class="col-sm-12 col-md-6">
            <div class="panel"><?php render_cabecalho_painel('LANÇAR CARDÁPIO', 'fas fa-hamburger', true); ?>
              <form class="form-horizontal" action="cadcardapio2.php" method="post">
                <div class="panel-content">
                  <p>Informe a data para cadastro de novo cardápio. Caso já exista o cadastro, o sistema apresentará os dados para consulta/alteração do mesmo. </p>
                  <div class="row">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10"><?php render_data_field('datacardapio', true, 'Data:', null) ?></div>
                    <div class="col-sm-12"><hr><button type="submit" class="btn btn-warning">LANÇAR</button></div>
                  </div>
                </div>
              </form>
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