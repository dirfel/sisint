<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if ($_SESSION['auth_data']['contarancho'] < "2") {
  header('Location: index.php');
  exit();
}

$pdo = conectar("membros");
$pdo2 = conectar("arranchamento");
$datarancho = filter_input(INPUT_POST, "datarancho");
$meuidsu = $_SESSION['auth_data']['idsubunidade'];
$convdata = strtotime(date_converter($datarancho));

$ds = date('D'); // pega o dia da semana da data atual

$dtlimiteA = date('Y-m-d', strtotime("+2 days"));
$dtlimite = strtotime($dtlimiteA);
$dtcincoA = date('Y-m-d', strtotime("+5 days"));
$dtcinco = strtotime($dtcincoA);
$dtquatroA = date('Y-m-d', strtotime("+4 days"));
$dtquatro = strtotime($dtquatroA);
$dttresA = date('Y-m-d', strtotime("+3 days"));
$dttres = strtotime($dttresA);
$erro = 0;

if ($convdata < $dtlimite) {
  $msgerro = base64_encode("ARRANCHAMENTO LIBERADO SOMENTE A PARTIR DE " . date('d/m/Y', $dtlimite));
  $erro = 1;
}

if ($ds == 'Fri') { //verifica se o dia da semana � sexta-feira
  if ($convdata < $dttres) {
    $msgerro = base64_encode("ARRANCHAMENTO LIBERADO SOMENTE A PARTIR DE " . date('d/m/Y', $dtquatro));
    $erro = 1;
  }
}

if ($ds == 'Sat') { //verifica se o dia da semana � s�bado
  if ($convdata < $dttres) {
    $msgerro = base64_encode("ARRANCHAMENTO LIBERADO SOMENTE A PARTIR DE " . date('d/m/Y', $dttres));
    $erro = 1;
  }
}
if ($erro < 1) { ?>
  <!doctype html>
  <html lang="pt-BR" class="fixed left-sidebar-collapsed">

  <head>
    <?php include '../recursos/views/cabecalho.php'; ?>
  </head>

  <body>
    <div class="wrap">
      <div class="page-header">
      <?php render_painel_usu('ARRANCHAMENTO', $_SESSION['nivel_arranchamento']); ?>
      </div>
      <div class="page-body">
        <div class="left-sidebar">
          <?php include 'menu_opc.php'; ?>
        </div>
        <div class="content">
        <?php render_content_header('Arranchamento por Seleção em '. $datarancho, 'fa fa-group'); ?>
          <div class="row animated fadeInUp">
            <div class="col-sm-12">
              <div class="row">
                <div class="col-sm-12">
                  <?php
                    if ($_SESSION['auth_data']['contarancho'] == "2") { // CONTA DO FURRIEL
                      $consultausu = $pdo->prepare("SELECT * FROM usuarios WHERE idsubunidade = :idsubunidade AND userativo = 'S' ORDER BY idpgrad, nomeguerra ASC");
                      $consultausu->bindParam(":idsubunidade", $meuidsu, PDO::PARAM_STR);
                    } else {
                      $consultausu = $pdo->prepare("SELECT * FROM usuarios WHERE userativo = 'S' ORDER BY idpgrad, nomeguerra ASC");
                    }
                    $consultausu->execute();
                    $qtdusers = $consultausu->fetchAll(PDO::FETCH_ASSOC);
                    $qtd_users = count($qtdusers);
                  ?>
                    <div class="panel"><?php render_cabecalho_painel('ARRANCHAR POR SELEÇÃO: '. $qtd_users.' MILITARES EM '.$datarancho, 'fas fa-utensils', true); ?>
                    <div class="panel-content">
                      <div class="table-responsive">
                        <form action="gravselect.php<?php echo ("?qtduser=$qtd_users&datarancho=$datarancho") ?>" method="post">
                          <table class="table table-striped table-hover table-bordered text-center">
                            <thead>
                              <tr>
                                <th>Ordem</th>
                                <th>P/G</th>
                                <th>Nome Guerra</th>
                                <th><i class="fa fa-coffee" aria-hidden="true"></i> Café</th>
                                <th><i class="fas fa-drumstick-bite" aria-hidden="true"></i> Almoço</th>
                                <th><i class="fas fa-hotdog" aria-hidden="true"></i> Jantar</th>
                                <th>Responsável</th>
                                <th>Situação</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php for ($i = 0; $i < $qtd_users; $i++) { 
                                $reg = $qtdusers[$i];
                                $regpg = getPGrad($regidpgrad);
                                echo ("<tr>");
                                $userid = "userid" . $i;
                                $nmgrr = "nomeguerra" . $i;
                                $useridpg = "useridpg" . $i;
                                $useridsu = "useridsu" . $i;
                                $_SESSION[$userid] = $reg['id'];
                                $_SESSION[$nmgrr] = $reg['nomeguerra'];
                                $_SESSION[$useridpg] = $regidpgrad;
                                $_SESSION[$useridsu] = $reg['idsubunidade'];
                                echo ("<td>" . $_SESSION[$userid] . "</td>");
                                echo ("<td>" . $regpg . "</td>");
                                echo ("<td>" . $_SESSION[$nmgrr] . "</td>");
                                //Pesquisa situação de arranchamento
                                $consultarancho = $pdo2->prepare("SELECT * FROM arranchado WHERE data = :datarancho AND iduser = :iduser");
                                $consultarancho->bindParam(":datarancho", $datarancho, PDO::PARAM_STR);
                                $consultarancho->bindParam(":iduser", $reg['id'], PDO::PARAM_STR);
                                $consultarancho->execute();
                                $orancho = $consultarancho->fetchAll(PDO::FETCH_ASSOC);
                                if (count($orancho) < 1) { ?>
                                  <td><input type="checkbox" name=<?php echo ("ocafe" . $i . ""); ?> value="SIM"></td>
                                  <td><input type="checkbox" name=<?php echo ("oalmoco" . $i . ""); ?> value="SIM"></td>
                                  <td><input type="checkbox" name=<?php echo ("ojantar" . $i . ""); ?> value="SIM"></td>
                                  <td></td>
                                  <td></td>
                                <?php } else {
                                  $regrancho = $orancho[0]; ?>
                                    <td><input type="checkbox" name=<?= "ocafe" . $i . "" ?> value="SIM" <?= ($regrancho['cafe'] == "SIM") ? 'checked' : '' ?>></td>
                                    <td><input type="checkbox" name=<?= "oalmoco" . $i . "" ?> value="SIM" <?= ($regrancho['almoco'] == "SIM") ? 'checked' : '' ?>></td>
                                    <td><input type="checkbox" name=<?= "ojantar" . $i . "" ?> value="SIM" <?= ($regrancho['jantar'] == "SIM") ? 'checked' : '' ?>></td>
                              <?php 
                                  echo ("<td>" . $regrancho['quemgrava'] . "</td>");
                                  echo ("<td>" . $regrancho['modo'] . "</td>");
                                }
                                echo "</tr>";
                              } ?>
                            </tbody>
                          </table>
                          <hr>
                          <button type="submit" name="action" class="btn btn-warning">ARRANCHAR</button>
                        </form>
                      </div>
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
<?php } else {
  header('Location: select.php?token=' . $msgerro);
} ?>