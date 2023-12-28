<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if (!($_SESSION['nivel_guarda'] == "Oficial e Sargento" || $_SESSION['nivel_guarda'] == "Supervisor" || $_SESSION['nivel_guarda'] == "Administrador")) {
  $msgerro = base64_encode('Usuário não possui acesso! Somente usuário: Oficial e Sargento!');
  header('Location: index.php?token=' . $msgerro);
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
      <?php render_content_header('Livro de Partes do Oficial de Dia', 'fa fa-book'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-6">
            <form id="validation" action="livroPartesEditar2.php" method="post">
              <div class="panel"><?php render_cabecalho_painel('LANÇAR / EDITAR LIVRO DE PARTES:', 'fas fa-book', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-sm-12 form-group">
                      <label style="font-size: 15px;" for="form-group" class="control-label"><i class="fas fa-info-circle color-info" aria-hidden="true"></i> Após o LANÇAMENTO do Livro de Partes o mesmo só poderá ser editado pelo militar que fez o lançamento.</label>
                      <label style="font-size: 15px;" for="form-group" class="control-label"><i class="fas fa-info-circle color-info" aria-hidden="true"></i> Após o FINALIZAR do Livro de Partes o mesmo não poderá ser editado ou excluído.</label>
                      <label style="font-size: 15px;" for="form-group" class="control-label"><i class="fas fa-info-circle color-danger" aria-hidden="true"></i> O Livro de Partes só está disponível para a impressão após ser FINALIZADO.</label>
                    </div>
                    <div class="col-md-6 mb-sm"><?php render_data_field('dataLivro', true, 'Data assumiu o Serviço:', 'ontem') ?></div>
                    <div class="col-md-12">
                      <hr>
                      <button type="submit" name="action" value='Larçar no Roteiro dos Postos' class="btn btn-primary" style="width: 140px;">LANÇAR / EDITAR</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
        <?php include '../recursos/views/scroll_to_top.php'; ?>
    </div>
  </div>
  <?php include '../recursos/views/footer.php'; ?>
</body>

</html>