<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if (!($_SESSION['nivel_guarda'] == "Anotador Gda" || $_SESSION['nivel_guarda'] == "Cabo Gda" || $_SESSION['nivel_guarda'] == "Oficial e Sargento" || $_SESSION['nivel_guarda'] == "Supervisor" || $_SESSION['nivel_guarda'] == "Administrador")) {
  $msgerro = base64_encode('Usuário não possui acesso! Somente usuários: Anotador Gda, Cabo Gda, Oficial e Sargento!');
  header('Location: index.php?token=' . $msgerro);
  exit();
}

$pdo = conectar("membros");
$pdo2 = conectar("guarda");
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
      <?php render_content_header('Entrada e Saída de Viaturas Militares', 'fa fa-bus'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-6">
            <form id="validation" action="viaturas2.php" method="post">
              <div class="panel"><?php render_cabecalho_painel('LANÇAR SAÍDA DE VIATURAS:', 'fa fa-bus', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-md-12 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Selecione a Viatura Militar na
                          lista:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-bus"></i></span>
                          <select id="tkvtrSaida" name="tkvtr" class="form-control select" style="width: 100%" required>
                            <?php
                            $consulta1 = $pdo2->prepare("SELECT * FROM viatura WHERE situacao = '0' ORDER BY tipo, marca, modelo, placa, odometro ASC");
                            $consulta1->execute();
                            echo ("<option></option>");
                            echo ("<optgroup label='Viatura Militar da OM'>");
                            while ($reg1 = $consulta1->fetch(PDO::FETCH_ASSOC)) :
                              echo ("<option value=" . base64_encode($reg1['id']) . number_format($reg1['odometro'], 0, '', '.') . ">" . $reg1['placa'] . " - " . $reg1['modelo']
                                . " - " . $reg1['marca'] . " - (" . $reg1['tipo'] . ") - Ult Od: " . number_format($reg1['odometro'], 0, '', '.') . " Km</option>");
                            endwhile;
                            echo ("</optgroup>");
                            ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Selecione o Chefe de Viatura na lista:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-group"></i></span>
                          <select name="tkchvtr" class="form-control select" style="width: 100%" required>
                            <?php
                            $consulta2 = $pdo->prepare("SELECT * FROM usuarios WHERE userativo = 'S' ORDER BY idpgrad, nomecompleto ASC");
                            $consulta2->execute();
                            echo ("<option></option>");
                            echo ("<optgroup label='Chefe de Viatura'>");
                            while ($reg2 = $consulta2->fetch(PDO::FETCH_ASSOC)) :
                              echo ("<option value=" . base64_encode($reg2['id']) . ">" . getPGrad($reg2['idpgrad']) . "  - " . $reg2['nomecompleto'] . " (" . $reg2['nomeguerra'] . ")</option>");
                            endwhile;
                            echo ("</optgroup>"); ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Selecione o Motorista na lista:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-group"></i></span>
                          <select name="tkmtr" class="form-control select" style="width: 100%" required>
                            <?php $consulta3 = $pdo->prepare("SELECT * FROM usuarios WHERE userativo = 'S' ORDER BY idpgrad, nomecompleto ASC");
                            $consulta3->execute();
                            echo ("<option></option>");
                            echo ("<optgroup label='Motorista'>");
                            while ($reg3 = $consulta3->fetch(PDO::FETCH_ASSOC)) :
                              echo ("<option value=" . base64_encode($reg3['id']) . ">" . getPGrad($reg3['idpgrad']) . "  - " . $reg3['nomecompleto'] . " (" . $reg3['nomeguerra'] . ")</option>");
                            endwhile; ?>
                            </optgroup>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Destino:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fas fa-route"></i></span>
                          <input type="text" class="form-control text-uppercase maxLength" name="destino" maxlength="30" placeholder="Destino" required>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Número da Ficha:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fas fa-file-contract"></i></span>
                          <input type="text" class="form-control fichaVtr" name="ficha" pattern="^\d{4}/\d{2}$" placeholder="XXXX/XX" required>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Odômetro saída:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fas fa-tachometer-alt"></i></span>
                          <input type="text" class="form-control odometro" id="odometroSaida2" name="odometro" pattern="^\d{3}.\d{3}$" placeholder="XXX.XXX - Km" required>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Hora saída:</label>
                        <div class="input-group bootstrap-timepicker timepicker">
                          <span class="input-group-addon date-time-color"><i class="fa fa-clock-o"></i></span>
                          <input type="text" class="form-control time" name="hora" value="<?php echo ($hora); ?>" required>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 mb-sm"><?php render_data_field('data', true, 'Data saída:', 'now'); ?></div>
                    <div class="col-md-12">
                      <hr>
                      <div class="input-group mb-sm">
                        <div id="divcheck2"></div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <button type="submit" id="btnSaida" name="action" value='Saiu do Aquartelamento' class="btn btn-primary" style="width: 140px;">SAIU</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="col-sm-12 col-md-6">
            <form id="validation" action="viaturas3.php" method="post">
              <div class="panel"><?php render_cabecalho_painel('LANÇAR ENTRADA DE VIATURAS:', 'fa fa-bus', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-md-12 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Selecione a Viatura Militar e Chefe de Viatura na lista:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-bus"></i></span>
                          <select id="tkVtrEntrada" name="tkvtr" class="form-control select" style="width: 100%" required>
                            <?php
                            $consulta4 = $pdo2->prepare("SELECT * FROM viatura WHERE situacao = '1' ORDER BY tipo, marca, modelo, placa ASC");
                            $consulta4->execute();
                            echo ("<option></option>");
                            echo ("<optgroup label='Viatura Militar da OM'>");
                            while ($reg4 = $consulta4->fetch(PDO::FETCH_ASSOC)) :
                              $consulta5 = $pdo->prepare("SELECT id, idpgrad, nomeguerra, idpgrad FROM usuarios");
                              $consulta5->execute();
                              while ($reg_usuarios = $consulta5->fetch(PDO::FETCH_ASSOC)) :
                                if ($reg4['idchvtr'] == $reg_usuarios['id']) {
                                  echo ("<option value=" . base64_encode($reg4['id']) . number_format($reg4['odometro'], 0, '', '.') . ">" . $reg4['placa'] . " - " . $reg4['modelo']
                                    . " - (" . $reg4['tipo'] . ") - Od: " . number_format($reg4['odometro'], 0, '', '.') . " Km - Ch Vtr " . getPGrad($reg_usuarios['idpgrad']) . " " . $reg_usuarios['nomeguerra'] . "</option>");
                                }
                              endwhile;
                            endwhile;
                            echo ("</optgroup>");
                            ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label text-bold color-danger">Odômetro saída:</label>
                        <div class="input-group">
                          <span class="input-group-addon color-danger"><i class="fas fa-tachometer-alt"></i></span>
                          <input id="odometroSaida" type="text" class="form-control text-bold color-danger" disabled="Campo não pode ser editado">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label color-darker-2">Odômetro entrada:</label>
                        <div class="input-group">
                          <span class="input-group-addon color-darker-2"><i class="fas fa-tachometer-alt"></i></span>
                          <input type="text" id="odometroEntrada" class="form-control odometro" name="odometro" pattern="^\d{3}.\d{3}$" placeholder="XXX.XXX - Km" required>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 mb-sm"><?php render_hora_field('hora', true, 'Hora entrada:') ?></div>
                    <div class="col-md-6 mb-sm"><?php render_data_field('data', true, 'Data entrada:', 'now'); ?></div>
                    <div class="col-md-12">
                      <hr>
                      <div class="input-group mb-sm"><div id="divcheck"></div></div>
                    </div>
                    <div class="col-md-12">
                      <button type="submit" id="btnEntrada" name="action" value='Entrou no Aquartelamento' class="btn btn-darker-1" style="width: 140px;" disabled>ENTROU</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
        <?php include '../recursos/views/scroll_to_top.php'; ?>
    </div>
  </div>
  <?php include '../recursos/views/footer.php'; ?>
  <script type="text/javascript">
    //var e = document.getElementById("tkVtrEntrada");
    //var odometroSaida = e.options[e.selectedIndex].value;
    //var odometroSaida = $("#tkVtrEntrada :selected").val();
    //document.getElementById("odometroSaida").setAttribute('value', odometroSaida);
    var ultOdo = 0;
    $('#tkvtrSaida').change(function() {
        var value = $(this).val();
        var value = value.replace(/[^\d]+/, '');
        switch (value.length) {
            case 1:
                value = ('000.00' + value);
                break;
            case 2:
                value = ('000.0' + value);
                break;
            case 3:
                value = ('000.' + value);
                break;
            case 5:
                value = ('00' + value);
                break;
            case 6:
                value = ('0' + value);
                break;
        }
        ultOdo = value;

    });
    $('#tkVtrEntrada').change(function() {
        var value = $(this).val();
        var value = value.replace(/[^\d]+/, '');
      switch (value.length) {
        case 1:
          value = ('000.00' + value);
          break;
        case 2:
          value = ('000.0' + value);
          break;
        case 3:
          value = ('000.' + value);
          break;
        case 5:
          value = ('00' + value);
          break;
        case 6:
          value = ('0' + value);
          break;
      }
      $('#odometroSaida').val(value);
      $('#odometroSaida').select2().trigger('change');
    });

    $(document).ready(function() {
      $("#odometroEntrada").keyup(checkOdometroMatch);
      $("#odometroSaida2").keyup(checkOdometroMatch2);
    });
    function checkOdometroMatch2() {
        ultOdo = parseFloat(ultOdo);
        var odometroSaida2 = $("#odometroSaida2").val();
        var odometroSaida2 = parseFloat(odometroSaida2);
        var odometroPercorridoInterno = ((odometroSaida2 - ultOdo) * 1000);

        if (odometroSaida2 == '' | odometroSaida2 == '0' | isNaN(odometroPercorridoInterno)) {
            $("#divcheck2").html("<span class='color-danger text-bold'>Campo de odometro vazio!</span>");
            document.getElementById("btnSaida").disabled = true;
        } else if (odometroSaida2 < ultOdo) {
            $("#divcheck2").html("<span class='color-danger text-bold'>Odômetro de saída não pode ser menor que o da última entrada!</span>");
            document.getElementById("btnSaida").disabled = false;
        } else if (odometroPercorridoInterno.toFixed(0) > 100) {
            $("#divcheck2").html("<span class='color-danger text-bold'>A viatura percorreu dentro da OM: " + odometroPercorridoInterno.toFixed(0) + " Km, este valor é muito alto!</span>");
            document.getElementById("btnSaida").disabled = false;
        } else if (odometroSaida2 ==ultOdo) {
            $("#divcheck2").html("<span class='color-warning text-bold'>Os odometros são iguais, é melhor verficar novamente antes de Lançar!</span>");
            document.getElementById("btnSaida").disabled = false;
        } else if (odometroPercorridoInterno.toFixed(0) > 50) {
            $("#divcheck2").html("<span class='color-warning text-bold'>A viatura percorreu: " + odometroPercorridoInterno.toFixed(0) + " Km, é melhor verficar novamente antes de Lançar!</span>");
            document.getElementById("btnSaida").disabled = false;
        } else {
            $("#divcheck2").html("<span class='color-success text-bold'>A viatura percorreu: " + odometroPercorridoInterno.toFixed(0) + " Km</span>");
            document.getElementById("btnSaida").disabled = false;
        }
    }
    function checkOdometroMatch() {
      var odometroEntrada = $("#odometroEntrada").val();
      var odometroEntrada = parseFloat(odometroEntrada);
      var odometroSaida = $("#odometroSaida").val();
      var odometroSaida = parseFloat(odometroSaida);
      var odometroPercorrido = ((odometroEntrada - odometroSaida) * 1000);

      if (odometroEntrada == '' | odometroEntrada == '0' | isNaN(odometroPercorrido)) {
        $("#divcheck").html("<span class='color-danger text-bold'>Campo de odometro vazio!</span>");
        document.getElementById("btnEntrada").disabled = true;
      } else if (odometroEntrada < odometroSaida) {
        $("#divcheck").html("<span class='color-danger text-bold'>Odômetro de entrada não pode ser menor!</span>");
        document.getElementById("btnEntrada").disabled = true;
      } else if (odometroPercorrido.toFixed(0) > 4000) {
        $("#divcheck").html("<span class='color-danger text-bold'>A viatura percorreu " + odometroPercorrido.toFixed(0) + " Km desde a última vez que entrou na OM, este valor é muito alto!</span>");
        document.getElementById("btnEntrada").disabled = true;
      } else if (odometroEntrada == odometroSaida) {
        $("#divcheck").html("<span class='color-warning text-bold'>Os odometros são iguais, é melhor verficar novamente antes de Lançar!</span>");
        document.getElementById("btnEntrada").disabled = false;
      } else if (odometroPercorrido.toFixed(0) > 50) {
        $("#divcheck").html("<span class='color-warning text-bold'>A viatura percorreu " + odometroPercorrido.toFixed(0) + " Km desde a última vez que entrou na OM, é melhor verficar novamente antes de Lançar!</span>");
        document.getElementById("btnEntrada").disabled = false;
      } else {
        $("#divcheck").html("<span class='color-success text-bold'>A viatura percorreu " + odometroPercorrido.toFixed(0) + " Km desde a última vez que entrou na OM</span>");
        document.getElementById("btnEntrada").disabled = false;
      }
    }
  </script>
  
</body>

</html>