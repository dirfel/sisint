<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

$token = base64_decode(filter_input(INPUT_GET, "token", FILTER_SANITIZE_STRING));
$token2 = base64_decode(filter_input(INPUT_GET, "token2", FILTER_SANITIZE_STRING));

if (!($_SESSION['nivel_guarda'] == "Anotador Gda" || $_SESSION['nivel_guarda'] == "Cabo Gda" || $_SESSION['nivel_guarda'] == "Oficial e Sargento" || $_SESSION['nivel_guarda'] == "Supervisor" || $_SESSION['nivel_guarda'] == "Administrador")) {
  $msgerro = base64_encode('Usuário não possui acesso! Somente usuários: Anotador Gda e Cabo Gda!');
  header('Location: index.php?token=' . $msgerro);
  exit();
}

$pdo = conectar("membros");
$data = date("d/m/Y");
$hora = date("H:i");

?>
<!doctype html>
<html lang="pt-BR" class="fixed left-sidebar-collapsed">

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
      <?php render_content_header('Entrada e Saída de Militares', 'fa fa-group'); ?>
        <form id="validation" action="militares2.php" method="post">
          <div class="row animated fadeInLeftBig">
            <?php include '../recursos/views/token.php'; ?>
            <div class="col-sm-12 col-md-6">
              <div class="panel"><?php render_cabecalho_painel('LANÇAR ENTRADA OU SAÍDA DURANTE O EXPEDIENTE:', 'fa fa-suitcase', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-md-12"><?php render_militar_ativo_select('tkusr', 'select2-example-basic', true, true) ?></div>
                    <div class="col-md-12">
                      <hr>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="inputMaxLength" class="control-label">Hora após expediente:</label>
                        <div class="input-group bootstrap-timepicker timepicker">
                          <span class="input-group-addon date-time-color"><i class="fa fa-clock-o"></i></span>
                          <input type="text" class="form-control time" name="hora" value="<?php echo $hora ?>" required>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6"><?php render_data_field('data', true, 'Data durante o expediente:', 'now'); ?></div>
                    <div class="col-md-12">
                    <hr>
                        </div>
                    <div class="col-md-6">
                      <div class="form-check">
                        <input id="check01" value="" type="checkbox" class="form-check-input" name="bike">
                        <label for="check01" class="form-check-label">Estava com bicicleta</label>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <hr>
                      <div class="col-md-6">
                      <button type="submit" name="action" value='Saiu DURANTE o expediente' class="btn btn-darker-1" style="width: 250px;">SAIU DURANTE EXPEDIENTE</button>
                        </div>
                      <div class="col-md-6">
                      <button type="submit" name="action" value='Entrou DURANTE o expediente' class="btn btn-darker-2" style="width: 250px;">ENTROU DURANTE EXPEDIENTE</button>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </form>
        <form id="validation" action="militares2.php" method="post">
          <div class="col-sm-12 col-md-6">
            <div class="panel"><?php render_cabecalho_painel('LANÇAR ENTRADA OU SAÍDA APÓS O EXPEDIENTE:', 'fas fa-umbrella-beach', true); ?>
              <div class="panel-content">
                <div class="row">
                  <div class="col-md-12"><?php render_militar_ativo_select('tkusr', 'select2-example-basic2', true, true) ?></div>
                  <div class="col-md-12"><hr></div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="inputMaxLength" class="control-label">Hora após expediente:</label>
                      <div class="input-group bootstrap-timepicker timepicker">
                        <span class="input-group-addon date-time-color"><i class="fa fa-clock-o"></i></span>
                        <input type="text" class="form-control time" name="hora" value="<?php echo $hora ?>" required>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6"><?php render_data_field('data', true, 'Data após o expediente', 'now'); ?></div>
                  <div class="col-md-12">
                    <hr>
                        </div>
                    <div class="col-md-6">
                      <div class="form-check">
                        <input id="check02" value="" type="checkbox" class="form-check-input" name="bike">
                        <label for="check02" class="form-check-label">Estava com bicicleta</label>
                      </div>
                    </div>
                  <div class="col-md-12">
                    <hr>
                    <div class="col-md-6">
                      <button type="submit" name="action" value='Saiu APÓS o expediente' class="btn btn-darker-1" style="width: 250px;">SAIU APÓS O EXPEDIENTE</button>
                    </div>
                    <div class="col-md-6">
                      <button type="submit" name="action" value='Entrou APÓS o expediente' class="btn btn-darker-2" style="width: 250px;">ENTROU APÓS O EXPEDIENTE</button>
                  </div>
                </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
      <?php include '../recursos/views/scroll_to_top.php'; ?>
  </div>
  <?php include '../recursos/views/footer.php'; ?>
  <script type="text/javascript">
    $('.date').datepicker({
      clearBtn: true,
      orientation: "bottom"
    })
    $('.time').timepicker({
      showMeridian: false,
      minuteStep: 1,
      defaultTime: false
    })
  </script>
</body>

</html>