<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

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
      <?php render_content_header('Controle de Efetivo e Plano de Chamada', 'fa fa-home'); ?>
        <div class="row animated zoomInDown">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-4">
            <?php render_card2(
              '', 'Efetivo', 'icon fa fa-users', 
              '<h4 class="title">MILITARES DA ATIVA<b></b></h4>
              <h4 class="subtitle">Oficiais Superiores: <b>'. $of_sup_count .'</b></h4>
              <h4 class="subtitle">Oficiais Intermediários: <b>'. $of_int_count .'</b></h4>
              <h4 class="subtitle">Oficiais Subalternos: <b>'. $of_sub_count .'</b></h4>
              <h4 class="subtitle">Subtenentes: <b>'. $st_count .'</b></h4>
              <h4 class="subtitle">1º e 2º Sargentos: <b>'. $sgt_1e2_count .'</b></h4>
              <h4 class="subtitle">3º Sargentos: <b>'. $sgt_3_count .'</b></h4>
              <h4 class="subtitle">Cabos: <b>'. $cb_count .'</b></h4>
              <h4 class="subtitle">Soldados EP: <b>'. $sd_count .'</b></h4>
              <h4 class="subtitle">Soldados EV: <b>'. $ev_count .'</b></h4>
              <br>
              <h4 class="subtitle">Efetivo total: <b>'. $total_count .'</b></h4>
              <h4 class="subtitle" style="padding-top:4px;">Oficiais: <b>'. $of .'</b></h4>
              <h4 class="subtitle" style="padding-top:4px;">Praças: <b>'. $grad .'</b></h4>',
              '', ''); ?>
          </div>
          <div class="col-sm-12 col-md-4">
            <?php render_card1('livro_afastamento.php', 'Preencha o livro de afastamento digital antes de viajar', 'icon fa fa-car',
              '<h4 class="title">Livro de Afastamento<b></b></h4><h4 class="subtitle">Acesse o Livro de Afastamento Digital aqui.</h4>'); ?>
          </div>
          <div class="col-sm-12 col-md-4">
          <?php render_card1('../controledepessoal/cad_usu_indiv.php?tkusr='.base64_encode($_SESSION['auth_data']['id']), 'Clique aqui para atualizar seus dados', 'icon fas fa-user', 
                '<h4 class="subtitle"><b>Mantenha seus dados atualizados</b></h4>
                <h4 class="subtitle"><b>Última atualização: </b>'. (date_converter2($_SESSION['auth_data']['ult_atlz_dados']) ?? 'Sem registro') .'</h4>
                <h1 class="title" style="font-size: 19px;">Atualizar dados</h1>'); ?>
          <?php if($_SESSION['auth_data']['idpgrad'] != 16) {          
            render_card1('../controledepessoal/cad_usu_indiv_dep.php?tkusr='.base64_encode($_SESSION['auth_data']['id']), 'Clique aqui para atualizar seus dependentes', 'icon fas fa-users', 
                '<h4 class="subtitle"><b>[BETA] Mantenha o cadastro de seus dependentes atualizados</b></h4>
                <h1 class="title" style="font-size: 19px;">Atualizar Dependentes</h1>'); } ?>
          <?php if($_SESSION['nivel_plano_chamada'] == "Supervisor" || $_SESSION['nivel_plano_chamada'] == "Administrador") { ?>
            <div class="panel widgetbox wbox-2 bg-scale-0">
            <div class="panel-content">
                    <div class="row">
                        <!-- <div class="col-xs-2"><span class="icon fas fa-house"></span></div> -->
                        <div class="col-xs-10">
                          <form action="cad_usu_ind_dep.php" method="GET">
                              <?php $titulares_fusex = titulares_fusex();
                              $tits = array();
                              foreach($titulares_fusex as $tit) {
                                $tits[$tit['id']] = ImprimeConsultaMilitar2($tit);
                              }
                              render_default_select('tkusr', 'select2-example-basic', true, 'Selecione o titular', 'Selecione o titular', 'icon fas fa-house', $tits, '');         
                            ?>
                            <button class="btn btn-primary">Acessar</button>
                          </form>
                        </div>
                    </div>
                </div>
            </div>
           <?php } ?>
          </div>
        </div> 
      </div>
      <?php include '../recursos/views/scroll_to_top.php'; ?>
    </div>
  </div>
  <?php include '../recursos/views/footer.php'; ?>
</body>
</html>