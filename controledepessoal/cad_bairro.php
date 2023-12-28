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
    <?php render_painel_usu('GUARDA', $_SESSION['nivel_guarda']); ?>
    </div>
    <div class="page-body">
      <div class="left-sidebar">
        <?php include 'menu_opc.php'; ?>
      </div>
      <div class="content">
      <?php render_content_header('Bairros', 'fa fa-map-marker-alt'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-6">
            <div class="panel"><?php render_cabecalho_painel('CADASTRAR NOVO BAIRRO:', 'fa fa-plus', true); ?>
              <div class="panel-content">
                <div class="row">
                  <div class="col-md-12">
                    <form id="inline-validation" action="conf_bairro.php" method="post">
                      <?php render_custom_input('Nome do Bairro:', 'bairro', 'bairro', '', 30, 'Novo Bairro', true, False); ?>
                      <?php render_setores_de_bairros_select('setor', true); ?>
                      <div class="form-group">
                        <hr>
                        <button type="submit" class="btn btn-primary">CADASTRAR</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <div class="panel"><?php render_cabecalho_painel('EDITAR BAIRRO:', 'fa fa-edit', true); ?>
              <div class="panel-content">
                <form id="inline-validation" action="cad_bairro_edit.php" method="post">
                  <div class="row">
                    <div class="col-md-12">
                      <?php render_bairros_select('idBairro', true); ?>
                      <div class="form-group">
                        <hr>
                        <button name="btn_edit_bairro" type="submit" class="btn btn-primary" value="Editar Bairro">EDITAR</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="panel"><?php render_cabecalho_painel('TABELA DE BAIRROS:', 'fa fa-map-marker-alt', true); ?>
              <div class="panel-content">
                <table id="responsive-table" class="data-table table table-striped table-hover responsive nowrap" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Bairro</th>
                      <th>Setor</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $consulta2 = listar_bairros();
                    foreach ($consulta2 as $reg2) {
                      echo ("<tr>");
                      echo ("<td>" . $reg2['id'] . "</td>");
                      echo ("<td>" . $reg2['bairro'] . "</td>");
                      $regs = listar_setores_de_bairros();
                      foreach ($regs as $linha) {
                        if ($linha['id'] == $reg2['setor']) { echo ("<td>" . $linha['setor'] . "</td>"); }
                      }
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