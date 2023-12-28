<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if ($_SESSION['nivel_plano_chamada'] == "Sem Acesso") {
    $msgerro = base64_encode('Usuário não possui acesso!');
    header('Location: ../sistemas/index.php?token=' . $msgerro);
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
    <?php render_painel_usu('CONTROLE DE PESSOAL', $_SESSION['nivel_plano_chamada']); ?>
    </div>
    <div class="page-body">
      <div class="left-sidebar">
        <?php include 'menu_opc.php'; ?>
      </div>
      <div class="content">
      <?php render_content_header('Subunidades', 'fa fa-sitemap'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-6">
            <div class="panel"><?php render_cabecalho_painel('CADASTRAR NOVA SUBUNIDADE', 'fa fa-plus', true); ?>
              <div class="panel-content">
                <div class="row">
                  <div class="col-md-12">
                    <form id="inline-validation" action="conf_subunid.php" method="post">
                      <?php render_custom_input('Nome da Subunidade:', '', 'subunidade', '', 30, 'Nova Subunidade', true, false);?>
                      <div class="form-group">
                        <button type="submit" name="action" class="btn btn-primary">CADASTRAR</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="panel"><?php render_cabecalho_painel('TABELA DE SUBUNIDADES', 'fa fa-sitemap', true); ?>
              <div class="panel-content">
                <table id="responsive-table" class="data-table table table-striped table-hover responsive nowrap" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Subunidade</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $consulta = listar_subunidades();
                    foreach ($consulta as $reg) {
                      echo ("<tr>");
                      echo ("<td>" . $reg['id'] . "</td>");
                      echo ("<td>" . $reg['descricao'] . "</td>");
                      echo ("</tr>");
                    } ?>
                  </tbody>
                </table>
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