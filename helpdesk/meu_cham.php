<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if ($_SESSION['nivel_helpdesk'] == "Sem Acesso") {
    header('Location: ../sistemas/index.php');
    exit();
  }

$pdo = conectar("helpdesk");
$pdo2 = conectar("membros");

?>
<!doctype html>
<html lang="pt-BR" class="fixed left-sidebar-collapsed">

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
      <?php render_content_header('Meus Chamados Abertos e Finalizados', 'fa fa-info-circle'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-12">
            <div class="panel"><?php render_cabecalho_painel('MEUS CHAMADOS ABERTOS:', 'fas fa-arrow-circle-up', true); ?>
              <div class="panel-content">
                <table id="responsive-table" class="data-table table table-striped table-hover responsive nowrap" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <td align='center' valign='middle'><font size=3><strong>ID</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Tipo de Serviço</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Assunto</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Seção</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Ocorrências</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Situação</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Técnico</strong></font></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $filtro = $pdo->prepare("SELECT * FROM chamado WHERE idsolicitante = :idsolicitante AND (situacao = '1' OR situacao = '2')");
                    $filtro->bindParam(":idsolicitante", $_SESSION['auth_data']['id'], PDO::PARAM_INT);
                    $filtro->execute();
                    while ($reg = $filtro->fetch(PDO::FETCH_ASSOC)) {
                      $criptolink = base64_encode($reg['numchamado']);
                      if ($reg['situacao'] == "1") {
                        $situacao = "Em espera";
                      } else if ($reg['situacao'] == "2") {
                        $situacao = "Em atendimento";
                      } else {
                        $situacao = "Finalizado";
                      }
                      $filtro1 = $pdo->prepare("SELECT * FROM historico WHERE numchamado = :numchamado");
                      $filtro1->bindParam(":numchamado", $reg['numchamado']);
                      $filtro1->execute();
                      $ocorrencias = $filtro1->fetchAll(PDO::FETCH_ASSOC);

                      $filtro2 = $pdo->prepare("SELECT * FROM servico WHERE id = :idservico");
                      $filtro2->bindParam(":idservico", $reg['idservico']);
                      $filtro2->execute();
                      $idsv = $filtro2->fetchAll(PDO::FETCH_ASSOC);
                      $descsv = $idsv[0];

                      $filtro3 = $pdo->prepare("SELECT * FROM secao WHERE id = :idsecao");
                      $filtro3->bindParam(":idsecao", $reg['idsecao']);
                      $filtro3->execute();
                      $idsecao = $filtro3->fetchAll(PDO::FETCH_ASSOC);
                      $descsecao = $idsecao[0];

                      $filtro4 = $pdo2->prepare("SELECT * FROM usuarios WHERE id = :idtecnico");
                      $filtro4->bindParam(":idtecnico", $reg['tecnico']);
                      $filtro4->execute();
                      $idtecnico = $filtro4->fetchAll(PDO::FETCH_ASSOC);
                      $tecnico = $idtecnico[0];

                      $descpgrad = getPGrad($tecnico['idpgrad']);
                    ?>
                      <tr>
                        <td align='center' style="font-weight: bold;" valign='middle'><a href="chamado.php?out=<?php echo $criptolink; ?>">
                            <i class="fa fa-mail-reply-all" aria-hidden="true"></i>
                            <?php echo $reg['numchamado']; ?></a></td>
                        <td align='center' valign='middle'><?php echo $descsv['servico']; ?></td>
                        <td align='center' valign='middle'><?php echo $reg['assunto']; ?></td>
                        <td align='center' valign='middle'><?php echo $descsecao['secao']; ?></td>
                        <td align='center' valign='middle'><?php echo count($ocorrencias); ?></td>
                        <td align='center' valign='middle'><?php echo $situacao; ?></td>
                        <td align='center' valign='middle'><?php
                          if ($reg['tecnico'] <> 0) { echo ($descpgrad . " " . $tecnico['nomeguerra']);
                          } else { echo "---"; }
                          ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-md-12">
            <div class="panel"><?php render_cabecalho_painel('MEUS CHAMADOS FINALIZADOS:', 'fas fa-circle-check', true); ?>
              <div class="panel-content">
                <table id="responsive-table" class="data-table table table-striped table-hover responsive nowrap" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <td align='center' valign='middle'><font size=3><strong>ID</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Tipo de Serviço</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Assunto</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Seção</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Ocorrências</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Situação</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Técnico</strong></font></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $filtro = $pdo->prepare("SELECT * FROM chamado WHERE idsolicitante = :idsolicitante AND situacao = '3'");
                    $filtro->bindParam(":idsolicitante", $_SESSION['auth_data']['id'], PDO::PARAM_INT);
                    $filtro->execute();
                    while ($reg = $filtro->fetch(PDO::FETCH_ASSOC)) {
                      $criptolink = base64_encode($reg['numchamado']);
                      if ($reg['situacao'] == "1") {
                        $situacao = "Em espera";
                      } else if ($reg['situacao'] == "2") {
                        $situacao = "Em atendimento";
                      } else {
                        $situacao = "Finalizado";
                      }
                      $filtro1 = $pdo->prepare("SELECT * FROM historico WHERE numchamado = :numchamado");
                      $filtro1->bindParam(":numchamado", $reg['numchamado']);
                      $filtro1->execute();
                      $ocorrencias = $filtro1->fetchAll(PDO::FETCH_ASSOC);

                      $filtro2 = $pdo->prepare("SELECT * FROM servico WHERE id = :idservico");
                      $filtro2->bindParam(":idservico", $reg['idservico']);
                      $filtro2->execute();
                      $idsv = $filtro2->fetchAll(PDO::FETCH_ASSOC);
                      $descsv = $idsv[0];

                      $filtro3 = $pdo->prepare("SELECT * FROM secao WHERE id = :idsecao");
                      $filtro3->bindParam(":idsecao", $reg['idsecao']);
                      $filtro3->execute();
                      $idsecao = $filtro3->fetchAll(PDO::FETCH_ASSOC);
                      $descsecao = $idsecao[0];

                      $filtro4 = $pdo2->prepare("SELECT * FROM usuarios WHERE id = :idtecnico");
                      $filtro4->bindParam(":idtecnico", $reg['tecnico']);
                      $filtro4->execute();
                      $idtecnico = $filtro4->fetchAll(PDO::FETCH_ASSOC);
                      $tecnico = $idtecnico[0];

                      $descpgrad = getPGrad($tecnico['idpgrad']);
                    ?>
                      <tr>
                        <td align='center' style="font-weight: bold;" valign='middle'>
                        <a href="chamado.php?out=<?= $criptolink; ?>">
                            <i class="fa fa-mail-reply-all" aria-hidden="true"></i>
                            <?= $reg['numchamado']; ?></a></td>
                        <td align='center' valign='middle'><?= $descsv['servico']; ?></td>
                        <td align='center' valign='middle'><?= $reg['assunto']; ?></td>
                        <td align='center' valign='middle'><?= $descsecao['secao']; ?></td>
                        <td align='center' valign='middle'><?= count($ocorrencias); ?></td>
                        <td align='center' valign='middle'><?= $situacao; ?></td>
                        <td align='center' valign='middle'><?= (getPGrad($tecnico['idpgrad']) . " " . $tecnico['nomeguerra']); ?></td>
                      </tr>
                    <?php } ?>
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