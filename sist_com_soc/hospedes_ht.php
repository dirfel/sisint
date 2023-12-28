<?php

// importar arquivos necessários para executar o código
include "../recursos/models/conexao.php";
require "../recursos/models/versession.php";
if ($_SESSION['nivel_com_soc'] == "Sem Acesso") {
    $msgerro = base64_encode('Usuário não possui acesso!');
    header('Location: ../sistemas/index.php?token=' . $msgerro);
    exit();
}
// calcula quantos dias a frente serão carregados
$max_days = 7;
if(isset($_GET['max_days'])) {
    $max_days = $_GET['max_days'];
}
$dias_antes = 1;
if(isset($_GET['dias_antes'])) {
    $dias_antes = $_GET['dias_antes'];
}

// conectar os banco de dados necessários
$pdo1 = conectar("membros");
$pdo2 = conectar("guarda");
$pdo3 = conectar("sistcomsoc");
$cadastros;
// obter da tabela usuarios o id, nome completo e idpgrad dos militares da OM
$consulta1 = $pdo1->prepare('SELECT id, nomecompleto, idpgrad, nomeguerra from usuarios order by idpgrad');
$consulta1->execute();
$reg1 = $consulta1->fetchAll(PDO::FETCH_ASSOC);

// obter da tabela visitante o id, nome completo e tipo dos visitantes e militades de outra OM
$consulta2 = $pdo2->prepare('SELECT id, nomecompleto, tipo from visitante order by tipo');
$consulta2->execute();
$reg2 = $consulta2->fetchAll(PDO::FETCH_ASSOC);

// obter a lista de veículos
$consulta2 = $pdo2->prepare('SELECT * FROM veiculo');
$consulta2->execute();
$veics = $consulta2->fetchAll(PDO::FETCH_ASSOC);

?>
<!doctype html>
<html lang="pt-BR" class="fixed">
    
    <head><?php include '../recursos/views/cabecalho.php'; ?></head>
    <body>
      <div class="wrap">
        <div class="page-header"><?php render_painel_usu('SERVIÇOS COM SOC', $_SESSION['nivel_com_soc']); ?></div>
    <div class="page-body">
      <div class="left-sidebar">
          <?php include 'menu_opc.php'; ?>
      </div>
      <div class="content">
      <?php render_content_header('Registro e Hóspedes', 'fa fa-home'); ?>
        <div class="row animated zoomInDown">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-8">
          <span id="edit-reserva"></span>
          <form id="validation" action="edit_reserva.php?id=0" method="post">
              <div class="panel"><?php render_cabecalho_painel('RESERVAR QUARTO:', 'fa fa-street-view', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-md-9 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Selecione o hóspede na lista:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-street-view"></i></span>
                          <select name="id_hospede" class="form-control" style="width: 100%" required="Preenchimento obrigatório" tabindex="-1" aria-hidden="true">
                          <optgroup label="Selecione um militar">
                            <option></option>
                            <?php foreach($reg1 as $chave) { ?>
                            <option value="<?= $chave["id"]?>">
                                <?= getPGrad($chave["idpgrad"])?> <?=$chave["nomecompleto"]?> (<?=$chave["nomeguerra"]?>)
                            </option>
                            <?php } 
                            foreach($reg2 as $chave) { ?>
                            <option value="-<?= $chave["id"]?>">
                                <?=$chave["nomecompleto"]?> (<?=$chave["tipo"]?>)
                            </option>
                            <?php } ?>
                          </optgroup>  
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 mb-sm"><?php render_cpf_input('cpf') ?></div>
                    <div class="col-md-3 mb-sm"><?php render_data_field('data_checkin', true, 'Data check in:', null); ?></div>
                    <div class="col-md-3 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Hora check in:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                          <input type="time" class="form-control time" name="hora_checkin" autocomplete="off" pattern="^\d{2}:\d{2}$">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 mb-sm"><?php render_data_field('data_checkout', true, 'Data check out:', null); ?></div>
                    <div class="col-md-3 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Hora check out:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                          <input type="time" class="form-control time" name="hora_checkout" autocomplete="off" pattern="^\d{2}:\d{2}$">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12 mb-sm"></div>
                    <div class="col-md-4 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Selecione o Veículo na lista:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-car"></i></span>
                          <select name="veiculo_id"  class="form-control select select2-hidden-accessible3" style="width: 100%" required="Preenchimento obrigatório" tabindex="-1" aria-hidden="true">
                            <option></option>
                            <optgroup label="Veículo do Visitante ou do Militar de outra OM">
                                <option value="0" selected>Será cadastrado após</option>
                            <?php foreach($veics as $chave) { ?>
                            <option value="<?= $chave["id"]?>">
                                <?= $chave["placa"]?> (<?=$chave["marca"]?> <?= $chave["modelo"]?> <?= $chave["cor"]?>)
                              </option>
                            <?php } ?>
                            </optgroup>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4 mb-sm"><?php render_custom_input('Motivo da reserva:', 'motivo', 'motivo_reserva', '', 80, 'Motivo', true, true, 'fas fa-compass') ?></div>
                    <div class="col-md-4 mb-sm"><?php render_custom_input('OM: (Obrigatório se militar da ativa)', 'om', 'om', '', 50, 'om', false, true, 'fas fa-compass') ?></div>
                    <div class="col-md-2 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Acompanhantes:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fas fa-users"></i></span>
                          <input type="number" class="form-control" name="acompanhantes" value="0"  required="Preenchimento obrigatório">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Quarto:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fas fa-hotel"></i></span>
                          <select name="quarto"  class="form-control select select2-hidden-accessible2" style="width: 100%" required="Preenchimento obrigatório" tabindex="-1" aria-hidden="true">
                            <option></option>
                            <optgroup label="Selecione">
                                <option value="HT1">HT-1</option>
                                <option value="HT2">HT-2</option>
                                <option value="HT3">HT-3</option>
                            </optgroup>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Grupo de tarifa:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fas fa-id-card-alt"></i></span>
                          <select name="gp_tarifa"  class="form-control select select2-hidden-accessible4" style="width: 100%" required="Preenchimento obrigatório" tabindex="-1" aria-hidden="true">
                            <option></option>
                            <optgroup label="Selecione">
                                <option value="Of Gen">Of Gen</option>
                                <option value="Of Sup">Of Sup</option>
                                <option value="Cap Ten">Cap/Ten</option>
                                <option value="Sten Sgt">Sten/Sgt</option>
                                <option value="Civil">Civil</option>
                                <option value="Cortesia">Cortesia</option>
                            </optgroup>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Adicional %:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fas fa-users"></i></span>
                          <input type="number" class="form-control" name="adicional_tarifa" value="0"  required="Preenchimento obrigatório">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <hr>
                      <button type="submit" name="action" value="Registrar reserva" class="btn btn-primary" style="width: 140px;">RESERVAR</button>
                      <br><p>Caso não encontre o hóspede ou visitante na lista, é necessario cadastrar abaixo.</p>
                    </div>
                  </div>
                </div>
              </div>
          </form>
          <!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
          <?php render_form_cadastro_visitante('../guarda/cad_visitantes.php'); ?>
          </div>

          <div class="col-sm-12 col-md-4">
          <form id="validation" action="test.php" method="post">
              <div class="panel"><?php render_cabecalho_painel('AGENDA:', 'fa fa-calendar', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-md-12 mb-sm">
                    <table class="table text-center">
                      <thead><tr><th>Dia</th><th>HT1</th><th>HT2</th><th>HT3</th></tr></thead>
                      <tbody>
                        <?php
                            // essa consulta retornará todos os hospedes que a data de checkout já passou
                            $estemes = date('m/Y');
                            $messeguinte = date('m/Y', strtotime('+1 month'));
                            $consulta3 = $pdo3->prepare('SELECT * FROM reservas ORDER BY id DESC');
                            $consulta3->execute();
                            $reg3 = $consulta3->fetchAll(PDO::FETCH_ASSOC);
                            // print_r($reg3);
                        // gero uma linha pra cada dia
                        for($i=-1*$dias_antes;$i<$max_days;$i++){
                            $diaria = date("d/m", time() + 86400 * $i);
                            if($i == 0) {
                              $diaria = '<span class="btn">' . $diaria .'</span>';
                            }
                            $diaria2 = date("Ymd", time() + 86400 * $i);
                            // para cada elemento em reg 3 verifico se checkin <= hoje e checkout >= hoje
                            $ht1 = '';
                            $ht2 = '';
                            $ht3 = '';
                            $link1 = '';
                            $link2 = '';
                            $link3 = '';
                            $onclick1 = '';
                            $onclick2 = '';
                            $onclick3 = '';
                            $title1 = 'title="Sem reserva"';
                            $title2 = 'title="Sem reserva"';
                            $title3 = 'title="Sem reserva"';
                            $a = '';
                            foreach($reg3 as $registro) {
                                $prep_data_checkin = $registro['data_checkin'][6].$registro['data_checkin'][7].$registro['data_checkin'][8].$registro['data_checkin'][9].$registro['data_checkin'][3].$registro['data_checkin'][4].$registro['data_checkin'][0].$registro['data_checkin'][1];
                                $prep_data_checkout = $registro['data_checkout'][6].$registro['data_checkout'][7].$registro['data_checkout'][8].$registro['data_checkout'][9].$registro['data_checkout'][3].$registro['data_checkout'][4].$registro['data_checkout'][0].$registro['data_checkout'][1];
                                if(intval($diaria2) < intval($prep_data_checkout) && intval($diaria2) >= intval($prep_data_checkin)) {
                                    if($registro['quarto'] == 'HT1') {
                                        $ht1 = 'btn btn-success';
                                        $onclick1 = 'onclick="editReserva('.$registro['id'].')"';
                                        if($registro['id_hospede'] > 0) {
                                            foreach($reg1 as $chave) {
                                                if($chave['id'] == $registro['id_hospede']) {
                                                    $a = getPGrad($chave["idpgrad"]) . ' '. $chave["nomecompleto"] . ' ('. $chave["nomeguerra"] . ') '. $registro["motivo_reserva"] . ' - ' . $registro["quarto"] . ' - Hora checkin: ' . $registro["hora_checkin"] . ' - Hora checkout: ' . $registro["hora_checkout"];
                                                    break;
                                                }
                                            }
                                        } else {
                                            foreach($reg2 as $chave) {
                                                if($chave['id'] == str_replace('-', '', $registro['id_hospede'])) {
                                                    $a = $chave["nomecompleto"]. ' (' . $chave["tipo"] . ') '. $registro["motivo_reserva"] . ' - ' . $registro["quarto"] . ' - Hora checkin: ' . $registro["hora_checkin"] . ' - Hora checkout: ' . $registro["hora_checkout"];
                                                    break;
                                                }
                                            }
                                        }
                                        $title1 = 'title="'. $a .'"';
                                    } else if($registro['quarto'] == 'HT2') {
                                        $ht2 = 'btn btn-success';
                                        $onclick2 = 'onclick="editReserva('.$registro['id'].')"';
                                        if($registro['id_hospede'] > 0) {
                                            foreach($reg1 as $chave) {
                                                if($chave['id'] == $registro['id_hospede']) {
                                                    $a = getPGrad($chave["idpgrad"]) . ' '. $chave["nomecompleto"] . ' ('. $chave["nomeguerra"] . ') '. $registro["motivo_reserva"] . ' - ' . $registro["quarto"] . ' - Hora checkin: ' . $registro["hora_checkin"] . ' - Hora checkout: ' . $registro["hora_checkout"];
                                                    break;
                                                }
                                            }
                                        } else {
                                            foreach($reg2 as $chave) {
                                                if($chave['id'] == str_replace('-', '', $registro['id_hospede'])) {
                                                    $a = $chave["nomecompleto"]. ' (' . $chave["tipo"] . ') '. $registro["motivo_reserva"] . ' - ' . $registro["quarto"] . ' - Hora checkin: ' . $registro["hora_checkin"] . ' - Hora checkout: ' . $registro["hora_checkout"];
                                                    break;
                                                }
                                            }
                                        }
                                        $title2 = 'title="'. $a .'"';
                                    } else if($registro['quarto'] == 'HT3') {
                                        $ht3 = 'btn btn-success';
                                        $onclick3 = 'onclick="editReserva('.$registro['id'].')"';
                                        if($registro['id_hospede'] > 0) {
                                            foreach($reg1 as $chave) {
                                                if($chave['id'] == $registro['id_hospede']) {
                                                    $a = getPGrad($chave["idpgrad"]) . ' '. $chave["nomecompleto"] . ' ('. $chave["nomeguerra"] . ') '. $registro["motivo_reserva"] . ' - ' . $registro["quarto"] . ' - Hora checkin: ' . $registro["hora_checkin"] . ' - Hora checkout: ' . $registro["hora_checkout"];
                                                    break;
                                                }
                                            }
                                        } else {
                                            foreach($reg2 as $chave) {
                                                if($chave['id'] == str_replace('-', '', $registro['id_hospede'])) {
                                                    $a = $chave["nomecompleto"]. ' (' . $chave["tipo"] . ') '. $registro["motivo_reserva"] . ' - ' . $registro["quarto"] . ' - Hora checkin: ' . $registro["hora_checkin"] . ' - Hora checkout: ' . $registro["hora_checkout"];
                                                    break;
                                                }
                                            }
                                        }
                                        $title3 = 'title="'. $a .'"';
                                    }
                                }
                            }           
                            echo '<tr><th>' . $diaria . '</th>';
                                echo '<td><a '.$link1.' data-toggle="tooltip" data-placement="top" '.$onclick1.' class="'.$ht1.'" '. $title1 .'>HT1</a></td>';
                                echo '<td><a '.$link2.' data-toggle="tooltip" data-placement="top" '.$onclick2.' class="'.$ht2.'" '. $title2 .'>HT2</a></td>';
                                echo '<td><a '.$link3.' data-toggle="tooltip" data-placement="top" '.$onclick3.' class="'.$ht3.'" '. $title3 .'>HT3</a></td>';
                            echo '</tr>';
                        } ?>
                        
                      <tbody>
                    </table>
                    <a href="hospedes_ht.php?max_days=<?= (intval($max_days) + 30) ?>" class="btn btn-primary">Carregar mais 30 dias</a>
                    <a href="hospedes_ht.php?dias_antes=<?= (intval($dias_antes) + 30) ?>" class="btn btn-primary">Carregar 30 dias atrás</a>
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
    async function editReserva(reservaId) {
      let result = await $.ajax({
        url: './ajax_edit_reserva.php?id=' + reservaId,
        type: 'GET',
        success: function(res) {
          return res[0];
        }
      });
      let card = `
            <form id="validation2" action="edit_reserva.php?id=`+reservaId+`" method="post">
              <div class="panel"><?php render_cabecalho_painel('EDITAR RESERVA:', 'fa fa-street-view', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-md-9 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Selecione o hóspede na lista:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-street-view"></i></span>
                          <select name="id_hospede"  class="form-control" style="width: 100%" required="Preenchimento obrigatório" tabindex="-1" aria-hidden="true">
                          <optgroup label="Selecione um militar">
                            <option></option>
                            <?php foreach($reg1 as $chave) { ?>
                            <option value="<?= $chave["id"]?>"`+(<?=$chave['id']?> == result['id_hospede'] ? 'selected' : '')+`><?= getPGrad($chave["idpgrad"])?> <?=$chave["nomeguerra"]?></option>
                            <?php } 
                            foreach($reg2 as $chave) { ?>
                            <option value="-<?= $chave["id"]?>"`+(-<?=$chave['id']?> == result['id_hospede'] ? 'selected' : '')+`>
                                <?=$chave["nomecompleto"]?> (<?=$chave["tipo"]?>)
                            </option>
                            <?php } ?>
                          </optgroup>  
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">CPF:</label>
                        <input id="frmcpf" type="text" onblur="validarCPF(this.value);" class="form-control cpf" name="cpf" value="`+atob(result['cpf'])+`" placeholder="CPF">
                      </div>
                    </div>
                    <div class="col-md-3 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Data check in:</label>
                        <div class="input-group date">
                          <span class="input-group-addon date-time-color"><i class="fa fa-calendar"></i></span>
                          <input type="text" class="form-control" name="data_checkin" autocomplete="off" value="`+result['data_checkin']+`" required="Preenchimento obrigatório">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Hora check in:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                          <input type="time" class="form-control time" name="hora_checkin" autocomplete="off" value="`+result['hora_checkin']+`" pattern="^\d{2}:\d{2}$">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Data check out:</label>
                        <div class="input-group date">
                          <span class="input-group-addon date-time-color"><i class="fa fa-calendar"></i></span>
                          <input type="text" class="form-control" name="data_checkout" autocomplete="off" value="`+result['data_checkout']+`" required="Preenchimento obrigatório">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Hora check out:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                          <input type="time" class="form-control time" name="hora_checkout" autocomplete="off" value="`+result['hora_checkout']+`" pattern="^\d{2}:\d{2}$">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12 mb-sm"></div>
                    <div class="col-md-4 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Selecione o Veículo na lista:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-car"></i></span>
                          <select name="veiculo_id" class="form-control" style="width: 100%" required="Preenchimento obrigatório" tabindex="-1" aria-hidden="true">
                            <option></option>
                            <optgroup label="Veículo do Visitante ou do Militar de outra OM">
                                <option value="0" `+(result['veiculo_id'] == 0 ? 'selected' : '')+`>Será cadastrado após</option>
                            <?php foreach($veics as $chave) { ?>
                            <option value="<?= $chave["id"]?>" `+(<?=$chave['id']?> == result['veiculo_id'] ? 'selected' : '')+`>
                                <?= $chave["placa"]?> (<?=$chave["marca"]?> <?= $chave["modelo"]?> <?= $chave["cor"]?>)
                                </option>
                            <?php } ?>
                            </optgroup>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Motivo da reserva:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fas fa-compass"></i></span>
                          <input type="text" class="form-control text-uppercase maxLength" name="motivo_reserva" placeholder="Motivo" maxlength="80" value="`+result['motivo_reserva']+`" required="Preenchimento obrigatório">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">OM: (Obrigatório se militar da ativa)</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fas fa-compass"></i></span>
                          <input type="text" class="form-control text-uppercase maxLength" name="om" placeholder="OM" value="`+result['om']+`" maxlength="50">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-2 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Acompanhantes:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fas fa-users"></i></span>
                          <input type="number" class="form-control" name="acompanhantes" value="`+result['acompanhantes']+`"  required="Preenchimento obrigatório">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Quarto:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fas fa-hotel"></i></span>
                          <select name="quarto" class="form-control" style="width: 100%" required="Preenchimento obrigatório" tabindex="-1" aria-hidden="true">
                            <option></option>
                            <optgroup label="Selecione">
                                <option value="HT1" `+(result['quarto'] == 'HT1' ? 'selected' : '')+`>HT-1</option>
                                <option value="HT2" `+(result['quarto'] == 'HT2' ? 'selected' : '')+`>HT-2</option>
                                <option value="HT3" `+(result['quarto'] == 'HT3' ? 'selected' : '')+`>HT-3</option>
                            </optgroup>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Grupo de tarifa:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fas fa-id-card-alt"></i></span>
                          <select name="gp_tarifa" class="form-control" style="width: 100%" required="Preenchimento obrigatório" tabindex="-1" aria-hidden="true">
                            <option></option>
                            <optgroup label="Selecione">
                                <option value="Of Gen" `+(result['gp_tarifa'] == 'Of Gen' ? 'selected' : '')+`>Of Gen</option>
                                <option value="Of Sup" `+(result['gp_tarifa'] == 'Of Sup' ? 'selected' : '')+`>Of Sup</option>
                                <option value="Cap Ten" `+(result['gp_tarifa'] == 'Cap Ten' ? 'selected' : '')+`>Cap/Ten</option>
                                <option value="Sten Sgt" `+(result['gp_tarifa'] == 'Sten Sgt' ? 'selected' : '')+`>Sten/Sgt</option>
                                <option value="Civil" `+(result['gp_tarifa'] == 'Civil' ? 'selected' : '')+`>Civil</option>
                                <option value="Cortesia" `+(result['gp_tarifa'] == 'Cortesia' ? 'selected' : '')+`>Cortesia</option>
                            </optgroup>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Adicional %:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fas fa-users"></i></span>
                          <input type="number" class="form-control" name="adicional_tarifa"  value="`+result['adicional_tarifa']+`" required="Preenchimento obrigatório">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <hr>
                      <button type="submit" name="action" value="Editar" class="btn btn-primary" style="width: 160px;">
                        EDITAR RESERVA
                      </button>
                      <button class="btn btn-primary" style="width: 160px;">
                        <a class="text-dark" href="edit_reserva.php?act=del&id=`+reservaId+`" onclick="return confirm('Deseja realmente apagar essa reserva? Essa ação é irreversível!');">EXCLUIR RESERVA</a>
                      </button>
                      <br>
                      <p>Caso não encontre o veículo na lista, é necessario cadastrar no <a target="_blank" href="../guarda/index.php">sistema da Guarda</a> e recarregar esta página</p>
                    </div>
                  </div>
                </div>
              </div>
          </form>
      `;
      $('#edit-reserva').append(card);
    }
  </script>
</body>
</html>
