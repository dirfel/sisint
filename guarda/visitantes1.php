<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if (!($_SESSION['nivel_guarda'] == "Anotador Gda" || $_SESSION['nivel_guarda'] == "Cabo Gda" || $_SESSION['nivel_guarda'] == "Oficial e Sargento" || $_SESSION['nivel_guarda'] == "Supervisor" || $_SESSION['nivel_guarda'] == "Administrador")) {
  $msgerro = base64_encode('Usuário não possui acesso! Somente usuários: Anotador Gda e Cabo Gda!');
  header('Location: index.php?token=' . $msgerro);
  exit();
}

$pdo = conectar("guarda");
$data = date("d/m/Y");
$hora = date("H:i");

$visSit0 = read_visitante_por_situacao(0);
$vis0;
foreach($visSit0 as $vis) {
    $vis0[base64_encode($vis['id'])] = $vis['nomecompleto'] . " (" . $vis['tipo'] . ' ' . omitirDigitosCpf($vis['cpf']). ")";
}
// $visSit1 = read_visitante_por_situacao(1);
// $vis1;
// foreach($visSit1 as $vis) {
//     $vis1[base64_encode($vis['id'])] = $vis['nomecompleto'] . " (" . $vis['tipo'] . ' ' . omitirDigitosCpf($vis['cpf']). ")";
// }
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
      <?php render_content_header('Visitantes e Veículos', 'fa fa-plus'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-6">
            <form id="validation" action="visitantes2.php" method="post">
              <div class="panel"><?php render_cabecalho_painel('LANÇAR ENTRADA DE VISITANTE OU MILITAR DE OUTRA OM:', 'fa fa-street-view', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-md-8 mb-sm"><?php render_default_select('tkvisit', '', true, 'Selecione o Visitante ou Militar de outra OM na lista', 'Visitante ou Militar de outra OM', 'fa fa-street-view', $vis0, 'buscarVeic(value)'); ?></div>
                    <div class="col-md-4 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Hora entrada:</label>
                        <div class="input-group bootstrap-timepicker timepicker">
                          <span class="input-group-addon date-time-color"><i class="fa fa-clock-o"></i></span>
                          <input type="text" class="form-control time" name="hora" value="<?php echo ($hora); ?>" pattern="^\d{1,2}:\d{2}$" required="Preenchimento obrigatório">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-8 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Selecione o Veículo na lista:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-car"></i></span>
                          <select name="tkveiculo" id="veic" class="form-control select" style="width: 100%" required="Preenchimento obrigatório">
                            <option></option>
                            <optgroup label='Veículo do Visitante ou do Militar de outra OM'>
                            <?php
                            echo ("<option id='veic-" . str_replace("=", "", base64_encode('0')) . "' value=" . base64_encode('0') . ">Nenhum veículo</option>");
                            $consulta = $pdo->prepare("SELECT * FROM veiculo WHERE situacao = '0' ORDER BY tipo, marca, modelo, placa ASC");
                            $consulta->execute();
                            while ($reg = $consulta->fetch(PDO::FETCH_ASSOC)) :
                              echo ("<option id='veic-" . str_replace("=", "", base64_encode($reg['id'])) . "' value=" . base64_encode($reg['id']) . ">" . $reg['placa'] . " - " . $reg['modelo']
                                . " - " . $reg['marca'] . " - (" . $reg['tipo'] . ")</option>");
                            endwhile;
                            ?>
                            </optgroup>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4 mb-sm"><?php render_data_field('data', true, 'Data entrada:', 'now'); ?></div>
                   
                    <div class="col-md-8 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Destino do Visitante:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fas fa-compass"></i></span>
                          <input type="text" class="form-control text-uppercase maxLength" name="destino" placeholder="Destino" maxlength="30" required="Preenchimento obrigatório">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Nome do cracha do Visitante:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fas fa-id-card-alt"></i></span>
                          <input type="text" class="form-control text-uppercase maxLength" name="cracha" placeholder="Cracha" maxlength="30" required="Preenchimento obrigatório">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <hr>
                      <button type="submit" name="action" value='Entrou no Aquartelamento' class="btn btn-primary" style="width: 140px;">
                        ENTROU
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="col-sm-12 col-md-6">
            <form id="validation" action="visitantes3.php" method="post">
              <div class="panel"><?php render_cabecalho_painel('LANÇAR SAÍDA DE VISITANTE OU MILITAR DE OUTRA OM:', 'fa fa-street-view', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-md-12 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Selecione o Visitante ou Militar de outra OM e Veículo na lista:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-street-view"></i> <i class="fa fa-car" aria-hidden="true"></i></span>
                          <select name="tkvisit" class="form-control select" style="width: 100%" required="Preenchimento obrigatório">
                            <?php $consulta = $pdo->prepare("SELECT visitante.id, visitante.nomecompleto, visitante.tipo, visitante.idveiculo, visitante.cracha, veiculo.placa, 
                              veiculo.marca, veiculo.modelo, veiculo.tipo AS veiculo_tipo FROM visitante LEFT JOIN veiculo ON (visitante.idveiculo = veiculo.id) 
                              WHERE visitante.situacao = '1'");
                            $consulta->execute();
                            echo ("<option></option>");
                            echo ("<optgroup label='Visitante ou de Militar de outra OM e Veículo'>");
                            while ($reg = $consulta->fetch(PDO::FETCH_ASSOC)) :
                              if ($reg['idveiculo'] == 0) { echo ("<option value=" . base64_encode($reg['id']) . ">" . $reg['nomecompleto'] . " (" . $reg['tipo'] . ") (Nenhum Veículo) (" . $reg['cracha'] . ")</option>");
                              } else { echo ("<option value=" . base64_encode($reg['id']) . ">" . $reg['nomecompleto'] . " (" . $reg['tipo'] . ") (" . $reg['placa'] . " - " . $reg['modelo']
                                  . " - " . $reg['marca'] . " - " . $reg['veiculo_tipo'] . ") (" . $reg['cracha'] . ")</option>"); }
                            endwhile;
                            echo ("</optgroup>"); ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 mb-sm"><?php render_hora_field('hora', true, 'Hora saída:', 'now') ?></div>
                    <div class="col-md-6 mb-sm"><?php render_data_field('data', true, 'Data saída:', 'now'); ?></div>
                    <div class="col-md-12">
                      <hr>
                      <button type="submit" name="action" value='Saiu do Aquartelamento' class="btn btn-darker-1" style="width: 140px;">SAIU</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="col-sm-12 col-md-12"></div>
          <div class="col-sm-12 col-md-6"><?php render_form_cadastro_visitante('../guarda/cad_visitantes.php') ?></div>
          <div class="col-sm-12 col-md-6">
            <form id="validation" action="cad_veiculos.php" method="post">
              <div class="panel"><?php render_cabecalho_painel('CADASTRAR VEÍCULO DE VISITANTE OU DE MILITAR DE OUTRA OM:', 'fas fa-plus', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-md-6 mb-sm"><?php render_tipo_veic_select('tipo');    ?></div>
                    <div class="col-md-6 mb-sm"><?php render_marca_veic_select('marca');  ?></div>
                    <div class="col-md-6 mb-sm"><?php render_cor_veic_select('cor');      ?></div>
                    <div class="col-md-6 mb-sm"><?php render_placa_veic_field('placa');   ?></div>
                    <div class="col-md-6 mb-sm"><?php render_modelo_veic_field('modelo'); ?></div>
                    <div class="col-md-12">
                      <hr>
                      <button type="submit" name="action" value='Saiu APÓS o expediente' class="btn btn-warning" style="width: 140px;">CADASTRAR</button>
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
  <script>
    function buscarVeic(idvis) {
        let veic = 0;
        $.get('ajax_ult_veic_visitante.php?id_visitante=' + idvis, function (data, status) {
            veic = data.replaceAll('=', '');
            let aaaa = $('#veic-'+veic).html();
            console.log('aaaa: '+aaaa);
            $('#veic').val(data);
            $('#select2-veic-container').text(aaaa);

        });

    }
  </script>
</body>

</html>