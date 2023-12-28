<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if ($_SESSION['auth_data']['contahd'] < "2") {
    header('Location: index.php');
    exit();
  }

$pdo = conectar("membros");

?>
<!doctype html>
<html lang="pt-BR" class="fixed left-sidebar-collapsed">

<head>
  <?php include '../recursos/views/cabecalho.php'; ?>

</head>

<body>
  <div class="wrap">
    <div class="page-header">
    <?php render_painel_usu('CONTROLE DE PESSOAL', $_SESSION['nivel_plano_chamada']); ?>
    </div>
    <div class="page-body">
      <div class="left-sidebar">
        <?php include 'menu_opc.php'; ?>
      </div>
      <div class="content">
      <?php render_content_header('Cadastrar Novo Usuário no Sistema', 'fa fa-user-plus'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <form id="inline-validation" action="conf_usu.php" method="post">
            <div class="col-sm-12 col-md-12">
              <div class="panel"><?php render_cabecalho_painel('CADASTRAR NOVO USUÁRIO:', 'fa fa-user-plus', true); ?>
                <div class="panel-content">
                  <div class="row">
                  <?php render_painel_dados_usuario(); ?>
                    <div class="col-md-12"><hr>
                      <button type="submit" name="btn_novo_cadastro" value='Cadastrou novo usuário' class="btn btn-warning">CADASTRAR</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
      <?php include '../recursos/views/scroll_to_top.php'; ?>
    </div>
  </div>
  <?php include '../recursos/views/footer.php'; ?>
</body>
</html>