<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
if ($_SESSION['nivel_plano_chamada'] == "Sem Acesso") {
    $msgerro = base64_encode('Usuário não possui acesso!');
    header('Location: ../sistemas/index.php?token=' . $msgerro);
    exit();
}
$p1 = conectar("membros");

include "indexInfo.php";

?>
<!doctype html>
<html lang="pt-BR" class="fixed">

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
      <?php render_content_header('Controle de Férias', 'fa fa-home'); ?>
        <div class="row animated zoomInDown">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-4">
          <div class="panel widgetbox wbox-2 bg-scale-0">
              <div class="panel-content color-primary">
                <a data-toggle="tooltip" data-placement="top" title="Relatório por Posto e Graduação">
                  <div class="row">
                    <div class="col-xs-2">
                      <span class="icon fa fa-list"></span>
                    </div>
                    <div class="col-xs-10">
                      <h4 class="title">Relatório P/G</h4>
                    </div>
                  </div>
                </a>
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-md-4">
          <div class="panel widgetbox wbox-2 bg-scale-0">
              <div class="panel-content color-primary">
                <a data-toggle="tooltip" href="ferias_cadastrar1.php" data-placement="top" title="Cadastrar ou Editar férias">
                  <div class="row">
                    <div class="col-xs-2">
                      <span class="icon fa fa-edit"></span>
                    </div>
                    <div class="col-xs-10">
                      <h4 class="title">Cadastro de Férias</h4>
                    </div>
                  </div>
                </a>
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-md-4">
          <div class="panel widgetbox wbox-2 bg-scale-0">
              <div class="panel-content color-primary">
                <a data-toggle="tooltip" data-placement="top" title="Plano de Férias">
                  <div class="row">
                    <div class="col-xs-2">
                      <span class="icon fa fa-car"></span>
                    </div>
                    <div class="col-xs-10">
                      <h4 class="title">Plano de Férias</h4>
                    </div>
                  </div>
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
        </div>
      </div>
      <?php include '../recursos/views/scroll_to_top.php'; ?>
    </div>
  </div>
  <?php include '../recursos/views/footer.php'; ?>
</body>

</html>