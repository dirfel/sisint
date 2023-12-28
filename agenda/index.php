<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
$p1 = conectar("membros");
if($_SESSION['nivel_fatos_observados'] == 'Sem Acesso') {
    header('Location: ../sistemas');
} ?>
<!doctype html>
<html lang="pt-BR" class="fixed">

<head><?php include '../recursos/views/cabecalho.php'; ?>
<style>
  a.btn {
  padding: 2px 4px 2px 4px;
}
</style>
</head>
<body>
  <div class="wrap">
    <div class="page-header"><?php render_painel_usu('AGENDA', $_SESSION['nivel_fatos_observados']); ?></div>
    <div class="page-body">
      <div class="left-sidebar"><?php include 'menu_opc.php'; ?></div>
      <div class="content">
      <?php render_content_header('Painel Inicial da Agenda', 'fa fa-home'); ?>
        <div class="row">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-6 animated fadeInRightBig"><?php render_agenda_listagem('AGENDA PÚBLICA', 'fa fa-group', 'agenda-bateria'); ?>
            <?php render_agenda_listagem('EVENTOS COMPARTIMENTADOS', 'fa fa-share', 'agenda-compartilhamento'); ?>
            <?php render_agenda_listagem('AGENDA PRIVADA', 'fa fa-user-secret', 'agenda-eu'); ?></div>
            <div class="col-sm-12 col-md-6 animated fadeInRightBig">
              <div class="panel"><?php render_cabecalho_painel('CALENDÁRIO DE ATIVIDADES  | <a onclick="reduzirMes(); loadCalendar()"><i class="fa fa-arrow-left"></i></a><span id="mesatual"></span><a onclick="aumentarMes(); loadCalendar()"><i class="fa fa-arrow-right"></i></a>', 'far fa-calendar', false); ?>
                <div class="">
                  <div class="row">
                    <div class="col-md-12 mb-sm">
                      <div class="form-group">
                        <table class="table text-center calendario my-0">
                            <tr class=" py-0 my-0"><th>Dom</th><th>Seg</th><th>Ter</th><th>Qua</th><th>Qui</th><th>Sex</th><th>Sab</th></tr>
                            <?php $f = array('a', 'b', 'c', 'd', 'e', 'f');
                            foreach($f as $l) {
                              for($i = 1; $i<=7; $i++) {
                                if($i == 1) { echo '<tr>'; }
                                echo '<td><a class="btn btn-primary" data-toggle="tooltip" title="" id="'.$l.$i.'" onclick="setFocusDay(this)" href="#fim"></a></td>';
                                if($i == 7) { echo '</tr>'; }
                              }
                            } ?>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php render_formulario_agenda(); ?>
            </div>
          </div>
        </div>

          </div>
        </div>
      </div>
      <?php include '../recursos/views/scroll_to_top.php'; ?>
    </div>
  </div>
  <?php include '../recursos/views/footer.php'; ?>
  <script src="../recursos/vendor/javascripts/agenda-scripts.js"></script>
</body>
</html>