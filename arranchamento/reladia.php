<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

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
      <?php render_content_header('Gerar Relatório', 'fa fa-print'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <form target="_blank" id="validation" action="reladiafinal.php" method="post">
            <div class="col-sm-12 col-md-6">
              <div class="panel"><?php render_cabecalho_painel('GERAR RELATÓRIOS DE ARRANCHADOS', 'fa fa-print', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-md-12 mb-sm"><?php render_data_field('datarancho', true, 'Data:', null) ?></div>
                    <div class="col-md-12 mb-sm">
                      <div class="form-group">
                        <label for="inputMaxLength" class="control-label">Selecione o Círculo Hierárquico:</label>
                        <div class="input-group">
                          <select name="postograd" id="select2-example-basic" class="form-control" style="width: 100%" required>
                            <option></option>
                            <optgroup label='Posto / Graduação'>
                              <option value=0> TODOS </option>
                              <option value=1> Oficiais </option>
                              <option value=2> Subtenentes/Sargentos </option>
                              <option value=3> Cabos/Soldados </option>
                            </optgroup>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12 mb-sm">
                      <div class="form-group">
                        <label for="inputMaxLength" class="control-label">Selecione a Subunidade:</label>
                        <div class="input-group">
                          <select name="subunidade" id="select2-example-basic2" class="form-control" style="width: 100%" required>
                            <option></option>
                            <optgroup label='Subunidade'>
                            <option value= 0>TODAS </option>
                            <?php $consultax = listar_subunidades();
                            foreach ($consultax as $regx) {
                              echo ("<option value=" . $regx['id'] . ">" . $regx['descricao'] . "</option>");
                            } ?>
                            </optgroup>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <hr>
                      <button type="submit" class="btn btn-warning">GERAR</button>
                    </div>
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
  <?php include '../recursos/views/footer.php'; ?>
</body>

</html>