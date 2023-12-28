<?php

require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

$pdo = conectar("membros");
?>
<!doctype html>
<html lang="pt-BR" class="fixed left-sidebar-collapsed">

<head>
  <?php include '../recursos/views/cabecalho.php'; ?>
</head>

<body>
  <div class="wrap">
    <div class="page-header">
    <?php render_painel_usu('CONTROLE DE PESSOAL', $_SESSION['nivel_plano_chamada']); ?>
    </div>
    <div class="page-body">
      <div class="left-sidebar">
        <?php include 'menu_opc.php'; ?>
      </div>
      <div class="content">
      <?php render_content_header('Livro de Afastamento', 'fa fa-map-marker-alt'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-4">
            <div class="panel"><?php render_cabecalho_painel('CADASTRAR MINHA VIAGEM', 'fas fa-plus', true); ?>
              <div class="panel-content">
                <div class="row">
                  <div class="col-md-12">
                    <form id="inline-validation" action="conf_afastamento.php" method="post">
                    <div class="col-md-6">
                        <label for="inputinicio" class="control-label">Data de início:</label>
                      <div class="input-group date">
                      <span class="input-group-addon date-time-color"><i class="fa fa-calendar"></i></span>
                        <input type="datetime" autocomplete="off" class="form-control" id="inputinicio" name="inicio" required>
                      </div>
                      </div>
                    <div class="col-md-6">
                        <label for="inputfim" class="control-label">Data de fim:</label>
                      <div class="input-group date">
                      <span class="input-group-addon date-time-color"><i class="fa fa-calendar"></i></span>
                        <input type="datetime" autocomplete="off" class="form-control" id="inputfim" name="fim" required>
                      </div>
                      </div>
                      <div class="col-md-12"></hr></div>
                      <div class="col-md-12">
                      <div class="form-group">
                        <label for="inputMaxLength" class="control-label">Cidade de destino:</label>
                        <input type="text" class="form-control maxLength" id="inputMaxLength" name="cidade" placeholder="Cidade - UF" maxlength="50" required>
                      </div>
                      </div>
                      <div class="col-md-12"></hr></div>

                      <div class="col-md-6">
                        <div class="form-group">
                            <label>Telefone contato:</label>
                            <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-mobile-phone"></i></span>
                            <input type="text" class="form-control cellPhone" name="fonecelular" placeholder="XX-XXXXX-XXXX" pattern="^\d{2}-\d{5}-\d{4}$" required="Preenchimento obrigatório">
                            </div>
                        </div>
                      </div>
                      <div class="col-md-6">

                      <div class="form-group">
                        <label for="select3-example-basic" class="control-label">Motivo:</label>
                        <select name="motivo" id="select3-example-basic" class="form-control" style="width: 100%" required>
                            <optgroup label='MOTIVO'>
                            <option value=""></option>
                            <option value="0">Missão</option>
                            <option value="1">Descanso (férias, dispensa, fim de semana, ...)</option>
                            <option value="2">Tratamento de Saúde</option>
                            <option value="3">Outro (especifique abaixo)</option>
                            </optgroup>
                        </select>
                      </div>
                      </div>
                      <div class="col-md-12"></hr></div>

                      <div class="col-md-12">
                      <div class="form-group">
                        <label for="obs" class="control-label">Observações:</label>
                        <input type="text" class="form-control" id="obs" name="obs" placeholder="Observações">
                      </div>
                      <div class="form-group">
                        <hr>
                        <button type="submit" class="btn btn-primary">
                          CADASTRAR
                        </button>
                      </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <?php 
            if ($_SESSION['nivel_plano_chamada'] == "Administrador" || $_SESSION['nivel_plano_chamada'] == "Supervisor") {
                render_card2('relatorio_afastamentos.php', 'Exibir o relatório', 'icon fa fa-list', '<h4 class="title">Relatório<b></b></h4><h4 class="subtitle">Relatório de afastamentos futuros.</h4>', '_blank', ''); 
            } ?>
            </div>
          <div class="col-md-8">
            <div class="panel"><?php render_cabecalho_painel('HISTÓRICO DE AFASTAMENTOS:', 'fas fa-map-marker-alt', true); ?>
              <div class="panel-content">
                <table id="responsive-table" class="data-table table table-striped table-hover responsive" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th class="text-center">Militar</th>
                      <th class="text-center">Ida e volta</th>
                      <th class="text-center">Destino</th>
                      <th class="text-center">Telefone</th>
                      <th class="text-center">Motivo</th>
                      <th class="text-center">Observações</th>
                      <th class="text-center">Ações</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $motivo = ['Missão', "Descanso", "Tratamento de saúde", "Outro"];
                    if($_SESSION['nivel_plano_chamada'] == "Supervisor" | $_SESSION['nivel_plano_chamada'] == "Administrador"){
                        $consulta2 = $pdo->prepare("SELECT * FROM afastamentos INNER JOIN usuarios ON afastamentos.militar = usuarios.id ORDER BY afastamentos.af_id DESC");
                        $consulta2->execute();

                    } else {
                        $consulta2 = $pdo->prepare("SELECT * FROM afastamentos INNER JOIN usuarios ON afastamentos.militar = usuarios.id WHERE afastamentos.militar = ".$_SESSION['auth_data']['id']." ORDER BY afastamentos.af_id DESC");
                        $consulta2->execute();
                    }
                    
                    while ($reg2 = $consulta2->fetch(PDO::FETCH_ASSOC)) {
                      echo ("<tr>");
                      echo ('<td class="text-center">' . ImprimeConsultaMilitar2($re2) . "</td>");
                      echo ('<td class="text-center">' . $reg2['inicio'] . '<br>' . $reg2['fim'] . '</td>');
                      echo ('<td class="text-center">' . $reg2['destino'] . "</td>");
                      echo ('<td class="text-center">' . $reg2['fonecelular'] . "</td>");
                      echo ('<td class="text-center">' . $motivo[$reg2['motivo']] . "</td>");
                      echo ("<td>" . $reg2['obs'] . "<td>");
                      echo (date_converter($reg2['fim']) > date('Y-m-d') || $_SESSION['nivel_plano_chamada'] == 'Administrador' || $_SESSION['nivel_plano_chamada'] == 'Supervisor') ? ('<td class="text-center"><a href="remover_afastamento.php?id='.base64_encode($reg2['af_id']).'" class="btn btn-danger">X</a></td>') : '<td></td>';
                      echo "<tr>";
                    }
                    ?>
                  </tbody>
                </table> 
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