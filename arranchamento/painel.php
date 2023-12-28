<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

// essa pagina permite arranchar por data

$datarancho = filter_input(INPUT_POST, "datarancho", FILTER_SANITIZE_STRING);
$convdata = strtotime(date_converter($datarancho));
$ds = date('D'); // pega o dia da semana da data atual
$dtlimite = date('Y-m-d', strtotime("+2 days"));
$dtpossivel = date('d/m/Y', strtotime("+2 days"));

if ($convdata <= strtotime($dtlimite)) {
  $msgerro = base64_encode('Arranchar por Data disponível a partir de ' . $dtpossivel);
  header("Location: index.php?token=" . $msgerro);
}

$p2 = conectar("arranchamento"); // nova conexão com base de dados secundária
$sistema = base64_encode('SISTEMA DE ARRANCHAMENTO');
$idmembro = $_SESSION['auth_data']['id'];
?>
<!doctype html>
<html lang="pt-BR" class="fixed">

<head>
  <?php include '../recursos/views/cabecalho.php'; ?>
</head>

<body>
  <div class="wrap">
    <div class="page-header"><?php render_painel_usu('ARRANCHAMENTO', $_SESSION['nivel_arranchamento']); ?></div>
    <div class="page-body">
      <div class="left-sidebar"><?php include 'menu_opc.php'; ?></div>
      <div class="content">
      <?php render_content_header('Arranchamento por data', 'fa fa-calendar-alt'); ?>
        <div class="row animated fadeInUp">
          <div class="col-sm-12 col-md-6">
            <div class="row">
              <div class="panel"><?php render_cabecalho_painel('ARRANCHAR PARA O DIA:', 'far fa-calendar-alt', true); ?>
                <div class="panel-content">
                  <div class="table-responsive">
                    <form action="gravaranchodia.php?dtr=<?php echo ($datarancho); ?>" method="post">
                      <table class="table table-striped table-hover table-bordered text-center" style="width:100%">
                        <?php
                        // PESQUISA SE EXISTE CARDÁPIO NO DIA
                        $psqcardapio = $p2->prepare("SELECT * FROM cardapio WHERE data = :data");
                        $psqcardapio->bindParam(':data', $datarancho);
                        $psqcardapio->execute();
                        $mcardapio = $psqcardapio->fetchAll(PDO::FETCH_ASSOC);
                        if (count($mcardapio) < 1) {
                          $cardcafe = "Cardápio não cadastrado.";
                          $cardalmoco = "Cardápio não cadastrado.";
                          $cardjantar = "Cardápio não cadastrado.";
                        } else {
                          $diacardapio = $mcardapio[0];
                          $cardcafe = $diacardapio['cafe'];
                          $cardalmoco = $diacardapio['almoco'];
                          $cardjantar = $diacardapio['jantar'];
                        }
                        // VERIFICA SE FOI ARRANCHADO NESTA DATA.
                        $inforancho = $p2->prepare("SELECT * FROM arranchado WHERE iduser = :iduser AND data = :data");
                        $inforancho->bindParam(':iduser', $idmembro);
                        $inforancho->bindParam(':data', $datarancho);
                        $inforancho->execute();
                        $rancho = $inforancho->fetchAll(PDO::FETCH_ASSOC);
                        if (count($rancho) < 1) {
                          $ocafe = "";
                          $oalmoco = "";
                          $ojantar = "";
                        } else {
                          $dadosrancho = $rancho[0];
                          $ocafe = $dadosrancho['cafe'];
                          $oalmoco = $dadosrancho['almoco'];
                          $ojantar = $dadosrancho['jantar'];
                        } ?>
                        <thead><tr><th style="width:50%">Refeição</th><th style="width:50%">Cardápio</th></tr></thead>
                        <tbody>
                          <?php if ($ocafe == "SIM") { ?>
                            <tr> <td><input type="checkbox" name="ocafe" value="SIM" checked> <i class="fa fa-coffee" aria-hidden="true"></i> Café</td><td><?= $cardcafe ?></td></tr>
                          <?php } else { ?>
                            <tr><td><input type="checkbox" name="ocafe" value="SIM"> <i class="fa fa-coffee" aria-hidden="true"></i> Café</td><td><?= $cardcafe ?></td></tr>
                          <?php }
                          if ($oalmoco == "SIM") { ?>
                            <tr><td><input type="checkbox" name="oalmoco" value="SIM" checked> <i class="fas fa-drumstick-bite" aria-hidden="true"></i> Almoço</td><td><?= $cardalmoco ?></td></tr>
                          <?php } else { ?>
                            <tr><td><input type="checkbox" name="oalmoco" value="SIM"> <i class="fas fa-drumstick-bite" aria-hidden="true"></i> Almoço</td><td><?= $cardalmoco ?></td></tr>
                          <?php }
                          if ($ojantar == "SIM") { ?>
                            <tr><td><input type="checkbox" name="ojantar" value="SIM" checked> <i class="fas fa-hotdog" aria-hidden="true"></i> Jantar</td><td><?= $cardjantar ?></td></tr>
                          <?php } else { ?>
                            <tr><td><input type="checkbox" name="ojantar" value="SIM"> <i class="fas fa-hotdog" aria-hidden="true"></i> Jantar</td><td><?= $cardjantar ?></td></tr>
                          <?php } ?>
                        </tbody>
                      </table>
                      <hr>
                      <button type="submit" name="action" class="btn btn-primary">ARRANCHAR</button>
                    </form>
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