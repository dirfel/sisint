<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if ($_SESSION['nivel_helpdesk'] == "Sem Acesso") {
    header('Location: ../sistemas/index.php');
    exit();
  }

$p1 = conectar("membros");
$p2 = conectar("helpdesk");


$consMeuChamadoAberto = $p2->prepare("SELECT id FROM chamado WHERE idsolicitante = :idmembro AND (situacao = '1' OR situacao = '2')");
$consMeuChamadoAberto->bindParam(':idmembro', $idmembro, PDO::PARAM_INT);
$consMeuChamadoAberto->execute();
$meuChamadoAberto = $consMeuChamadoAberto->fetchAll(PDO::FETCH_ASSOC);
$totalMeuChamadoAberto = count($meuChamadoAberto);

$consMeuChamadoFinalizado = $p2->prepare("SELECT id FROM chamado WHERE idsolicitante = :idmembro AND situacao = '3'");
$consMeuChamadoFinalizado->bindParam(':idmembro', $idmembro, PDO::PARAM_INT);
$consMeuChamadoFinalizado->execute();
$meuChamadoFinalizado = $consMeuChamadoFinalizado->fetchAll(PDO::FETCH_ASSOC);
$totalMeuChamadoFinalizado = count($meuChamadoFinalizado);

$consTotalChamadoFinalizado = $p2->prepare("SELECT id FROM chamado WHERE situacao = '3'");
$consTotalChamadoFinalizado->execute();
$totalChamadoFinalizado = $consTotalChamadoFinalizado->fetchAll(PDO::FETCH_ASSOC);
$totalTotalChamadoFinalizado = count($totalChamadoFinalizado);

$consTotalChamadoAbertos = $p2->prepare("SELECT id FROM chamado WHERE (situacao = '1' OR situacao = '2')");
$consTotalChamadoAbertos->execute();
$totalChamadoAbertos = $consTotalChamadoAbertos->fetchAll(PDO::FETCH_ASSOC);
$totalTotalChamadoAbertos = count($totalChamadoAbertos);

$color = ($totalTotalChamadoAbertos > 0) ? "danger" : "success";

?>
<!doctype html>
<html lang="pt-BR" class="fixed">

<head>
  <?php include '../recursos/views/cabecalho.php'; ?>
</head>

<body>
  <div class="wrap">
    <div class="page-header">
    <?php render_painel_usu('HELPDESK', $_SESSION['nivel_helpdesk']); ?>
    </div>
    <div class="page-body">
      <div class="left-sidebar">
        <?php include 'menu_opc.php'; ?>
      </div>
      <div class="content">
      <?php render_content_header('Informações do Sistema e do Usuário', 'fa fa-home'); ?>
        <div class="row">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-12">
            
            <div class="col-sm-12 col-lg-4 animated fadeInRightBig">
              <?php if ($_SESSION['nivel_helpdesk'] == "Supervisor" or $_SESSION['nivel_helpdesk'] == "Administrador") { ?>
                <div class="panel widgetbox wbox-2 bg-scale-0 b-md b-<?php echo $color ?>">
                  <div class="panel-content">
                    <a href="<?php echo ("adm_cham.php"); ?>" data-toggle="tooltip" data-placement="top" title="Administrar Chamados">
                      <div class="row">
                        <div class="col-xs-2">
                          <span class="icon fab fa-galactic-republic"></span>
                        </div>
                        <div class="col-xs-10">
                          <h4 class="subtitle color-<?php echo $color ?>">Chamados ABERTOS:
                            <b><?php echo $totalTotalChamadoAbertos; ?></b></h4>
                          <h4 class="subtitle" style="padding-top:4px;">Chamados FINALIZADOS:
                            <b><?php echo $totalTotalChamadoFinalizado; ?></b></h4>
                        </div>
                      </div>
                    </a>
                  </div>
                </div>
              <?php } else { ?>
                <div class="panel widgetbox wbox-2 bg-scale-0">
                  <div class="panel-content">
                    <div class="row">
                      <div class="col-xs-2">
                        <span class="icon fab fa-galactic-republic color-darker-1"></span>
                      </div>
                      <div class="col-xs-10">
                        <h4 class="subtitle color-darker-1">Meus Chamados ABERTOS: <b><?php echo $totalMeuChamadoAberto; ?></b></h4>
                        <h4 class="subtitle color-darker-1" style="padding-top:4px;">Meus Chamados FINALIZADOS: <b><?php echo $totalMeuChamadoFinalizado; ?></b></h4>
                        <h4 class="subtitle color-darker-1" style="padding-top:4px;">Total de Chamados FINALIZADOS: <b><?php echo $totalTotalChamadoFinalizado; ?></b></h4>
                      </div>
                    </div>
                  </div>
                </div>
              <?php } ?>
            </div>
            </div>
          </div>
          <?php if ($_SESSION['nivel_helpdesk'] == "Administrador") {
            include "index_userSupAdm.php";
          } ?>
        </div>
      </div>
        <?php include '../recursos/views/scroll_to_top.php'; ?>
    </div>
  </div>
  <?php include '../recursos/views/footer.php'; ?>
</body>

</html>   