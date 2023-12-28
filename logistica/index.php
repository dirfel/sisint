<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
if($_SESSION['nivel_fatos_observados'] == 'Sem Acesso') { //TOOD: Verificar permissões
    header('Location: ../sistemas');
    exit();
}
?>
<!doctype html>
<html lang="pt-BR" class="fixed">
<head><?php include '../recursos/views/cabecalho.php'; ?></head>
<body>
  <div class="wrap">
    <div class="page-header"><?php render_painel_usu('LOGÍSTICA', $_SESSION['nivel_fatos_observados']); ?></div>
    <div class="page-body">
      <div class="left-sidebar"><?php include 'menu_opc.php'; ?></div>
      <div class="content">
      <?php render_content_header('Logística', 'fa fa-home'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-lg-6"> 
            
          </div>
            <div class="col-sm-12 col-md-6">
              
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