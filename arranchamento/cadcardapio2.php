<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if ($_SESSION['auth_data']['contarancho'] < "3") {
  header('Location: index.php');
  exit();
}

$pdo2 = conectar("arranchamento"); // nova conexão com base de dados secundária
$sistema = base64_encode('SISTEMA DE ARRANCHAMENTO');
$idmembro = $_SESSION['auth_data']['id'];
$datacardapio = filter_input(INPUT_POST, "datacardapio");
$pesquisa = "SELECT * FROM cardapio WHERE data = :datacardapio";
$stmt = $pdo2->prepare($pesquisa);
$stmt->bindParam(':datacardapio', $datacardapio);
$stmt->execute();
$contcardapio = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (count($contcardapio) < 1) {
  $cardcafe = "";
  $cardalmoco = "";
  $cardjantar = "";
} else {
  $selecard = $contcardapio[0];
  $cardcafe = $selecard['cafe'];
  $cardalmoco = $selecard['almoco'];
  $cardjantar = $selecard['jantar'];
}
$convdata = strtotime(date_converter($datacardapio));
$dtlimite = date('Y-m-d', strtotime("+1 days"));
if ($convdata <= strtotime($dtlimite)) {
  $editavel = "NAO";
} else {
  $editavel = "SIM";
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
      <?php render_content_header('Cardápio', 'fa fa-hemburger'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <?php if ($editavel == "SIM") { ?>
            <form class="form-horizontal form-stripe" action="confcardapio.php?dtc=<?php echo ($datacardapio); ?>" method="post">
            <?php } else { ?>
              <form class="form-horizontal form-stripe" action="cadcardapio.php" method="post">
              <?php } ?>
              <div class="col-sm-12">
                <div class="panel"><?php render_cabecalho_painel('CARDÁPIO DO DIA '.$datacardapio, 'fas fa-hamburger', true); ?>
                  <div class="panel-content">
                    <div class="row">
                      <div class="col-sm-4">
                        <div class="panel">
                          <div class="panel-header  panel-primary">
                            <h3 class="panel-title"><i class="fa fa-coffee"></i> Café:</h3>
                          </div>
                          <div class="panel-content">
                            <div class="col-sm-12">
                              <div class="form-group">
                                <label for="textareaMaxLength" class="control-label"></label>
                                <?php
                                if ($editavel == "SIM") {
                                ?>
                                  <textarea style="resize: none" name="cardcafe" class="form-control" rows="4" id="textareaMaxLength" placeholder="Dados do café da manhã" maxlength="100" required><?php echo ($cardcafe) ?></textarea>
                                <?php
                                } else {
                                ?>
                                  <textarea style="resize: none" name="cardcafe" class="form-control" rows="4" id="textareaMaxLength" placeholder="Dados do café da manhã" maxlength="100" disabled><?php echo ($cardcafe) ?></textarea>
                                <?php
                                }
                                ?>
                                <span class="help-block"><i class="fa fa-info-circle mr-xs"></i>Máximo de caracteres <span class="code">100</span></span>
                              </div>
                            </div>
                            <div class="mb-md">
                            </div>
                            <div class="clearfix">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="panel">
                          <div class="panel-header  panel-primary">
                            <h3 class="panel-title"><i class="fas fa-drumstick-bite"></i> Almoço:</h3>
                          </div>
                          <div class="panel-content">
                            <div class="col-sm-12">
                              <div class="form-group">
                                <label for="textareaMaxLength2" class="control-label"></label>
                                <?php
                                if ($editavel == "SIM") {
                                ?>
                                  <textarea style="resize: none" name="cardalmoco" class="form-control" rows="4" id="textareaMaxLength" placeholder="Dados do Almoço" maxlength="100" required><?php echo ($cardalmoco) ?></textarea>
                                <?php
                                } else {
                                ?>
                                  <textarea style="resize: none" name="cardalmoco" class="form-control" rows="4" id="textareaMaxLength" placeholder="Dados do Almoço" maxlength="100" disabled><?php echo ($cardalmoco) ?></textarea>
                                <?php
                                }
                                ?>
                                <span class="help-block"><i class="fa fa-info-circle mr-xs"></i>Máximo de caracteres <span class="code">100</span></span>
                              </div>
                            </div>
                            <div class="mb-md">
                            </div>
                            <div class="clearfix">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="panel">
                          <div class="panel-header  panel-primary">
                            <h3 class="panel-title"><i class="fas fa-hotdog"></i> Jantar:</h3>
                          </div>
                          <div class="panel-content">
                            <div class="col-sm-12">
                              <div class="form-group">
                                <label for="textareaMaxLength3" class="control-label"></label>
                                <?php
                                if ($editavel == "SIM") {
                                ?>
                                  <textarea style="resize: none" name="cardjantar" class="form-control" rows="4" id="textareaMaxLength" placeholder="Dados do Jantar" maxlength="100" required><?php echo ($cardjantar) ?></textarea>
                                <?php
                                } else {
                                ?>
                                  <textarea style="resize: none" name="cardjantar" class="form-control" rows="4" id="textareaMaxLength" placeholder="Dados do Jantar" maxlength="100" disabled><?php echo ($cardjantar) ?></textarea>
                                <?php
                                }
                                ?>
                                <span class="help-block"><i class="fa fa-info-circle mr-xs"></i>Máximo de caracteres <span class="code">100</span></span>
                              </div>
                            </div>
                            <div class="mb-md">
                            </div>
                            <div class="clearfix">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <button type="submit" class="btn btn-warning">
                          LANÇAR
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              </form>
        </div>
      </div>
    </div>
  </div>
  <?php include '../recursos/views/footer.php'; ?>
</body>

</html>