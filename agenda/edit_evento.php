<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
$p1 = conectar("membros");
$p2 = conectar("agenda");
if($_SESSION['nivel_fatos_observados'] == 'Sem Acesso') {
    header('Location: ../sistemas');
    exit();
}
if(!isset($_GET['id'])) {
    header('Location: /index.php');
    exit();
}

    $consulta = $p2->prepare("SELECT * FROM evento WHERE id = ".$_GET['id']);
    $consulta->execute();
    $consulta = $consulta->fetchAll(PDO::FETCH_ASSOC);
    $evento = $consulta[0];
    if(count($consulta) == 0 || $consulta[0]['autor'] != $_SESSION['auth_data']['id']) {
        die('Erro! Esse evento não existe ou você não é o autor dele.');
    }
?>
<!doctype html>
<html lang="pt-BR" class="fixed">
<head><?php include '../recursos/views/cabecalho.php'; ?></head>
<body>
  <div class="wrap">
    <div class="page-header"><?php render_painel_usu('AGENDA', $_SESSION['nivel_fatos_observados']); ?></div>
    <div class="page-body">
      <div class="left-sidebar"><?php include 'menu_opc.php'; ?></div>
      <div class="content">
        <?php render_content_header('Editar Evento', 'fa fa-edit'); ?>
        <div class="row">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-12">
            <div class="col-sm-12 col-lg-6 animated fadeInRightBig"><?php render_formulario_agenda($evento) ?></div>
          </div>
            <div class="col-sm-12 col-lg-6 animated fadeInRightBig">
              <div class="panel"><?php render_cabecalho_painel('EDITAR ANEXO', 'fa fa-upload', true); ?>
                <div class="panel-content">
                  <div class="row">
                  <div class="col-md-12 mb-sm"><?= $evento['anexo'] == '' ? 'Não há anexo carregado para esse evento' : '<a href="'.$evento['anexo'].'" target="_blank">Visualizar anexo</a>' ?></div>
                  <div class="col-md-12 mb-sm"><?= $evento['anexo'] == '' ? '' : '<a href="set_event.php?acao=remover_anexo&id='.$_GET['id'].'">Excluir anexo</a>' ?></div>
                  <div class="col-md-12 mb-sm"><hr></div>
                  <div class="col-md-12 mb-sm">
                    <form class="form-group" method="post" action="set_event.php?id=<?=$_GET['id']?>&acao=trocaupload" enctype="multipart/form-data">
                      <?php render_file_upload_button('arquivo') ?>
                      <hr>
                      <button class="btn btn-primary">Trocar arquivo de anexo</button>
                    </form>
                  </div>
                </div>
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