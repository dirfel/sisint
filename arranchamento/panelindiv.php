<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if ($_SESSION['auth_data']['contarancho'] < "3") {
  header('Location: index.php');
  exit();
}

$p1 = conectar("membros");
$p2 = conectar("arranchamento");

$idusuario = base64_decode(filter_input(INPUT_POST, "tkusr"));
$sistema = base64_encode('SISTEMA DE ARRANCHAMENTO');

$select_usuarios = $p1->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE id = :idusuario");
$select_usuarios->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
$select_usuarios->execute();
while ($reg = $select_usuarios->fetch(PDO::FETCH_ASSOC)) {
  $nome_usuarios = $reg['nomeguerra'];
  $pg_usuarios = getPGrad($reg['idpgrad']);
}
$oarranchado = $pg_usuarios . " " . $nome_usuarios;

$somadias = 1;
$contadias = 0;
for ($i = 0; $i < 15; $i++) {
  $odia[$i] = date('d/m/Y', strtotime("+" . $contadias . " days"));
  $semana[$i] = date('D', strtotime("+" . $contadias . " days"));
  $contadias++;
  if ($semana[$i] == "Sun") { $semana[$i] = "Domingo";       }
  if ($semana[$i] == "Mon") { $semana[$i] = "Segunda-feira"; }
  if ($semana[$i] == "Tue") { $semana[$i] = "Terça-feira";   }
  if ($semana[$i] == "Wed") { $semana[$i] = "Quarta-feira";  }
  if ($semana[$i] == "Thu") { $semana[$i] = "Quinta-feira";  }
  if ($semana[$i] == "Fri") { $semana[$i] = "Sexta-feira";   }
  if ($semana[$i] == "Sat") { $semana[$i] = "Sábado";        }
}
?>
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
      <?php render_content_header('Arranchamento individual do '. $oarranchado, 'fa fa-user-tag'); ?>
        <div class="row animated fadeInUp">
          <div class="col-sm-12 col-md-8">
            <div class="row">
              <div class="panel"><?php render_cabecalho_painel('ARRANCHAR INDIVIDUALMENTE: '.$oarranchado, 'fas fa-user-tag', true); ?>
                <div class="panel-content">
                  <div class="table-responsive">
                    <form action="dezdiasind.php?soma=<?php echo ($somadias); ?>&usr=<?php echo ($idusuario); ?>" method="post">
                      <table class="table table-striped table-hover table-bordered text-center">
                        <thead>
                          <tr>
                            <th>Dia Semana</th>
                            <th>Data</th>
                            <th><i class="fa fa-coffee" aria-hidden="true"></i> Café</th>
                            <th><i class="fas fa-drumstick-bite" aria-hidden="true"></i> Almoço</th>
                            <th><i class="fas fa-hotdog" aria-hidden="true"></i> Jantar</th>
                            <th>Cardápio</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php for ($i = 0; $i < 15; $i++) { ?>
                            <tr>
                              <td><?php echo ($semana[$i]); ?></td>
                              <td><?php echo ($odia[$i]); ?></td>
                              <?php
                              $inforancho = $p2->prepare("SELECT * FROM arranchado WHERE iduser = :iduser AND data = :data");
                              $inforancho->bindParam(':iduser', $idusuario);
                              $inforancho->bindParam(':data', $odia[$i]);
                              $inforancho->execute();
                              $rancho = $inforancho->fetchAll(PDO::FETCH_ASSOC);
                              if (count($rancho) < 1) {
                                $ocafe[$i] = "";
                                $oalmoco[$i] = "";
                                $ojantar[$i] = "";
                              } else {
                                $dadosrancho = $rancho[0];
                                $ocafe[$i] = $dadosrancho['cafe'];
                                $oalmoco[$i] = $dadosrancho['almoco'];
                                $ojantar[$i] = $dadosrancho['jantar'];
                              }
                              if ($ocafe[$i] == "SIM") {
                                if ($i < $somadias) { ?>
                                  <td><input type="checkbox" name=<?php echo ("ocafe" . $i . ""); ?> value="" disabled="Campo não pode ser editado" checked></td>
                                <?php } else { ?>
                                  <td><input type="checkbox" name=<?php echo ("ocafe" . $i . ""); ?> value="SIM" checked>
                                  </td>
                                <?php }
                              } else {
                                if ($i < $somadias) { ?>
                                  <td><input type="checkbox" name=<?php echo ("ocafe" . $i . ""); ?> value="" disabled="Campo não pode ser editado"></td>
                                <?php } else { ?>
                                  <td><input type="checkbox" name=<?php echo ("ocafe" . $i . ""); ?> value="SIM"></td>
                                <?php }
                              }
                              if ($oalmoco[$i] == "SIM") {
                                if ($i < $somadias) { ?>
                                  <td><input type="checkbox" name=<?php echo ("oalmoco" . $i . ""); ?> value="" disabled="Campo não pode ser editado" checked></td>
                                <?php } else { ?>
                                  <td><input type="checkbox" name=<?php echo ("oalmoco" . $i . ""); ?> value="SIM" checked>
                                  </td>
                                <?php }
                              } else {
                                if ($i < $somadias) { ?>
                                  <td><input type="checkbox" name=<?php echo ("oalmoco" . $i . ""); ?> value="" disabled="Campo não pode ser editado"></td>
                                <?php } else { ?>
                                  <td><input type="checkbox" name=<?php echo ("oalmoco" . $i . ""); ?> value="SIM"></td>
                                <?php }
                              }
                              if ($ojantar[$i] == "SIM") {
                                if ($i < $somadias) { ?>
                                  <td><input type="checkbox" name=<?php echo ("ojantar" . $i . ""); ?> value="" disabled="Campo não pode ser editado" checked></td>
                                <?php
                                } else { ?>
                                  <td><input type="checkbox" name=<?php echo ("ojantar" . $i . ""); ?> value="SIM" checked>
                                  </td>
                                <?php }
                              } else {
                                if ($i < $somadias) { ?>
                                  <td><input type="checkbox" name=<?php echo ("ojantar" . $i . ""); ?> value="" disabled="Campo não pode ser editado"></td>
                                <?php } else { ?>
                                  <td><input type="checkbox" name=<?php echo ("ojantar" . $i . ""); ?> value="SIM"></td>
                                <?php }
                              }
                              $psqcardapio = $p2->prepare("SELECT * FROM cardapio WHERE data = :data");
                              $psqcardapio->bindParam(':data', $odia[$i]);
                              $psqcardapio->execute();
                              $mcardapio = $psqcardapio->fetchAll(PDO::FETCH_ASSOC);
                              $cardia = $odia[$i];
                              if (count($mcardapio) < 1) { ?>
                                <td><a> <i class="fa fa-close"></i></a></td>
                              <?php } else { ?>
                                <td><a href="<?php echo ('mostracard.php?out=' . $cardia); ?>" class='fa fa-eye' data-toggle='modal' data-target='#lg-modal'></a></td>
                              <?php } ?>
                            </tr>
                          <?php } ?>
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
      <?php include '../recursos/views/scroll_to_top.php'; ?>
    </div>
  </div>
  <?php include '../recursos/views/footer.php'; ?>
</body>

</html>