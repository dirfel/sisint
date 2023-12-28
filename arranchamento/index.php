<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
$p1 = conectar("membros");
$p2 = conectar("arranchamento");
$idmembro = $_SESSION['auth_data']['id']; 
$confirmado = "SIM";

$maxdiasexibir = 50;

$cafe = $p2->prepare("SELECT data FROM arranchado WHERE iduser = :idusuario AND cafe = :cafe ORDER BY id DESC");
$cafe->bindParam(':idusuario', $idmembro, PDO::PARAM_INT);
$cafe->bindParam(':cafe', $confirmado, PDO::PARAM_STR);
$cafe->execute();
$oscafes = $cafe->fetchAll(PDO::FETCH_ASSOC);
$totalcafe = count($oscafes);
if ($totalcafe > 0) {
  $ultimocafe = $oscafes[0]['data'];
} else {
  $ultimocafe = "***";
}

$almoco = $p2->prepare("SELECT data FROM arranchado WHERE iduser = :idusuario AND almoco = :almoco ORDER BY id DESC");
$almoco->bindParam(':idusuario', $idmembro, PDO::PARAM_INT);
$almoco->bindParam(':almoco', $confirmado, PDO::PARAM_STR);
$almoco->execute();
$osalmoco = $almoco->fetchAll(PDO::FETCH_ASSOC);
$totalalmoco = count($osalmoco);
if ($totalalmoco > 0) {
  $ultimoalmoco = $osalmoco[0]['data'];
} else {
  $ultimoalmoco = "***";
}

$jantar = $p2->prepare("SELECT data FROM arranchado WHERE iduser = :idusuario AND jantar = :jantar ORDER BY id DESC");
$jantar->bindParam(':idusuario', $idmembro, PDO::PARAM_INT);
$jantar->bindParam(':jantar', $confirmado, PDO::PARAM_STR);
$jantar = $jantar->fetchAll(PDO::FETCH_ASSOC);
$totaljantar = count($osjantar ?? array());
if ($totaljantar > 0) {
  $ultimojantar = $osjantar[0]['data'];
} else {
  $ultimojantar = "***";
}

$somadias = 2; // fecha o arranchamento com 2 dias antes da refeição
$diasemana = date('D');

// O código abaixo faz com que o arranchamento feche toda a quinta para a semana seguinte
// if ($diasemana == 'Mon') { // verifica se o dia da semana é segunda-feira
//   $somadias = 7; 
// } else if ($diasemana == 'Tue') { // verifica se o dia da semana é segunda-feira
//     $somadias = 6;
// } else if ($diasemana == 'Wed') { // verifica se o dia da semana é segunda-feira
//     $somadias = 5;
// } else if ($diasemana == 'Thu') { // verifica se o dia da semana é quinta-feira
//   $somadias = 4;
// } else if ($diasemana == 'Fri') { // verifica se o dia da semana é sexta-feira
//   $somadias = 10;
// } else if ($diasemana == 'Sat') { // verifica se o dia da semana é sábado
//   $somadias = 9;
// } else if ($diasemana == 'Sun') { // verifica se o dia da semana é sábado
//     $somadias = 8;
// }
$contadias = 0;
for ($i = 0; $i < $maxdiasexibir; $i++) {
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
} ?>
<!doctype html>
<html lang="pt-BR" class="fixed">
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
      <?php render_content_header('Arranchamento', 'fa fa-home'); ?>
        <div class="row">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-4 animated fadeInUp">
            <div class="panel widgetbox wbox-2 bg-scale-0">
              <form class="form-horizontal form-stripe" action="painel.php" method="post">
                <div class="panel-content">
                    <div class="row">
                      <div class="col-xs-3"><span class="icon fas fa-calendar-alt"></span></div>
                      <div class="col-xs-8">
                        <h4 class="subtitle">
                          <?php render_data_field('datarancho', true, 'Arranchar por Data:', null); ?>
                          <div class="">
                            <button type="submit" name="action" class="btn btn-primary">ARRANCHAR</button>
                          </div>
                        </h4>
                      </div>
                    </div>
                </div>
              </form>
            </div>

            <?php render_card2(
              '', 'Arranchamento para o Café', 'icon fa fa-coffee', 
              '<h4 class="subtitle color-darker-2"><b>Arranchamento para o Café</b></h4>
              <h4 class="subtitle color-darker-2" style="padding-top:4px;">Arranchamento totais:<b>'.$totalcafe .'</b></h4>
              <h4 class="subtitle color-darker-2" style="padding-top:4px;">Último Arranchamento:<b>'.$ultimocafe .'</b></h4>', 
              '', ''
            ); ?>
            
            <?php render_card2(
              '', 'Arranchamento para o Almoço', 'icon fas fa-drumstick-bite', 
              '<h4 class="subtitle color-darker-2"><b>Arranchamento para o Almoço</b></h4>
              <h4 class="subtitle color-darker-2" style="padding-top:4px;">Arranchamento totais:<b>'.$totalalmoco .'</b></h4>
              <h4 class="subtitle color-darker-2" style="padding-top:4px;">Último Arranchamento:<b>'.$ultimoalmoco .'</b></h4>', 
              '', ''
            ); ?>

            <?php render_card2(
              '', 'Arranchamento para o Jantar', 'icon fas fa-hotdog', 
              '<h4 class="subtitle color-darker-2"><b>Arranchamento para o Jantar</b></h4>
              <h4 class="subtitle color-darker-2" style="padding-top:4px;">Arranchamento totais:<b>'.$totaljantar .'</b></h4>
              <h4 class="subtitle color-darker-2" style="padding-top:4px;">Último Arranchamento:<b>'.$ultimojantar .'</b></h4>', 
              '', ''
            ); ?>
            
          </div>
          <div class="col-sm-12 col-md-8 animated fadeInRightBig">
            <div class="panel"><?php render_cabecalho_painel('ARRANCHAR:', 'fas fa-utensils', true); ?>
              <div class="panel-content">
                <div class="table-responsive">
                  <form action="dezdias.php?soma=<?=($somadias) ?>" method="post">
                    <table class="table table-striped table-hover table-bordered text-center mv-xlg" style="font-size: 15px; width:100%">
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
                        <?php
                        for ($i = 0; $i < $maxdiasexibir; $i++) {
                          echo ("<tr><td>" . $semana[$i] . "</td><td>" . $odia[$i] . "</td>");
                          $inforancho = $p2->prepare("SELECT * FROM arranchado WHERE iduser = :iduser AND data = :data");
                          $inforancho->bindParam(':iduser', $idmembro);
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
                              <td><input type="checkbox" name=<?=("ocafe" . $i . "") ?> value="" disabled="Campo não pode ser editado" checked></td>
                            <?php } else { ?>
                              <td><input type="checkbox" name=<?=("ocafe" . $i . "") ?> value="SIM" checked></td>
                            <?php }
                          } else {
                            if ($i < $somadias) { ?>
                              <td><input type="checkbox" name=<?=("ocafe" . $i . "") ?> value="" disabled="Campo não pode ser editado"></td>
                            <?php } else { ?>
                              <td><input type="checkbox" name=<?=("ocafe" . $i . "") ?> value="SIM"></td>
                            <?php }
                          }

                          if ($oalmoco[$i] == "SIM") {
                            if ($i < $somadias) { ?>
                              <td><input type="checkbox" name=<?=("oalmoco" . $i . "") ?> value="" disabled="Campo não pode ser editado" checked></td>
                            <?php } else { ?>
                              <td><input type="checkbox" name=<?=("oalmoco" . $i . "") ?> value="SIM" checked></td>
                            <?php }
                          } else {
                            if ($i < $somadias) { ?>
                              <td><input type="checkbox" name=<?=("oalmoco" . $i . "") ?> value="" disabled="Campo não pode ser editado"></td>
                            <?php } else { ?>
                              <td><input type="checkbox" name=<?=("oalmoco" . $i . "") ?> value="SIM"></td>
                            <?php }
                          }
                          if ($ojantar[$i] == "SIM") {
                            if ($i < $somadias) { ?>
                              <td><input type="checkbox" name=<?=("ojantar" . $i . "") ?> value="" disabled="Campo não pode ser editado" checked></td>
                            <?php } else { ?>
                              <td><input type="checkbox" name=<?=("ojantar" . $i . "") ?> value="SIM" checked></td>
                            <?php }
                          } else {
                            if ($i < $somadias) { ?>
                              <td><input type="checkbox" name=<?=("ojantar" . $i . "") ?> value="" disabled="Campo não pode ser editado"></td>
                            <?php } else { ?>
                              <td><input class="color-success" type="checkbox" name=<?=("ojantar" . $i . "") ?> value="SIM"></td>
                            <?php }
                          }
                          $psqcardapio = $p2->prepare("SELECT * FROM cardapio WHERE data = :data");
                          $psqcardapio->bindParam(':data', $odia[$i]);
                          $psqcardapio->execute();
                          $mcardapio = $psqcardapio->fetchAll(PDO::FETCH_ASSOC);
                          $cardia = $odia[$i];
                          if (count($mcardapio) < 1) { ?>
                            <td><a> <i class="fa fa-close color-danger"></i></a></td>
                          <?php } else { ?>
                            <td><a href="<?=('mostracard.php?out=' . $cardia) ?>" class='fa fa-eye color-success' data-toggle='modal' data-target='#lg-modal'></a></td>
                        <?php } } ?>
                        </tr>
                      </tbody>
                    </table>
                    <hr>
                    <button type="submit" name="action" class="btn btn-darker-1" style="margin-bottom: 22px !important">ARRANCHAR</button>
                  </form>
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