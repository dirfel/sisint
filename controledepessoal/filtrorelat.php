<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
if ($_SESSION['nivel_plano_chamada'] == "Sem Acesso") {
    $msgerro = base64_encode('Usuário não possui acesso!');
    header('Location: ../sistemas/index.php?token=' . $msgerro);
    exit();
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
    <?php render_painel_usu('CONTROLE DE PESSOAL', $_SESSION['nivel_plano_chamada']); ?>
    </div>
    <div class="page-body">
      <div class="left-sidebar">
        <?php include 'menu_opc.php'; ?>
      </div>
      <div class="content">
      <?php render_content_header('Gerar Plano de Chamada', 'fa fa-paper-plane'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <form target="_blank" action="relatoriofinal.php" method="post">
            <div class="col-sm-12 col-md-6">
              <div class="panel"><?php render_cabecalho_painel('GERAR RELATÓRIO POR SELEÇÃO:', 'fa fa-paper-plane', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="inputMaxLength" class="control-label">Selecione o Posto/Graduação na lista:</label>
                        <div class="input-group mb-sm">
                          <span class="input-group-addon"><i class="fa fa-mortar-board"></i></span>
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
                    <div class="col-md-12"><?php render_subunidades_select('subunidade', true); ?></div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="inputMaxLength" class="control-label">Selecione o Setor na lista:</label>
                        <div class="input-group mb-sm">
                          <span class="input-group-addon"><i class="fa fa-map-marker-alt"></i></span>
                          <select name="setores" id="select2-example-basic3" class="form-control" style="width: 100%">
                            <option></option>
                            <optgroup label='Setores'>
                            <option value= 0>TODOS </option>
                            <?php
                            $regs = listar_setores_de_bairros();
                            foreach ($regs as $regx) {
                              echo ("<option value=" . $regx['id'] . ">" . $regx['setor'] . "</option>");
                            } ?>
                            </optgroup>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <hr>
                      <button type="submit" name="action" value='Gerou Plano de Chamada' class="btn btn-primary">GERAR</button>
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
  <?php include '../recursos/views/footer.php'; ?>
</body>

</html>