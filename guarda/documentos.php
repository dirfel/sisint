<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if (!($_SESSION['nivel_guarda'] == "Cabo Gda" || $_SESSION['nivel_guarda'] == "Oficial e Sargento" || $_SESSION['nivel_guarda'] == "Supervisor" || $_SESSION['nivel_guarda'] == "Administrador")) {
  $msgerro = base64_encode('Usuário não possui acesso! Somente usuário: Cabo Gda, Oficial e Sargento!');
  header('Location: index.php?token=' . $msgerro);
  exit();
}

$hora = "08:00";
$dataInicial = date("d/m/Y", strtotime(date("Y-m-d") . '-1 days'));
$dataFinal = date("d/m/Y");

?>
<!doctype html>
<html lang="pt-BR" class="fixed left-sidebar-collapsed">

<head>
  <?php include '../recursos/views/cabecalho.php'; ?>
  <script src="../recursos/vendor/chart-js/chart.js"></script>
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
      <?php render_content_header('Documentos e Estatísticas', 'fa fa-print'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <form target="_blank" id="validation" action="documentos_gerados.php" method="post">
            <div class="col-sm-12 col-md-6">
              <div class="panel"><?php render_cabecalho_painel('GERAR RELATÓRIOS E ROTEIROS:', 'fas fa-print', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-md-12 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Selecione o Tipo de Documento na lista:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fas fa-print"></i></span>
                          <select name="relatorio" id="select2-example-basic" class="form-control" style="width: 100%" required>
                            <option></option>
                            <optgroup label='Tipo de Documento'>
                            <option value='Pronto de Viaturas'>Pronto de Viaturas</option>
                            <option value='Livro de Partes do Oficial de Dia'>Livro de Partes do Oficial de Dia</option>
                            <option value='Entrada e Saída de Militares Durante o Expediente'>Entrada e Saída de Militares Durante o Expediente</option>
                            <option value='Entrada e Saída de Militares Após o Expediente'>Entrada e Saída de Militares Após o Expediente</option>
                            <option value='Entrada e Saída de Visitantes e Veículos'>Entrada e Saída de Visitantes e Veículos</option>
                            <option value='Entrada e Saída de Viaturas Militares'>Entrada e Saída de Viaturas Militares</option>
                            <option value='Entrada e Saída no Alojamento de Cabo e Soldado'>Entrada e Saída no Alojamento de Cabo e Soldado</option>
                            <option value='Militares e Visitantes que Pernoitaram na OM'>Militares e Visitantes que Pernoitaram na OM</option>
                            <option value='Roteiro da Guarda e dos Postos'>Roteiro da Guarda e dos Postos</option>
                            <option value='Roteiro de Ronda e Permanência'>Roteiro de Ronda e Permanência</option>
                            </optgroup>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Data ASSUMIU o Serviço:</label>
                        <div class="input-group date">
                          <span class="input-group-addon color-darker-1 date-time-color"><i class="fa fa-calendar"></i></span>
                          <input id="dataAssumiuId" type="text" class="form-control" name="data_inicial" onchange="dataPassou()" autocomplete="off" value="<?php echo ($dataInicial); ?>" required>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Data PASSOU o Serviço:</label>
                        <div class="input-group">
                          <span class="input-group-addon color-darker-2 "><i class="fa fa-calendar"></i></span>
                          <input id="dataPassouId" type="text" class="form-control" name="data_final" autocomplete="off" value="<?php echo ($dataFinal); ?>" readonly>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <hr>
                      <button type="submit" name="action" value='Gerou Documento' class="btn btn-primary">GERAR</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        <!-- </div> -->
        <?php
            $ano = date('Y');
            $mes = date('m');
            if(isset($_GET['mes']) && is_numeric($_GET['mes'])) {
              $mes = str_pad($_GET['mes'], 2, '0', STR_PAD_LEFT);
            }
            if(isset($_GET['ano']) && is_numeric($_GET['ano'])) {
              $ano = str_pad($_GET['ano'], 2, '0', STR_PAD_LEFT);
            }
            $pdo = conectar('guarda');
            $query = 'SELECT liv_partes_ofdia.id, liv_partes_ofdia.data, liv_partes_ofdia.idleituras, 
            liv_partes_ofdia_leituras.energia_anterior, liv_partes_ofdia_leituras.energia1, liv_partes_ofdia_leituras.energia2, liv_partes_ofdia_leituras.energia_atual, 
            liv_partes_ofdia_leituras.agua_int_anterior, liv_partes_ofdia_leituras.agua_int_atual, 
            liv_partes_ofdia_leituras.agua_ext_anterior, liv_partes_ofdia_leituras.agua_ext_atual, 
            liv_partes_ofdia_leituras.umid1, liv_partes_ofdia_leituras.umid2, liv_partes_ofdia_leituras.umid3, 
            liv_partes_ofdia_leituras.umid5, liv_partes_ofdia_leituras.umid6, liv_partes_ofdia_leituras.umid7, 
            liv_partes_ofdia_leituras.temp1, liv_partes_ofdia_leituras.temp2, liv_partes_ofdia_leituras.temp3, 
            liv_partes_ofdia_leituras.temp5, liv_partes_ofdia_leituras.temp6, liv_partes_ofdia_leituras.temp7 
            FROM `liv_partes_ofdia` INNER JOIN liv_partes_ofdia_leituras ON idleituras = liv_partes_ofdia_leituras.id_leituras 
            WHERE liv_partes_ofdia.data LIKE "'.$ano.'-'.$mes.'%";';
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $umidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
          ?>
          <div class="col-sm-12 col-md-12"></div>
          <div class="col-sm-12 col-md-6">
              <div class="panel"><?php render_cabecalho_painel('ESTATÍSTICAS DE ÁGUA E LUZ (<a href="documentos.php?mes='.(($mes == 1) ? 12 : ($mes - 1)).'&ano='.(($mes == 1) ? ($ano - 1) : $ano).'"><i class="fa fa-arrow-left text-danger"></i></a> '.$mes . '-' .$ano.' <a href="documentos.php?mes='.(($mes == 12) ? 1 : ($mes + 1)).'&ano='.(($mes == 12) ? ($ano + 1) : $ano).'"><i class="fa fa-arrow-right text-danger"></i></a>)', 'fas fa-line-chart', true); ?>
                <div class="panel-content">
                  <div class="row">
                  <div class="col-sm-12 col-md-12">
                        <a target="_blank" href="energia.php?mes=<?=$mes?>&ano=<?=$ano?>" class="btn btn-primary">Relatório de Consumo de Energia</a>
                        <a target="_blank" href="agua.php?mes=<?=$mes?>&ano=<?=$ano?>" class="btn btn-primary">Relatório de Consumo de Água</a>
                    </div>
                    <div class="col-sm-12 col-md-12"><canvas id="aguaexterno"></canvas></div><br>
                    <div class="col-sm-12 col-md-12"><hr></div>
                    <div class="col-sm-12 col-md-12"><canvas id="aguainterno"></canvas></div><br>
                    <div class="col-sm-12 col-md-12"><hr></div>
                    <div class="col-sm-12 col-md-12"><canvas id="energiatotal"></canvas></div><br>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-12 col-md-6">
              <div class="panel"><?php render_cabecalho_painel('ESTATÍSTICAS DE TEMPERATURA E UMIDADE (<a href="documentos.php?mes='.(($mes == 1) ? 12 : ($mes - 1)).'&ano='.(($mes == 1) ? ($ano - 1) : $ano).'"><i class="fa fa-arrow-left text-danger"></i></a> '.$mes . '-' .$ano.' <a href="documentos.php?mes='.(($mes == 12) ? 1 : ($mes + 1)).'&ano='.(($mes == 12) ? ($ano + 1) : $ano).'"><i class="fa fa-arrow-right text-danger"></i></a>)', 'fas fa-line-chart', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <a target="_blank" href="temperatura.php?mes=<?=$mes?>&ano=<?=$ano?>&relatorio=temp-sim" class="btn btn-primary">Temperatura Simulador</a>
                        <a target="_blank" href="temperatura.php?mes=<?=$mes?>&ano=<?=$ano?>&relatorio=umid-sim" class="btn btn-primary">Umidade Simulador</a>
                        <a target="_blank" href="temperatura.php?mes=<?=$mes?>&ano=<?=$ano?>&relatorio=temp-rad" class="btn btn-primary">Temperatura Radar</a>
                        <a target="_blank" href="temperatura.php?mes=<?=$mes?>&ano=<?=$ano?>&relatorio=umid-rad" class="btn btn-primary">Umidade Radar</a>
                    </div>
                    <div class="col-sm-12 col-md-12"><canvas id="umidadeChart1"></canvas></div><br>
                    <div class="col-sm-12 col-md-12"><hr></div>
                    <div class="col-sm-12 col-md-12"><canvas id="umidadeChart2"></canvas></div><br>
                    <div class="col-sm-12 col-md-12"><hr></div>
                    <div class="col-sm-12 col-md-12"><canvas id="tempChart1"></canvas></div><br>
                    <div class="col-sm-12 col-md-12"><hr></div>
                    <div class="col-sm-12 col-md-12"><canvas id="tempChart2"></canvas></div><br>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>
        <?php include '../recursos/views/scroll_to_top.php'; ?>
    </div>
  </div>
  <!-- Agua externa -->
  <script>
          function generateaguaexternoData() {
            const data = [];
            <?php foreach($umidades as $umidade) {?> 
              data.push({
                day: <?=$umidade['data'][8].$umidade['data'][9]?>,
                consumo: <?=($umidade['agua_ext_atual'] - $umidade['agua_ext_anterior'])/100?>,
              });
            <?php } ?>
            return data;
          }
          const agua_ext = generateaguaexternoData();
          const agua_ext_chart = new Chart(document.getElementById('aguaexterno').getContext('2d'), {
            data: {
              labels: agua_ext.map(entry => entry.day),
              datasets: [{
                  type: 'line',
                  label: 'Crítico',
                  data: agua_ext.map(entry => 2),
                  borderColor: 'red',
                  backgroundColor: 'red',
                  fill: false,
                  lineTension: 0.4,
                }, {
                  type: 'line',
                  label: 'Máximo',
                  data: agua_ext.map(entry => 1),
                  borderColor: 'orange',
                  backgroundColor: 'orange',
                  fill: false,
                  lineTension: 0.4,
                }, {
                  type: 'bar',
                  label: 'Consumo',
                  data: agua_ext.map(entry => entry.consumo),
                  borderColor: 'blue',
                  backgroundColor: 'blue',
                  fill: false,
                  lineTension: 0.4,
                },
              ],
            },
            options: {
              plugins: {
                title: {
                  display: true,
                  text: 'Consumo de água externa',
                },
                tooltip: {
                  callbacks: {
                    label: function (context) {
                      return context.dataset.label + ': ' + context.parsed.y + ' m³';
                    },
                  },
                },
              },
              interaction: {
                mode: 'index',
                intersect: false,
              },
              scales: {
                x: {
                  display: true,
                  title: {
                    display: true,
                    text: 'Dias do Mês',
                  },
                },
                y: {
                  ticks: {
                    stepSize: 1,
                  },
                  title: {
                    display: true,
                    text: 'm³',
                  },
                },
              },
            },
          });
        </script>
  <!-- Agua interna -->
  <script>
          function generateaguainternoData() {
            const data = [];
            <?php foreach($umidades as $umidade) {?> 
              data.push({
                day: <?=$umidade['data'][8].$umidade['data'][9]?>,
                consumo: <?=($umidade['agua_int_atual'] - $umidade['agua_int_anterior'])/10 ?>,
              });
            <?php } ?>
            return data;
          }

          const agua_int = generateaguainternoData();
          const agua_int_chart = new Chart(document.getElementById('aguainterno').getContext('2d'), {
            type: 'line',
            data: {
              labels: agua_int.map(entry => entry.day),
              datasets: [{
                  type: 'line',
                  label: 'Crítico',
                  data: agua_int.map(entry => 40),
                  borderColor: 'red',
                  backgroundColor: 'red',
                  fill: false,
                  lineTension: 0.4,
                }, {
                  type: 'line',
                  label: 'Máximo',
                  data: agua_int.map(entry => 30),
                  borderColor: 'orange',
                  backgroundColor: 'orange',
                  fill: false,
                  lineTension: 0.4,
                }, {
                  type: 'bar',
                  label: 'Consumo',
                  data: agua_int.map(entry => entry.consumo),
                  borderColor: 'blue',
                  backgroundColor: 'blue',
                  fill: false,
                  lineTension: 0.4,
                },
              ],
            },
            options: { plugins: { title: { display: true, text: 'Consumo de água interna', },tooltip:{callbacks:{label:function(context){return context.dataset.label+': '+context.parsed.y + ' m³';},},},},
              interaction: {mode: 'index',intersect: false,},
              scales:{x:{display:true,title: {display: true,text: 'Dias do Mês',},},y: {ticks: {stepSize: 20,},title:{display:true,text:'m³',},},
              },},
          });
        </script>
  <!-- energia total -->
  <script>
          function generateenergiatotalData() {
            const data = [];
            <?php foreach($umidades as $umidade) {?> 
              data.push({
                day: <?=$umidade['data'][8].$umidade['data'][9]?>,
                consumo: <?=$umidade['energia_atual'] - $umidade['energia_anterior']?>,
              });
            <?php } ?>
            return data;
          }

          const energia = generateenergiatotalData();
          const energia_chart = new Chart(document.getElementById('energiatotal').getContext('2d'), {

            data: {
              labels: energia.map(entry => entry.day),
              datasets: [{
                type: 'line',
                  label: 'Crítico',
                  data: energia.map(entry => 80),
                  borderColor: 'red',
                  backgroundColor: 'red',
                  fill: false,
                  lineTension: 0.4,
                }, {
                  type: 'line',
                  label: 'Máximo',
                  data: energia.map(entry => 40),
                  borderColor: 'orange',
                  backgroundColor: 'orange',
                  fill: false,
                  lineTension: 0.4,
                }, {
                  type: 'bar',
                  label: 'Consumo',
                  data: energia.map(entry => entry.consumo),
                  borderColor: 'blue',
                  backgroundColor: 'blue',
                  fill: false,
                  lineTension: 0.4,
                },
              ],
            },
            options: {
              plugins: {
                title: {
                  display: true,
                  text: 'Consumo de energia',
                },
                tooltip: {
                  callbacks: {
                    label: function (context) {
                      return context.dataset.label + ': ' + context.parsed.y + ' Kw/h';
                    },
                  },
                },
              },
              interaction: {
                mode: 'index',
                intersect: false,
              },
              scales: {
                x: {
                  display: true,
                  title: {
                    display: true,
                    text: 'Dias do Mês',
                  },
                },
                y: {
                  ticks: {
                    stepSize: 20,
                  },
                  title: {
                    display: true,
                    text: 'Kw/h',
                  },
                },
              },
            },
          });
        </script>
  <!-- umidade 1 -->
  <script>
          function generateUmidade1Data() {
            const data = [];
            <?php foreach($umidades as $umidade) {?> 
              data.push({
                day: <?=$umidade['data'][8].$umidade['data'][9]?>,
                morning: <?=$umidade['umid1']?>,
                afternoon: <?=$umidade['umid2']?>,
                evening: <?=$umidade['umid3']?>,
              });
            <?php } ?>
            return data;
          }

          const umidadeData1 = generateUmidade1Data();
          const umidadeChart1 = new Chart(document.getElementById('umidadeChart1').getContext('2d'), {
            type: 'line',
            data: {
              labels: umidadeData1.map(entry => entry.day),
              datasets: [{
                  label: 'Máximo',
                  data: umidadeData1.map(entry => 76),
                  borderColor: 'red',
                  backgroundColor: 'red',
                  fill: false,
                  lineTension: 0.4,
                }, {
                  label: 'Manhã',
                  data: umidadeData1.map(entry => entry.morning),
                  borderColor: 'purple',
                  backgroundColor: 'purple',
                  fill: false,
                  lineTension: 0.4,
                }, {
                  label: 'Tarde',
                  data: umidadeData1.map(entry => entry.afternoon),
                  borderColor: 'green',
                  backgroundColor: 'green',
                  fill: false,
                  lineTension: 0.4,
                }, {
                  label: 'Noite',
                  data: umidadeData1.map(entry => entry.evening),
                  borderColor: 'blue',
                  backgroundColor: 'blue',
                  fill: false,
                  lineTension: 0.4,
                }, {
                  label: 'Minimo',
                  data: umidadeData1.map(entry => 34),
                  borderColor: 'orange',
                  backgroundColor: 'orange',
                  fill: false,
                  lineTension: 0.4,
                },],
            },
            options: {
              plugins: {
                title: {
                  display: true,
                  text: 'Umidade do Ar na sala do Simulador do RBS-70',
                },
                tooltip: {
                  callbacks: {
                    label: function (context) {
                      return context.dataset.label + ': ' + context.parsed.y + '%';
                    },
                  },
                },
              },
              interaction: {
                mode: 'index',
                intersect: false,
              },
              scales: {
                x: {
                  display: true,
                  title: {
                    display: true,
                    text: 'Dias do Mês',
                  },
                },
                y: {
                  ticks: {
                    stepSize: 20,
                  },
                  title: {
                    display: true,
                    text: 'Umidade (%)',
                  },
                },
              },
            },
          });
  </script>
  <!-- umidade 2 -->
  <script>
        // Gerar dados aleatórios para o gráfico
        function generateUmidade2Data() {
          const data = [];
          <?php foreach($umidades as $umidade) {?> 
            data.push({
              day: <?=$umidade['data'][8].$umidade['data'][9]?>,
              morning: <?=$umidade['umid5']?>,
              afternoon: <?=$umidade['umid6']?>,
              evening: <?=$umidade['umid7']?>,
            });
          <?php } ?>
          return data;
        }

        // Configurar dados para o gráfico
        const umidadeData2 = generateUmidade2Data();
        const umidadeChart2 = new Chart(document.getElementById('umidadeChart2').getContext('2d'), {
          type: 'line',
          data: {
            labels: umidadeData2.map(entry => entry.day),
            datasets: [{
                label: 'Máximo',
                data: umidadeData2.map(entry => 76),
                borderColor: 'red',
                backgroundColor: 'red',
                fill: false,
                lineTension: 0.4,
              }, {
                label: 'Manhã',
                data: umidadeData2.map(entry => entry.morning),
                borderColor: 'purple',
                backgroundColor: 'purple',
                fill: false,
                lineTension: 0.4,
              }, {
                label: 'Tarde',
                data: umidadeData2.map(entry => entry.afternoon),
                borderColor: 'green',
                backgroundColor: 'green',
                fill: false,
                lineTension: 0.4,
              }, {
                label: 'Noite',
                data: umidadeData2.map(entry => entry.evening),
                borderColor: 'blue',
                backgroundColor: 'blue',
                fill: false,
                lineTension: 0.4,
              }, {
                label: 'Minimo',
                data: umidadeData2.map(entry => 34),
                borderColor: 'orange',
                backgroundColor: 'orange',
                fill: false,
                lineTension: 0.4,
              },],
          },
          options: {
            plugins: {
              title: {
                display: true,
                text: 'Umidade do Ar na sala do míssil RBS-70',
              },
              tooltip: {
                callbacks: {
                  label: function (context) {
                    return context.dataset.label + ': ' + context.parsed.y + '%';
                  },
                },
              },
            },
            interaction: {
              mode: 'index',
              intersect: false,
            },
            scales: {
              x: {
                display: true,
                title: {
                  display: true,
                  text: 'Dias do Mês',
                },
              },
              y: {
                ticks: {
                  stepSize: 20,
                },
                title: {
                  display: true,
                  text: 'Umidade (%)',
                },
              },
            },
          },
        });
  </script>
  <!-- Temp 1 -->
  <script>
    // Gerar dados aleatórios para o gráfico
    function generateTemp1Data() {
      const data = [];
      <?php foreach($umidades as $temp) {?> 
        data.push({
          day: <?=$temp['data'][8].$temp['data'][9]?>,
          morning: <?=$temp['temp1']?>,
          afternoon: <?=$temp['temp2']?>,
          evening: <?=$temp['temp3']?>,
        });
      <?php } ?>
      return data;
    }

    // Configurar dados para o gráfico
    const tempData1 = generateTemp1Data();
    const tempChart1 = new Chart(document.getElementById('tempChart1').getContext('2d'), {
      type: 'line',
      data: {
        labels: tempData1.map(entry => entry.day),
        datasets: [{
            label: 'Máximo',
            data: tempData1.map(entry => 37),
            borderColor: 'red',
            backgroundColor: 'red',
            fill: false,
            lineTension: 0.4,
          }, {
            label: 'Manhã',
            data: tempData1.map(entry => entry.morning),
            borderColor: 'purple',
            backgroundColor: 'purple',
            fill: false,
            lineTension: 0.4,
          }, {
            label: 'Tarde',
            data: tempData1.map(entry => entry.afternoon),
            borderColor: 'green',
            backgroundColor: 'green',
            fill: false,
            lineTension: 0.4,
          }, {
            label: 'Noite',
            data: tempData1.map(entry => entry.evening),
            borderColor: 'blue',
            backgroundColor: 'blue',
            fill: false,
            lineTension: 0.4,
          }, {
            label: 'Minimo',
            data: tempData1.map(entry => 16),
            borderColor: 'orange',
            backgroundColor: 'orange',
            fill: false,
            lineTension: 0.4,
          },],
      },
      options: {
        plugins: {
          title: {
            display: true,
            text: 'Temperatura na sala do Simulador do RBS-70',
          },
          tooltip: {
            callbacks: {
              label: function (context) {
                return context.dataset.label + ': ' + context.parsed.y + '%';
              },
            },
          },
        },
        interaction: {
          mode: 'index',
          intersect: false,
        },
        scales: {
          x: {
            display: true,
            title: {
              display: true,
              text: 'Dias do Mês',
            },
          },
          y: {
            ticks: {
              stepSize: 20,
            },
            title: {
              display: true,
              text: 'Temperatura (ºC)',
            },
          },
        },
      },
    });
  </script>
  <!-- Temp 2 -->
  <script>
    // Gerar dados aleatórios para o gráfico
    function generateTemp2Data() {
      const data = [];
      <?php foreach($umidades as $temp) {?> 
        data.push({
          day: <?=$temp['data'][8].$temp['data'][9]?>,
          morning: <?=$temp['temp5']?>,
          afternoon: <?=$temp['temp6']?>,
          evening: <?=$temp['temp7']?>,
        });
      <?php } ?>
      return data;
    }

    // Configurar dados para o gráfico
    const tempData2 = generateTemp2Data();
    const tempChart2 = new Chart(document.getElementById('tempChart2').getContext('2d'), {
      type: 'line',
      data: {
        labels: tempData2.map(entry => entry.day),
        datasets: [{
            label: 'Máximo',
            data: tempData2.map(entry => 37),
            borderColor: 'red',
            backgroundColor: 'red',
            fill: false,
            lineTension: 0.4,
          }, {
            label: 'Manhã',
            data: tempData2.map(entry => entry.morning),
            borderColor: 'purple',
            backgroundColor: 'purple',
            fill: false,
            lineTension: 0.4,
          }, {
            label: 'Tarde',
            data: tempData2.map(entry => entry.afternoon),
            borderColor: 'green',
            backgroundColor: 'green',
            fill: false,
            lineTension: 0.4,
          }, {
            label: 'Noite',
            data: tempData2.map(entry => entry.evening),
            borderColor: 'blue',
            backgroundColor: 'blue',
            fill: false,
            lineTension: 0.4,
          }, {
            label: 'Minimo',
            data: tempData2.map(entry => 16),
            borderColor: 'orange',
            backgroundColor: 'orange',
            fill: false,
            lineTension: 0.4,
          },],
      },
      options: {
        plugins: {
          title: {
            display: true,
            text: 'Temperatura na sala do míssil RBS-70',
          },
          tooltip: {
            callbacks: {
              label: function (context) {
                return context.dataset.label + ': ' + context.parsed.y + '%';
              },
            },
          },
        },
        interaction: {
          mode: 'index',
          intersect: false,
        },
        scales: {
          x: {
            display: true,
            title: {
              display: true,
              text: 'Dias do Mês',
            },
          },
          y: {
            ticks: {
              stepSize: 20,
            },
            title: {
              display: true,
              text: 'Temperatura (ºC)',
            },
          },
        },
      },
    });
  </script>
  <?php include '../recursos/views/footer.php'; ?>
  <script type="text/javascript">
    function dataPassou() {
      var dataAssumiuVal = $('#dataAssumiuId').val();
      dataAssumiuVal = dataAssumiuVal.split("/", 3);
      var dataAssumiuMes = (parseInt(dataAssumiuVal[1], 10) - 1);
      var dataPassouVal = new Date(dataAssumiuVal[2], dataAssumiuMes, dataAssumiuVal[0]);
      dataPassouVal.setDate(dataPassouVal.getDate() + 1);
      var dataPassouMesAux = (dataPassouVal.getMonth() + 1);
      var dataPassouMes = (dataPassouMesAux < 10) ? '0' + dataPassouMesAux : dataPassouMesAux;
      var dataPassouDia = (dataPassouVal.getDate() < 10) ? '0' + dataPassouVal.getDate() : dataPassouVal.getDate();
      var dataPassouValFinal = dataPassouDia + '/' + dataPassouMes + '/' + dataPassouVal.getFullYear();
      $('#dataPassouId').val(dataPassouValFinal);
    }
  </script>
</body>
</html>