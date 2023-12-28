<?php

require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if (!isset($_POST['action'])) {
  header('Location: index.php');
  exit();
}

require "livroPartes_conf2.php";

$dataBiConvertida = date_converter2($biData);
$dataLivroPlus = date('Y-m-d', strtotime("+1 day", strtotime($dataLivro)));
$dataLivroInicio = strftime('%d de %B de %Y', strtotime($dataLivro));
$dataLivroFinal = strftime('%d de %B de %Y', strtotime($dataLivroPlus));

$horaAtual = date('H:i');

$auxI = 0;

if ($dataAtual < $dataLivroPlus or ($dataAtual == $dataLivroPlus and $horaAtual < '07:00') or count($consulta_reg) == 0) {
  $finalizarBtn = 'true';
} else {
  $finalizarBtn = 'false';
}

if (!$anexos) {
  $anexos = "01 (um) documento com Pronto de Armamento, Pronto de Viaturas, Pronto do Paiol, Pronto da Conf Reg e Pronto do HT, 01 (um) lacre plástico rompido, 01 (uma) senha e contra-senha usada, 01 (um) Pernoite e os talões das viaturas.";
}

$consultaTen = consultaMilitarSelection(6);

?>
<!doctype html>
<html lang="pt-BR" class="fixed left-sidebar-collapsed">

<head>
  <?php include '../recursos/views/cabecalho.php'; ?>
</head>

<body onload="leiturasEnergiaAgua()">
  <div class="wrap">
    <div class="page-header">
    <?php render_painel_usu('GUARDA', $_SESSION['nivel_guarda']); ?>
    </div>
    <div class="page-body">
      <div class="left-sidebar">
        <?php include 'menu_opc.php'; ?>
      </div>
      <div class="content">
      <?php render_content_header('Lançamento e Edição do Livro de Partes do Oficial-de-Dia', 'fa fa-pencil-alt'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <form id="validation" action="livroPartesEditar_conf2.php?dataLivro=<?= $dataLivroEncode ?>" method="post">
            <div class="col-sm-12">
              <div class="panel"><?php render_cabecalho_painel('LANÇAMENTO E EDIÇÃO DO LIVRO DE PARTES: De: '.$dataLivroInicio.' para '.$dataLivroFinal, 'fas fa-pensil-alt', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-sm-12 col-md-6 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">1. Recebimento do Serviço:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-group"></i></span>
                          <select id="idOfDiaAnterior" name="idOfDiaAnterior" class="form-control select" placeholder="Selecione uma Opção" style="width: 100%" required>
                            <option></option>
                            <optgroup label='Militar da OM'>
                              <?php for ($i = 0; $i < count($consultaTen); $i++) {
                                echo ("<option value=" . base64_encode($consultaTen[$i]['id']) . ">" . getPGrad($consultaTen[$i]['idpgrad']) . " - " . $consultaTen[$i]['nomecompleto'] . " (" . $consultaTen[$i]['nomeguerra'] . ")</option>");
                              } ?>
                            </optgroup>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12 col-md-6 mb-sm">
                      <div class="col-sm-12 p-none">
                        <label for="form-group" class="control-label">2. Pessoal de Serviço:</label>
                      </div>
                      <div class="col-sm-12 col-md-6 ph-xs">
                        <div class="form-group">
                          <div class="input-group">
                            <span class="input-group-addon"><i class="fas fa-sticky-note"></i></span>
                            <input type="text" class="form-control bi maxLength" placeholder="Número do Boletim Interno" name="bi" value="<?= $bi ?>" maxlength="3" required>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-12 col-md-6 ph-xs"><?php render_data_field('biData', true, 'Data do Boletim Interno', $dataBiConvertida); ?></div>
                    </div>
                    <div class="col-sm-12 p-none">
                      <div class="col-sm-12 col-md-6 col-lg-5 mb-sm">
                        <div class="col-sm-12 p-none">
                          <label for="form-group" class="control-label">3. Parada Diária:</label>
                        </div>
                        <div class="col-sm-12 p-none">
                          <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label onclick="disable('paradaObs')" id="parada_label2" class="btn btn-primary m-xs <?php if ($parada <> "C") echo "active" ?>">
                              <input type="radio" name="parada" value="S" id="parada_radio2" <?php if ($parada <> "C" or $parada <> "N") echo "checked" ?>> Sem Alteração
                            </label>
                            <label onclick="enable('paradaObs')" id="parada_label3" class="btn btn-primary m-xs <?php if ($parada == "C") echo "active" ?>">
                              <input type="radio" name="parada" value="C" id="parada_radio3" <?php if ($parada == "C") echo "checked" ?>> Com Alteração
                            </label>
                            <label onclick="disable('paradaObs')" id="parada_label1" class="btn btn-primary m-xs <?php if ($parada == "N") echo "active" ?>">
                              <input type="radio" name="parada" value="N" id="parada_radio1" <?php if ($parada == "N") echo "checked" ?>> Não Realizada
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-12 col-md-6 col-lg-7 mb-sm">
                        <div class="form-group">
                          <label for="form-group" class="control-label">3.1. Observações da Parada Diária:</label>
                          <div class="input-group">
                            <span class="input-group-addon"><i class="fas fa-pen-alt"></i></span>
                            <textarea style="resize: vertical; max-height:350px; min-height:35px;" name="paradaObs" id="paradaObs" class="form-control" rows="2" placeholder="Observações" <?php if ($parada <> "C") echo "disabled" ?>><?= $paradaObs ?></textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12 p-none">
                      <div class="col-sm-12 mb-sm">
                        <div class="col-sm-12 p-none">
                          <label for="form-group" class="control-label">4. Punidos:</label>
                        </div>
                        <div class="col-sm-12 p-none">
                          <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label onclick="disable('punidosObs')" id="punidos_label1" class="btn btn-primary m-xs <?php if ($punidos <> "C") echo "active" ?>">
                              <input type="radio" name="punidos" value="S" id="punidos_radio1" <?php if ($punidos <> "C") echo "checked" ?>> Sem Alteração
                            </label>
                            <label onclick="enable('punidosObs')" id="punidos_label2" class="btn btn-primary m-xs <?php if ($punidos == "C") echo "active" ?>">
                              <input type="radio" name="punidos" value="C" id="punidos_radio2" <?php if ($punidos == "C") echo "checked" ?>> Com Alteração
                            </label>
                          </div>
                          <input id="punidos_btn" class="btn btn-darker-1 m-xs" type="button" value="Adicionar Campo" onclick="addCampos()">
                          <input id="punidos_btn2" class="btn btn-darker-1 m-xs" type="button" value="Remover Campo" onclick="removerCampo()" <?= (count($idPunido) > 0) ? "" : "disabled" ?>>
                        </div>
                      </div>
                      <div class="col-sm-12 mb-sm p-none">
                        <div class="col-sm-12">
                          <label for="form-group" class="control-label">4.1. Tabela de Punidos:</label>
                        </div>
                        <div class="col-sm-12">
                          <div class="panel-content p-none">
                            <table id="tPunidos" class="tabela table table-striped table-hover responsive nowrap" width="100%">
                              <thead>
                                <tr>
                                  <td align='center' valign='middle' width="30%"><font size=2><i class="fa fa-group"></i><strong> Punido</strong></font></td>
                                  <td align='center' valign='middle' width="19%"><font size=2><i class="fas fa-hand-paper"></i><strong> Punição</strong></font></td>
                                  <td align='center' valign='middle' width="14%"><font size=2><i class="fa fa-calendar"></i><strong> Início</strong></font></td>
                                  <td align='center' valign='middle' width="14%"><font size=2><i class="fa fa-calendar"></i><strong> Término</strong></font></td>
                                  <td align='center' valign='middle' width="9%"><font size=2><i class="fas fa-sticky-note"></i><strong> BI</strong></font></td>
                                  <td align='center' valign='middle' width="14%"><font size=2><i class="fa fa-calendar"></i><strong> Data BI</strong></font></td>
                                </tr>
                              </thead>
                              <tbody id="tPunidosBody">
                                <?php for ($i = 0; $i < count($consultaRegPunidos); $i++) { ?>
                                  <tr>
                                    <td align='center' valign='middle'><input style="width: 100%" type="text" class="form-control" name="idPunido" value="<?= consultaMilitar($consultaRegPunidos[$i]['idpunido']) ?>" autocomplete="off" readonly></td>
                                    <td align='center' valign='middle'><input style="width: 100%" type="text" class="form-control" name="idPunicao" value="<?= $consultaRegPunidos[$i]['punicao'] ?>" autocomplete="off" readonly></td>
                                    <td align='center' valign='middle'><input style="width: 100%" type="text" class="form-control" name="data_inicio" value="<?= date_converter2($consultaRegPunidos[$i]['data_inicio']) ?>" autocomplete="off" readonly></td>
                                    <td align='center' valign='middle'><input style="width: 100%" type="text" class="form-control" name="data_termino" value="<?= date_converter2($consultaRegPunidos[$i]['data_termino']) ?>" autocomplete="off" readonly></td>
                                    <td align='center' valign='middle'><input style="width: 100%" type="text" class="form-control" name="p_bi" value="<?= $consultaRegPunidos[$i]['p_bi'] ?>" autocomplete="off" readonly></td>
                                    <td align='center' valign='middle'><input style="width: 100%" type="text" class="form-control" name="p_bi_data" value="<?= date_converter2($consultaRegPunidos[$i]['p_bi_data']) ?>" autocomplete="off" readonly></td>
                                  </tr>
                                <?php }  ?>
                                <?php for ($ii = 0; $ii < 10; $ii++) { ?>
                                  <tr id="tPunidosTr_<?= $ii ?>" <?= (count($idPunido) <= $ii) ? "hidden" : ""; ?>>
                                    <td>
                                      <select id="idPunido_<?= $ii ?>" name="idPunido_<?= $ii ?>" class="form-control select" placeholder="Selecione uma Opção" style="width: 100%" <?= (count($idPunido) <= $ii) ? "required disabled" : ""; ?>>
                                        <option></option>
                                        <optgroup label='Militar da OM'>
                                          <?php for ($i = 0; $i < count($consultaTen); $i++) {
                                            echo ("<option value=" . base64_encode($consultaTen[$i]['id']) . ">" . getPGrad($consultaTen[$i]['idpgrad']) . " - " . $consultaTen[$i]['nomecompleto'] . " (" . $consultaTen[$i]['nomeguerra'] . ")</option>");
                                          } ?>
                                        </optgroup>
                                      </select>
                                    </td>
                                    <td>
                                      <select id="idPunicao_<?= $ii ?>" name="idPunicao_<?= $ii ?>" class="form-control select" placeholder="Selecione uma Opção" style="width: 100%" <?= (count($idPunido) <= $ii) ? "required disabled" : ""; ?>>
                                        <option></option>
                                        <optgroup label='Punições'>
                                          <option value='Impedimento disciplinar'>Impedimento disciplinar</option>
                                          <option value='Repreensão'>Repreensão</option>
                                          <option value='Detenção disciplinar'>Detenção disciplinar</option>
                                          <option value='Prisão disciplinar'>Prisão disciplinar</option>
                                        </optgroup>
                                      </select>
                                    </td>
                                    <td align='center' valign='middle'>
                                      <div class="input-group date">
                                        <span class="input-group-addon date-time-color"><i class="fa fa-calendar"></i></span>
                                        <input id="data_inicio_<?= $ii ?>" type="text" class="form-control" value="<?= date_converter2($data_inicio[$ii]) ?>" name="data_inicio_<?= $ii ?>" placeholder="Data I..." autocomplete="off" pattern="^\d{2}/\d{2}/\d{4}$" <?= (count($idPunido) <= $ii) ? "required disabled" : ""; ?>>
                                      </div>
                                    </td>
                                    <td align='center' valign='middle'>
                                      <div class="input-group date">
                                        <span class="input-group-addon date-time-color"><i class="fa fa-calendar"></i></span>
                                        <input id="data_termino_<?= $ii ?>" type="text" class="form-control" value="<?= date_converter2($data_termino[$ii]) ?>" name="data_termino_<?= $ii ?>" placeholder="Data T..." autocomplete="off" pattern="^\d{2}/\d{2}/\d{4}$" <?= (count($idPunido) <= $ii) ? "required disabled" : ""; ?>>
                                      </div>
                                    </td>
                                    <td align='center' valign='middle'>
                                      <input id="p_bi_<?= $ii ?>" style="width: 100%" type="text" class="form-control bi maxLength" value="<?= $p_bi[$ii] ?>" placeholder="Nr BI" name="p_bi_<?= $ii ?>" maxlength="3" <?= (count($idPunido) <= $ii) ? "required disabled" : ""; ?>>
                                    </td>
                                    <td align='center' valign='middle'>
                                      <div class="input-group date">
                                        <span class="input-group-addon date-time-color"><i class="fa fa-calendar"></i></span>
                                        <input id="p_bi_data_<?= $ii ?>" type="text" class="form-control" value="<?= date_converter2($p_bi_data[$ii]) ?>" name="p_bi_data_<?= $ii ?>" placeholder="Data BI" autocomplete="off" pattern="^\d{2}/\d{2}/\d{4}$" <?= (count($idPunido) <= $ii) ? "required disabled" : ""; ?>>
                                      </div>
                                    </td>
                                  </tr>
                                <?php }  ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12 p-none">
                      <div class="col-sm-12 col-md-6 col-lg-5 mb-sm">
                        <div class="col-sm-12 p-none">
                          <label for="form-group" class="control-label">5. Instalações:</label>
                        </div>
                        <div class="col-sm-12 p-none">
                          <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label onclick="disable('instalacoesObs')" id="instalacoes_label1" class="btn btn-primary m-xs <?php if ($instalacoes <> "C") echo "active" ?>">
                              <input type="radio" name="instalacoes" value="S" id="instalacoes_radio1" <?php if ($instalacoes <> "C") echo "checked" ?>> Sem Alteração
                            </label>
                            <label onclick="enable('instalacoesObs')" id="instalacoes_label2" class="btn btn-primary m-xs <?php if ($instalacoes == "C") echo "active" ?>">
                              <input type="radio" name="instalacoes" value="C" id="instalacoes_radio2" <?php if ($instalacoes == "C") echo "checked" ?>> Com Alteração
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-12 col-md-6 col-lg-7 mb-sm">
                        <div class="form-group">
                          <label for="form-group" class="control-label">5.1. Observações de Instalações:</label>
                          <div class="input-group">
                            <span class="input-group-addon"><i class="fas fa-pen-alt"></i></span>
                            <textarea style="resize: vertical; max-height:350px; min-height:35px;" name="instalacoesObs" id="instalacoesObs" class="form-control" rows="2" placeholder="Observações" <?php if ($instalacoes <> "C") echo "disabled" ?>><?= $instalacoesObs ?></textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12 p-none">
                      <div class="col-sm-12 col-md-6 col-lg-5 mb-sm">
                        <div class="col-sm-12 p-none">
                          <label for="form-group" class="control-label">6. Material Carga e Relacionado do Sv:</label>
                        </div>
                        <div class="col-sm-12 p-none">
                          <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label onclick="disable('cargaObs')" id="carga_label1" class="btn btn-primary m-xs <?php if ($carga <> "C") echo "active" ?>">
                              <input type="radio" name="carga" value="S" id="carga_radio1" <?php if ($carga <> "C") echo "checked" ?>> Sem Alteração
                            </label>
                            <label onclick="enable('cargaObs')" id="carga_label2" class="btn btn-primary m-xs <?php if ($carga == "C") echo "active" ?>">
                              <input type="radio" name="carga" value="C" id="carga_radio2" <?php if ($carga == "C") echo "checked" ?>> Com Alteração
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-12 col-md-6 col-lg-7 mb-sm">
                        <div class="form-group">
                          <label for="form-group" class="control-label">6.1. Observações de Material Carga e Relacionado do Sv:</label>
                          <div class="input-group">
                            <span class="input-group-addon"><i class="fas fa-pen-alt"></i></span>
                            <textarea style="resize: vertical; max-height:350px; min-height:35px;" name="cargaObs" id="cargaObs" class="form-control" rows="2" placeholder="Observações" <?php if ($carga <> "C") echo "disabled" ?>><?= $cargaObs ?></textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12 mb-sm">
                      <div class="col-sm-12 p-none">
                        <label for="form-group" class="control-label">7. Leituras:</label>
                      </div>
                      <div class="col-sm-12 p-none">
                        <div class="panel-content">
                          <table id="responsive-table" class="tabela table table-striped table-hover responsive nowrap" style="max-width: 600px !important">
                            <thead>
                              <tr>
                                <td align='center' valign='middle' width="40%"><font size=2><strong>Tipo da Leitura</strong></font></td>
                                <td align='center' valign='middle'><font size=2><strong>Leitura 06:30 D+1</strong></font></td>
                                <td align='center' valign='middle'><font size=2><strong>Leitura 18:00</strong></font></td>
                                <td align='center' valign='middle'><font size=2><strong>Leitura 13:30</strong></font></td>
                                <td align='center' valign='middle'><font size=2><strong>Anterior</strong></font></td>
                                <td align='center' valign='middle'><font size=2><strong>Consumo do dia</strong></font></td>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td align='center' valign='middle'><font size=2><strong>Energia (Kw/h)</strong></font></td>
                                <td align='center' valign='middle'>
                                  <div class="form-group">
                                    <div class="input-group">
                                      <input type="text" class="form-control leituras energia" name="energiaAtual" id="energiaAtual" value="<?= $energiaAtual ?>" required>
                                    </div>
                                  </div>
                                </td>
                                <td align='center' valign='middle'>
                                  <div class="form-group">
                                    <div class="input-group">
                                      <input type="text" class="form-control leituras energia" name="energia2" id="energia2" value="<?= $energia2 ?>" required>
                                    </div>
                                  </div>
                                </td>
                                <td align='center' valign='middle'>
                                  <div class="form-group">
                                    <div class="input-group">
                                      <input type="text" class="form-control leituras energia" name="energia1" id="energia1" value="<?= $energia1 ?>" required>
                                    </div>
                                  </div>
                                </td>
                                <td align='center' valign='middle'>
                                  <div class="form-group">
                                    <div class="input-group">
                                      <input type="text" class="form-control leituras energia" name="energiaAnterior" id="energiaAnterior" value="<?= $energiaAnterior ?>" required>
                                    </div>
                                  </div>
                                </td>
                                <td align='center' valign='middle'>
                                  <div class="form-group">
                                    <div class="input-group">
                                      <input type="text" class="form-control leituras energia" id="energiaConsumo" name="energiaConsumo" value="" disabled>
                                    </div>
                                  </div>
                                </td>
                              </tr>
                              <tr>
                                <td align='center' valign='middle'><font size=2><strong>Água Interno (m³)</strong></font></td>
                                <td align='center' valign='middle'>
                                  <div class="form-group">
                                    <div class="input-group">
                                      <input type="text" class="form-control leituras agua" name="aguaInternoAtual" id="aguaInternoAtual" value="<?= $aguaInternoAtual ?>" required>
                                    </div>
                                  </div>
                                </td>
                                <td align='center' valign='middle'>
                                  <div class="form-group">
                                    <div class="input-group">
                                      <input disabled type="text" class="form-control leituras agua" name="aguaInterno2" id="aguaInterno2" value="------------" >
                                    </div>
                                  </div>
                                </td>
                                <td align='center' valign='middle'>
                                  <div class="form-group">
                                    <div class="input-group">
                                      <input disabled type="text" class="form-control leituras agua" name="aguaInterno1" id="aguaInterno1" value="------------" >
                                    </div>
                                  </div>
                                </td>
                                <td align='center' valign='middle'>
                                  <div class="form-group">
                                    <div class="input-group">
                                      <input type="text" class="form-control leituras agua" name="aguaInternoAnterior" id="aguaInternoAnterior" value="<?= $aguaInternoAnterior ?>" required>
                                    </div>
                                  </div>
                                </td>
                                <td align='center' valign='middle'>
                                  <div class="form-group">
                                    <div class="input-group">
                                      <input type="text" class="form-control leituras agua" id="aguaInternoConsumo" name="aguaInternoConsumo" value="" disabled>
                                    </div>
                                  </div>
                                </td>
                              </tr>
                              <tr>
                                <td align='center' valign='middle'><font size=2><strong>Água Externo (m³)</strong></font></td>
                                <td align='center' valign='middle'>
                                  <div class="form-group">
                                    <div class="input-group">
                                      <input type="text" class="form-control leituras agua2" name="aguaExternoAtual" id="aguaExternoAtual" value="<?= $aguaExternoAtual ?>" required>
                                    </div>
                                  </div>
                                </td>
                                <td align='center' valign='middle'>
                                  <div class="form-group">
                                    <div class="input-group">
                                      <input disabled type="text" class="form-control leituras agua2" name="aguaExterno2" id="aguaExterno2" value="------------" >
                                    </div>
                                  </div>
                                </td>
                                <td align='center' valign='middle'>
                                  <div class="form-group">
                                    <div class="input-group">
                                      <input disabled type="text" class="form-control leituras agua2" name="aguaExterno1" id="aguaExterno1" value="------------" >
                                    </div>
                                  </div>
                                </td>
                                <td align='center' valign='middle'>
                                  <div class="form-group">
                                    <div class="input-group">
                                      <input type="text" class="form-control leituras agua2" name="aguaExternoAnterior" id="aguaExternoAnterior" value="<?= $aguaExternoAnterior ?>" required>
                                    </div>
                                  </div>
                                </td>
                                <td align='center' valign='middle'>
                                  <div class="form-group">
                                    <div class="input-group">
                                      <input type="text" class="form-control leituras agua2" id="aguaExternoConsumo" name="aguaExternoConsumo" value="" disabled>
                                    </div>
                                  </div>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12 p-none">
                      <div class="col-sm-12 col-md-4 mb-sm">
                        <div class="col-sm-12 p-none">
                          <label for="form-group" class="control-label">8. Rancho:</label>
                        </div>
                        <div class="col-sm-12 p-none">
                          <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label onchange="disableRancho()" id="rancho_label1" class="btn btn-primary m-xs <?php if ($rancho <> "C") echo "active" ?>">
                              <input type="radio" name="rancho" value="S" id="rancho_radio1" <?php if ($rancho <> "C") echo "checked" ?>> Sem Alteração
                            </label>
                            <label onclick="enable('ranchoObs')" id="rancho_label2" class="btn btn-primary m-xs <?php if ($rancho == "C") echo "active" ?>">
                              <input type="radio" name="rancho" value="C" id="rancho_radio2" <?php if ($rancho == "C") echo "checked" ?>> Com Alteração
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-12 col-md-4 mb-sm">
                        <div class="col-sm-12 p-none">
                          <label for="form-group" class="control-label">8.1. Apresentação do Fisc Dia Rancho por início do SV:</label>
                        </div>
                        <div class="col-sm-12 p-none">
                          <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label onchange="disableRancho()" id="ranchoFiscDia_label1" class="btn btn-primary m-xs <?php if ($ranchoFiscDia <> "C") echo "active" ?>">
                              <input type="radio" name="ranchoFiscDia" value="S" id="ranchoFiscDia_radio1" <?php if ($ranchoFiscDia <> "C") echo "checked" ?>> Sem Alteração
                            </label>
                            <label onclick="enable('ranchoObs')" id="ranchoFiscDia_label2" class="btn btn-primary m-xs <?php if ($ranchoFiscDia == "C") echo "active" ?>">
                              <input type="radio" name="ranchoFiscDia" value="C" id="ranchoFiscDia_radio2" <?php if ($ranchoFiscDia == "C") echo "checked" ?>> Com Alteração
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-12 col-md-4 mb-sm">
                        <div class="col-sm-12 p-none">
                          <label for="form-group" class="control-label">8.2. Apresentação do Coz-de-Dia por término do SV:</label>
                        </div>
                        <div class="col-sm-12 p-none">
                          <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label onchange="disableRancho()" id="ranchoCozDia_label1" class="btn btn-primary m-xs <?php if ($ranchoCozDia <> "C") echo "active" ?>">
                              <input type="radio" name="ranchoCozDia" value="S" id="ranchoCozDia_radio1" <?php if ($ranchoCozDia <> "C") echo "checked" ?>> Sem Alteração
                            </label>
                            <label onclick="enable('ranchoObs')" id="ranchoCozDia_label2" class="btn btn-primary m-xs <?php if ($ranchoCozDia == "C") echo "active" ?>">
                              <input type="radio" name="ranchoCozDia" value="C" id="ranchoCozDia_radio2" <?php if ($ranchoCozDia == "C") echo "checked" ?>> Com Alteração
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12 p-none">
                      <div class="col-sm-12 col-md-6 mb-sm">
                        <div class="form-group">
                          <label for="form-group" class="control-label">8.3. Observações do Rancho, Fisc Dia Rancho e Coz-de-Dia:</label>
                          <div class="input-group">
                            <span class="input-group-addon"><i class="fas fa-pen-alt"></i></span>
                            <textarea style="resize: vertical; max-height:350px; min-height:35px;" name="ranchoObs" id="ranchoObs" class="form-control" rows="2" placeholder="Observações" <?php if ($ranchoCozDia <> "C" and $ranchoFiscDia <> "C" and $rancho <> "C") echo "disabled" ?>><?= $ranchoObs ?></textarea>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-6 col-md-3 mb-sm">
                        <div class="form-group">
                          <label for="form-group" class="control-label">8.4. Sobras (g):</label>
                          <div class="input-group">
                            <input type="text" class="form-control peso" name="ranchoSobras" value="<?= $ranchoSobras ?>" required>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-6 col-md-3 mb-sm">
                        <div class="form-group">
                          <label for="form-group" class="control-label">8.4. Resíduos (g):</label>
                          <div class="input-group">
                            <input type="text" class="form-control peso" name="ranchoResiduos" value="<?= $ranchoResiduos; ?>" required>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12 p-none">
                      <div class="col-sm-12 col-md-6 col-lg-5 mb-sm">
                        <div class="col-sm-12 p-none">
                          <label for="form-group" class="control-label">9. Abastecimento e Movimento de Viaturas, fora do expediente:</label>
                        </div>
                        <div class="col-sm-12 p-none">
                          <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label onclick="disable('abastecimentoObs')" id="abastecimento_label1" class="btn btn-primary m-xs <?php if ($abastecimento <> "C") echo "active" ?>">
                              <input type="radio" name="abastecimento" value="S" id="abastecimento_radio1" <?php if ($abastecimento <> "C") echo "checked" ?>> Sem Alteração
                            </label>
                            <label onclick="enable('abastecimentoObs')" id="abastecimento_label2" class="btn btn-primary m-xs <?php if ($abastecimento == "C") echo "active" ?>">
                              <input type="radio" name="abastecimento" value="C" id="abastecimento_radio2" <?php if ($abastecimento == "C") echo "checked" ?>> Com Alteração
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-12 col-md-6 col-lg-7 mb-sm">
                        <div class="form-group">
                          <label for="form-group" class="control-label">9.1. Observações de Abastecimento e Movimento de Viaturas:</label>
                          <div class="input-group">
                            <span class="input-group-addon"><i class="fas fa-pen-alt"></i></span>
                            <textarea style="resize: vertical; max-height:350px; min-height:35px;" name="abastecimentoObs" id="abastecimentoObs" class="form-control" rows="2" placeholder="Observações" <?php if ($abastecimento <> "C") echo "disabled" ?>><?= $abastecimentoObs ?></textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12 p-none">
                      <div class="col-sm-12 col-md-6 col-lg-5 mb-sm">
                        <div class="col-sm-12 p-none">
                          <label for="form-group" class="control-label">10. Apresentação de Militares e Deslocamentos para fora da sede:</label>
                        </div>
                        <div class="col-sm-12 p-none">
                          <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label onclick="disable('apresentacaoMilObs')" id="apresentacaoMil_label1" class="btn btn-primary m-xs <?php if ($apresentacaoMil <> "C") echo "active" ?>">
                              <input type="radio" name="apresentacaoMil" value="S" id="apresentacaoMil_radio1" <?php if ($apresentacaoMil <> "C") echo "checked" ?>> Sem Alteração
                            </label>
                            <label onclick="enable('apresentacaoMilObs')" id="apresentacaoMil_label2" class="btn btn-primary m-xs <?php if ($apresentacaoMil == "C") echo "active" ?>">
                              <input type="radio" name="apresentacaoMil" value="C" id="apresentacaoMil_radio2" <?php if ($apresentacaoMil == "C") echo "checked" ?>> Com Alteração
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-12 col-md-6 col-lg-7 mb-sm">
                        <div class="form-group">
                          <label for="form-group" class="control-label">10.1. Observações de Apresentação de Militares e Deslocamentos:</label>
                          <div class="input-group">
                            <span class="input-group-addon"><i class="fas fa-pen-alt"></i></span>
                            <textarea style="resize: vertical; max-height:350px; min-height:35px;" name="apresentacaoMilObs" id="apresentacaoMilObs" class="form-control" rows="2" placeholder="Observações" <?php if ($apresentacaoMil <> "C") echo "disabled" ?>><?= $apresentacaoMilObs ?></textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12 p-none">
                      <div class="col-sm-12 col-md-6 col-lg-5 mb-sm">
                        <div class="col-sm-12 p-none">
                          <label for="form-group" class="control-label">11. Ocorrências:</label>
                        </div>
                        <div class="col-sm-12 p-none">
                          <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label onclick="disable('ocorrenciasObs')" id="ocorrencias_label1" class="btn btn-primary m-xs <?php if ($ocorrencias <> "C") echo "active" ?>">
                              <input type="radio" name="ocorrencias" value="S" id="ocorrencias_radio1" <?php if ($ocorrencias <> "C") echo "checked" ?>> Sem Alteração
                            </label>
                            <label onclick="enable('ocorrenciasObs')" id="ocorrencias_label2" class="btn btn-primary m-xs <?php if ($ocorrencias == "C") echo "active" ?>">
                              <input type="radio" name="ocorrencias" value="C" id="ocorrencias_radio2" <?php if ($ocorrencias == "C") echo "checked" ?>> Com Alteração
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-12 col-md-6 col-lg-7 mb-sm">
                        <div class="form-group">
                          <label for="form-group" class="control-label">11.1. Observações de Ocorrências:</label>
                          <div class="input-group">
                            <span class="input-group-addon"><i class="fas fa-pen-alt"></i></span>
                            <textarea style="resize: vertical; max-height:350px; min-height:35px;" name="ocorrenciasObs" id="ocorrenciasObs" class="form-control" rows="2" placeholder="Observações" <?php if ($ocorrencias <> "C") echo "disabled" ?>><?= $ocorrenciasObs ?></textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12 p-none">
                      <div class="col-sm-12 mb-sm">
                        <div class="col-sm-12 p-none">
                          <label for="form-group" class="control-label">12. Anexos:</label>
                        </div>
                        <div class="col-sm-12 p-none">
                          <div class="form-group">
                            <div class="input-group">
                              <span class="input-group-addon"><i class="fas fa-paperclip"></i></span>
                              <textarea style="resize: vertical; max-height:350px; min-height:35px;" name="anexos" id="anexos" class="form-control" rows="2" placeholder="Anexos"><?= $anexos ?></textarea>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12 col-md-6 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">13. Passagem do Serviço:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-group"></i></span>
                          <select id="idOfDiaProximo" name="idOfDiaProximo" class="form-control select" style="width: 100%" required>
                            <option></option>
                            <optgroup label='Militar da OM'>
                              <?php for ($i = 0; $i < count($consultaTen); $i++) {
                                echo ("<option value=" . base64_encode($consultaTen[$i]['id']) . ">" . getPGrad($consultaTen[$i]['idpgrad']) . " - " . $consultaTen[$i]['nomecompleto'] . " (" . $consultaTen[$i]['nomeguerra'] . ")</option>");
                              } ?>
                            </optgroup>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12 mb-sm">
                      <label class="text-center center-block mt-md">Quartel em Três Lagoas, MS, <?= $dataLivroFinal ?>.</label>
                    </div>
                    <div class="col-md-3 mb-sm"></div>
                    <div class="col-sm-12 col-md-6 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">14. Oficial de Dia <?= PREPOSICAO ?> <?= NOME_OM ?>:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-group"></i></span>
                          <select id="idOfDia" name="idOfDia" class="form-control select" style="width: 100%" required>
                            <option></option>
                            <optgroup label='Militar da OM'>
                              <?php for ($i = 0; $i < count($consultaTen); $i++) {
                                echo ("<option value=" . base64_encode($consultaTen[$i]['id']) . ">" . getPGrad($consultaTen[$i]['idpgrad']) . " - " . $consultaTen[$i]['nomecompleto'] . " (" . $consultaTen[$i]['nomeguerra'] . ")</option>");
                              } ?>
                            </optgroup>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 mb-sm"></div>
                    <div class="col-md-12">
                      <hr>
                      <button type="submit" name="action" value='1' class="btn btn-warning" style="width: 280px;">LANÇAR / EDITAR</button>
                      <hr>
                      <button id="finalizar_btn" type="submit" name="action" value='0' class="btn btn-danger finalizar_btn" style="width: 280px;">FINALIZAR PARA IMPRESSÃO</button>
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
  </div>
  <script src="../recursos/vendor/jquery/jquery-1.12.3.min.js"></script>
  <script src="../recursos/vendor/bootstrap/js/bootstrap.min.js"></script>
  <script src="../recursos/vendor/nano-scroller/nano-scroller.js"></script>
  <script src="../recursos/vendor/javascripts/template-script.min.js"></script>
  <script src="../recursos/vendor/javascripts/template-init.min.js"></script>
  <script src="../recursos/vendor/javascripts/examples/tables/data-tables.js"></script>
  <script src="../recursos/vendor/javascripts/examples/forms/advanced.js"></script>
  <script src="../recursos/vendor/select2/js/select2.min.js"></script>
  <script src="../recursos/vendor/input-masked/inputmask.bundle.min.js"></script>
  <script src="../recursos/vendor/input-masked/phone-codes/phone.js"></script>
  <script src="../recursos/vendor/bootstrap_max-lenght/bootstrap-maxlength.js"></script>
  <script src="../recursos/vendor/bootstrap_date-picker/js/bootstrap-datepicker.min.js"></script>
  <script src="../recursos/vendor/bootstrap_time-picker/js/bootstrap-timepicker.js"></script>
  <script src="../recursos/vendor/bootstrap_color-picker/js/bootstrap-colorpicker.min.js"></script>
  <script src="../recursos/vendor/data-table/media/js/jquery.dataTables.min.js"></script>
  <script src="../recursos/vendor/data-table/media/js/dataTables.bootstrap.min.js"></script>
  <script src="../recursos/vendor/data-table/extensions/Responsive/js/dataTables.responsive.min.js"></script>
  <script src="../recursos/vendor/data-table/extensions/Responsive/js/responsive.bootstrap.min.js"></script>
  <script src="../recursos/vendor/jquery-validation/jquery.validate.min.js"></script>
  <script type="text/javascript">
    function enable(id) {
      document.getElementById(id).removeAttribute("disabled")
    }

    function disable(id) {
      document.getElementById(id).setAttribute("disabled", "")
    }

    function disableRancho() {
      if (document.getElementById("rancho_label1").classList.contains("active") === true && document.getElementById("ranchoFiscDia_label1").classList.contains("active") === true &&
        document.getElementById("ranchoCozDia_label1").classList.contains("active") === true) {
        document.getElementById("ranchoObs").setAttribute("disabled", "")
      }
    }

    const finalizarBtn = <?= $finalizarBtn ?>;
    var i = <?= count($idPunido); ?>;

    function addCampos() {
      if (i < 10) {
        document.getElementById("tPunidosTr_" + i).removeAttribute("hidden");
        document.getElementById("idPunido_" + i).disabled = false;
        document.getElementById("idPunicao_" + i).disabled = false;
        document.getElementById("data_inicio_" + i).disabled = false;
        document.getElementById("data_termino_" + i).disabled = false;
        document.getElementById("p_bi_" + i).disabled = false;
        document.getElementById("p_bi_data_" + i).disabled = false;
        document.getElementById("punidos_btn2").disabled = false;
        i++;
      }
      if (i >= 10) {
        document.getElementById("punidos_btn").disabled = true;
      }
    }

    function removerCampo() {
      if (i > 0) {
        ii = i - 1;
        document.getElementById("tPunidosTr_" + ii).hidden = true;
        document.getElementById("idPunido_" + ii).disabled = true;
        document.getElementById("idPunicao_" + ii).disabled = true;
        document.getElementById("data_inicio_" + ii).disabled = true;
        document.getElementById("data_termino_" + ii).disabled = true;
        document.getElementById("p_bi_" + ii).disabled = true;
        document.getElementById("p_bi_data_" + ii).disabled = true;
        document.getElementById("punidos_btn").disabled = false;
        i--;
      }
      if (i <= 0) {
        document.getElementById("punidos_btn2").disabled = true;
      }
    }

    $(document).ready(function() {
      $(".leituras").keyup(leiturasEnergiaAgua);
      document.getElementById("finalizar_btn").disabled = finalizarBtn;
    });

    function leiturasEnergiaAgua() {
      var energiaAnterior = $('#energiaAnterior').val();
      var energiaAtual = $('#energiaAtual').val();
      var energiaConsumo = (parseInt(energiaAtual.replace(/([^\d])+/gim, ''), 10) - parseInt(energiaAnterior.replace(/([^\d])+/gim, ''), 10));
      if (energiaConsumo >= 0) {
        $('#energiaConsumo').val(energiaConsumo);
      } else {
        $('#energiaConsumo').val(0);
      }

      var aguaExternoAnterior = $('#aguaExternoAnterior').val();
      var aguaExternoAtual = $('#aguaExternoAtual').val();
      var aguaExternoConsumo = (parseInt(aguaExternoAtual.replace(/([^\d])+/gim, ''), 10) - parseInt(aguaExternoAnterior.replace(/([^\d])+/gim, ''), 10));
      if (aguaExternoConsumo >= 0) {
        $('#aguaExternoConsumo').val(aguaExternoConsumo);
      } else {
        $('#aguaExternoConsumo').val(0);
      }

      var aguaInternoAnterior = $('#aguaInternoAnterior').val();
      var aguaInternoAtual = $('#aguaInternoAtual').val();
      var aguaInternoConsumo = (parseInt(aguaInternoAtual.replace(/([^\d])+/gim, ''), 10) - parseInt(aguaInternoAnterior.replace(/([^\d])+/gim, ''), 10));
      if (aguaInternoConsumo >= 0) {
        $('#aguaInternoConsumo').val(aguaInternoConsumo);
      } else {
        $('#aguaInternoConsumo').val(0);
      }
    }

    $(".tabela").DataTable({
      scrollY: "480px",
      scrollCollapse: true,
      paging: false,
      searching: false,
      info: false
    });
    $(".bi").inputmask({
      mask: "9{1,3}"
    });
    $(".agua").inputmask({
      mask: "9.999.999,9",
      numericInput: true
    });
    $(".agua2").inputmask({
      mask: "999.999,99",
      numericInput: true
    });
    $(".energia").inputmask({
      mask: "99.999.999",
      numericInput: true
    });
    $(".peso").inputmask({
      mask: "99.999",
      numericInput: true
    });
    $("#idOfDia").val("<?= base64_encode($idOfDia) ?>").select2();
    $("#idOfDiaAnterior").val("<?= base64_encode($idOfDiaAnterior) ?>").select2();
    $("#idOfDiaProximo").val("<?= base64_encode($idOfDiaProximo) ?>").select2();
    <?php for ($auxI = 0; $auxI < count($idPunido); $auxI++) { ?>
      $("#idPunido_" + <?= $auxI ?>).val("<?= base64_encode($idPunido[$auxI]) ?>").select2();
      $("#idPunicao_" + <?= $auxI ?>).val("<?= $idPunicao[$auxI] ?>").select2();
    <?php } ?>
  </script>
  <script type="text/javascript" src="../recursos/vendor/javascripts/vendor-script-5.js"></script>
</body>

</html>