<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if ($_SESSION['nivel_guarda'] == "Sem Acesso") {
    $msgerro = base64_encode('Usuário não possui acesso! Somente usuário: Anotador Aloj!');
    header('Location: ../sistemas/index.php?token=' . $msgerro);
    exit();
}
$p1 = conectar("membros");
$p2 = conectar("guarda");

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
    <?php render_painel_usu('GUARDA', $_SESSION['nivel_guarda']); ?>
    </div>
    <div class="page-body">
      <div class="left-sidebar">
        <?php include 'menu_opc.php'; ?>
      </div>
      <div class="content">
      <?php render_content_header('Informações do Serviço', 'fa fa-home'); ?>
        <div class="row animated fadeInRightBig">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-12">
            <div class="row">
              <div class="col-sm-12 col-lg-4">
                <?php render_card1('cadastros_visitantes.php?token3=TmVzdGEgcMOhZ2luYSDDqSBwb3Nzw612ZWwgdmVyaWZpY2FyIG9zIGNhZGFzdHJvcyByZWFsaXphZG9zLCBlZGl0YS1sb3MgZSBleGNsdcOtLWxvcyBzZSBmb3IgbyBjYXNvIQ==', 
                'Cadastros de Visitantes e Veículos', 'icon fa fa-database', 
                '<h4 class="subtitle">Visitantes Cadastrados: <b>'.$totalvisitante.'</b></h4>
                <h4 class="subtitle" style="padding-top:4px;">Veículos Cadastrados: <b>'.$totalveiculo.'</b></h4>
                <h4 class="subtitle" style="padding-top:4px;">Viaturas Cadastradas: <b>'.$totalviatura.'</b></h4>'); ?>
                
                <?php render_card2(
                '', '', 'icon fas fa-gas-pump', 
                '<h4 class="subtitle"><b>Consumo Diário de Combustível</b></h4>
                <h4 class="subtitle" style="padding-top:4px;">Diesel: <b>'. number_format($consumo_total_d ?? 0, 2, ',', '.').'L D</b></h4>
                <h4 class="subtitle" style="padding-top:4px;">Gasolina: <b>'. number_format($consumo_total_g ?? 0, 2, ',', '.').'L G</b></h4>', 
                '', ''); ?>
              </div>
              <div class="col-sm-12 col-lg-4">
                <?php render_card1('lancamentos_mil_durante.php', 'Lançamentos: Entrada e Saída de Militares Durante o Expediente', 'icon fa fa-suitcase', 
                '<h4 class="subtitle"><b>Durante o Expediente</b></h4>
                <h4 class="subtitle" style="padding-top:4px;">Entrada de Militares: <b>'. $mil_durante_entrada_count.'</b></h4>
                <h4 class="subtitle" style="padding-top:4px;">Saída de Militares: <b>'. $mil_durante_saida_count.'</b></h4>'); ?>
                <?php render_card1('lancamentos_mil_apos.php', 'Lançamentos: Entrada e Saída de Militares Após o Expediente', 'icon fas fa-umbrella-beach', 
                '<h4 class="subtitle"><b>Após o Expediente</b></h4>
                <h4 class="subtitle" style="padding-top:4px;">Entrada de Militares: <b>'. $mil_apos_entrada_count .'</b></h4>
                <h4 class="subtitle" style="padding-top:4px;">Saída de Militares: <b>'. $mil_apos_saida_count .'</b></h4>'); ?>
                
                <?php render_card1('lancamentos_aloj.php', 'Lançamentos: Entrada e Saída no Alojamento de Cabo e Soldado', 'icon fas fa-door-open', 
                '<h4 class="subtitle"><b>Alojamento de Cb/Sd</b></h4>
                <h4 class="subtitle" style="padding-top:4px;">Entrada de Militares: <b>'. $mil_aloj_entrada_count .'</b></h4>
                <h4 class="subtitle" style="padding-top:4px;">Saída de Militares: <b>'. $mil_aloj_saida_count .'</b></h4>'); ?>
                
                <?php render_card2(
                '', '', 'icon fas fa-road', 
                '<h4 class="subtitle"><b>Distância Percorrida Diária</b></h4>
                <h4 class="subtitle" style="padding-top:4px;">Viaturas Militares à Diesel: <b>'. number_format($distancia_total_d ?? 0, 0, ',', '.').' Km</b></h4>
                <h4 class="subtitle" style="padding-top:4px;">Viaturas Militares à Gasolina: <b>'. number_format($distancia_total_g ?? 0, 0, ',', '.').' Km</b></h4>', '', ''); ?>
              </div>
              <div class="col-sm-12 col-lg-4">
                <?php render_card1('lancamentos_visitantes.php', 'Relação e Lançamentos: Entrada e Saída de Visitantes e Viaturas', 'icon fa fa-street-view', 
                '<h4 class="subtitle">Visitantes <b>DENTRO DA OM: '. $situacaovisitante.'</b></h4>
                <h4 class="subtitle" style="padding-top:4px;">Entrada de Visitantes: <b>'. $visit_entrada_count.'</b></h4>
                <h4 class="subtitle" style="padding-top:4px;">Saída de Visitantes: <b>'. $visit_saida_count.'</b></h4>'); ?>
                
                <?php render_card1('lancamentos_veiculos.php', 'Relação e Lançamentos: Entrada e Saída de Visitantes e Viaturas', 'icon fa fa-car', 
                '<h4 class="subtitle">Veiculos <b>DENTRO DA OM: '. $situacaoveiculo .'</b></h4>
                <h4 class="subtitle" style="padding-top:4px;">Entrada de Veículos: <b>'. $visit_entrada_veic_count.'</b></h4>
                <h4 class="subtitle" style="padding-top:4px;">Saída de Veículos: <b>'. $visit_saida_veic_count.'</b></h4>'); ?>
                
                <?php render_card1('lancamentos_viaturas.php', 'Relação e Lançamentos: Entrada e Saída de Viaturas Militares', 'icon fas fa-bus-alt', 
                '<h4 class="subtitle">Viaturas Militares <b>FORA DA OM: '. $situacaoviatura .'</b></h4>
                <h4 class="subtitle" style="padding-top:4px;">Saída de Viaturas Militares: <b>'. $vtr_saida_count.'</b></h4>
                <h4 class="subtitle" style="padding-top:4px;">Entrada de Viaturas Militares: <b>'. $vtr_entrada_count.'</b></h4>'); ?>
                
                <?php render_card2('pronto_ht_conf.php', 'Clique para gerar pronto do HT e da Conformidade de Registro e Gestão', 'icon fas fa-paper-plane', 
                '<h4 class="title">Prontos</b></h4><h4 class="subtitle" style="padding-top:4px;">Conformidade e Hotel de Trânsito</h4>', '_blank', ''); ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php include '../recursos/views/scroll_to_top.php'; ?>
    </div>
  </div>
  <?php include '../recursos/views/footer.php'; ?>
  <script type="text/javascript">
    $(".tabela").DataTable({
      order: [
        [0, "desc"]
      ],
      scrollY: "240px",
      scrollCollapse: true,
      paging: false,
      searching: false
    });
  </script>
</body>

</html>