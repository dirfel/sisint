<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
if($_SESSION['nivel_fatos_observados'] == 'Sem Acesso') {
    header('Location: ../sistemas');
    exit();
}
$consultaCbSd = consultaMilitarSelection(14);
?>
<!doctype html>
<html lang="pt-BR" class="fixed">
<head><?php include '../recursos/views/cabecalho.php'; ?></head>
<body>
  <div class="wrap">
    <div class="page-header"><?php render_painel_usu('FATOS OBSERVADOS', $_SESSION['nivel_fatos_observados']); ?></div>
    <div class="page-body">
      <div class="left-sidebar"><?php include 'menu_opc.php'; ?></div>
      <div class="content">
      <?php render_content_header('Fatos Observados', 'fa fa-home'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-lg-6"> 
            <form role="form" method="post" action="registrar_conf.php" enctype="multipart/form-data">
              <div class="panel"><?php render_cabecalho_painel('REGISTRAR FO:', 'fab fa-gripfire', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-sm-12 col-md-6 mb-sm">
                      <div class="form-group">
                        <label for="selectMil" class="control-label">Selecione o Militar:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fas fa-group"></i></span>
                          <select name="id_usuario" class="form-control select" style="width: 100%" required>
                            <option></option>
                            <optgroup label='Militar da OM'>
                              <?php for ($i = 0; $i < count($consultaCbSd); $i++) { echo ("<option value=" . base64_encode($consultaCbSd[$i]['id']) . ">" . getPGrad($consultaCbSd[$i]['idpgrad']) . " - " . $consultaCbSd[$i]['nomecompleto'] . " (" . $consultaCbSd[$i]['nomeguerra'] . ")</option>"); } ?>
                            </optgroup>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12 col-md-6 mb-sm">
                      <div class="form-group">
                        <label for="selectFO" class="control-label">Insira o Tipo do FO:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fab fa-gripfire"></i></span>
                          <select name="tipo" class="form-control select" style="width: 100%" required>
                            <option></option>
                            <optgroup label='Fator Observado'>
                              <option value="P">Positivo</option>
                              <option value="N">Neutro</option>
                              <option value="B">Negativo</option>
                            </optgroup>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12 col-md-12">
                      <div class="form-group">
                        <label for="inputMaxLength" class="control-label">Insira a Observação:</label>
                        <textarea style="resize: vertical; max-height:350px; min-height:35px;" name="obs" id="obsID" class="form-control" rows="6" placeholder="Observação" maxlength="500" required><?= base64_decode($_GET['last'] ?? '')?></textarea>
                      </div>
                    </div>
                    <div class="col-sm-12 col-md-12">
                      <hr>
                      <button type="submit" name="action" value='Abriu Chamado' class="btn btn-primary">REGISTRAR</button>
                    </div>
                  </div>
                </div>
              </div>
          </form>
          </div>
            <div class="col-sm-12 col-md-6">
               <form target="_blank" id="validation" action="consultar_conf.php" method="post">
              <div class="panel"><?php render_cabecalho_painel('CONSULTAR FO', 'far fa-meh', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-md-12 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Selecione o Militar ou Grupo:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fas fa-group"></i></span>
                          <select name="militar" id="select2-example-basic" class="form-control" style="width: 100%" required>
                            <option></option>
                            <optgroup label='Grupo'>
                              <option value="<?= base64_encode('TODOS OS MILITARES') ?>">TODOS OS MILITARES</option>
                              <option value="<?= base64_encode('TODOS OS CABOS') ?>">TODOS OS CABOS</option>
                              <option value="<?= base64_encode('TODOS OS SOLDADOS EP') ?>">TODOS OS SOLDADOS EP</option>
                              <option value="<?= base64_encode('TODOS OS SOLDADOS EV') ?>">TODOS OS SOLDADOS EV</option>
                            <optgroup label='Militar da OM'>
                              <?php for ($i = 0; $i < count($consultaCbSd); $i++) { echo ("<option value=" . base64_encode($consultaCbSd[$i]['id']) . ">" . getPGrad($consultaCbSd[$i]['idpgrad']) . " - " . $consultaCbSd[$i]['nomecompleto'] . " (" . $consultaCbSd[$i]['nomeguerra'] . ")</option>"); } ?>
                            </optgroup>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <hr>
                      <button type="submit" name="action" value='Gerou Documento' class="btn btn-primary">CONSULTAR</button>
                    </div>
                  </div>
                </div>
            </div>
          </form>
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