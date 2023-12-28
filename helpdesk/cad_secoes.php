<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

$pdo = conectar("helpdesk");
$pdo2 = conectar("membros");

if ($_SESSION['nivel_helpdesk'] != "Administrador") {
  header('Location: index.php');
  exit();
}
?>
<!doctype html>
<html lang="pt-BR" class="fixed left-sidebar-collapsed">

<head><?php include '../recursos/views/cabecalho.php'; ?></head>
<body>
  <div class="wrap">
    <div class="page-header"><?php render_painel_usu('HELPDESK', $_SESSION['nivel_helpdesk']); ?></div>
    <div class="page-body">
      <div class="left-sidebar">
        <?php include 'menu_opc.php'; ?>
      </div>
      <div class="content">
      <?php render_content_header('Seções de Trabalho', 'fa fa-boxes'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-6">
            <div class="panel"><?php render_cabecalho_painel('CADASTRAR NOVA SEÇÃO:', 'fa fa-plus', true); ?>
              <div class="panel-content">
                <div class="row">
                  <div class="col-md-12">
                    <form id="inline-validation" action="conf_secoes.php" method="post">
                      <div class="form-group">
                        <label for="inputMaxLength" class="control-label">Nome da Seção:</label>
                        <input type="text" class="form-control" id="inputMaxLength" name="secao" placeholder="Nova Seção" maxlength="30" required="Campo obrigatório">
                      </div>
                      <div class="form-group">
                        <hr>
                        <button type="submit" name="action" class="btn btn-primary">CADASTRAR</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="panel"><?php render_cabecalho_painel('fas fa-boxes', 'fas fa-boxes', true); ?>
              <div class="panel-content">
                <table id="responsive-table" class="data-table table table-striped table-hover responsive nowrap" cellspacing="0" width="100%">
                  <thead><tr><th>ID</th><th>Seções</th><th>Solicitações</th></tr></thead>
                  <tbody>
                    <?php $consulta = $pdo->prepare("SELECT * FROM secao");
                    $consulta->execute();
                    while ($reg = $consulta->fetch(PDO::FETCH_ASSOC)) :
                      echo ("<tr>");
                      echo ("<td>" . $reg['id'] . "</td>");
                      echo ("<td>" . $reg['secao'] . "</td>");
                      echo ("<td>" . $reg['qtdchamados'] . "</td>");
                      echo ("</tr>");
                    endwhile; ?>
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