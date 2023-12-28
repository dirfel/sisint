<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
if ($_SESSION['nivel_helpdesk'] != "Administrador") {
    header('Location: index.php');
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
      <?php render_content_header('Desbloquear Livro de Partes do Oficial-de-Dia', 'fa fa-rank'); ?>
        <div class="row">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-12">
            <div class="col-sm-12 col-lg-4 animated fadeInRightBig">
              <div class="panel widgetbox wbox-2 bg-scale-0">
                <div class="panel-content">
                  <a data-placement="top" title="">
                    <div class="row">
                      <div class="col-xs-2">
                        <span class="icon fa fa-unlock"></span>
                      </div>
                      <div class="col-xs-10">
                          <form class="form-group" action="desbloq_livro.php" method="post">
                          <?php render_data_field('dataLivro', true, 'Data assumiu o Serviço:', 'ontem'); ?>
                            
                          <input type="submit" class="btn btn-primary" value="Desbloquear">
                          </form>
                      </div>
                    </div>
                  </a>
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
</body>

</html>   