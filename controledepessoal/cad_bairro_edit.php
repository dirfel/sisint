<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
if ($_SESSION['nivel_plano_chamada'] == "Sem Acesso") {
    $msgerro = base64_encode('Usuário não possui acesso!');
    header('Location: ../sistemas/index.php?token=' . $msgerro);
    exit();
}
if (!isset($_POST['btn_edit_bairro'])) {
  header('Location: index.php');
  exit();
}

$idBairro = filter_input(INPUT_POST, "idBairro", FILTER_SANITIZE_NUMBER_INT);

$reg = listar_bairros($idBairro)[0];
$nomeBairro = $reg['bairro'];
$setorBairro = $reg['setor'];
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
      <?php render_content_header('Bairros', 'fa fa-codepen'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-6">
            <div class="panel"><?php render_cabecalho_painel('EDITAR BAIRRO: '. $nomeBairro, 'fa fa-edit', true); ?>
              <div class="panel-content">
                <form id="inline-validation" action="conf_bairro_edit.php?token=<?= base64_encode($idBairro) ?>" method="post">
                  <div class="row">
                    <div class="col-md-12"><?php render_custom_input('Editar nome do Bairro:', 'nomeBairro', 'nomeBairro', $nomeBairro, 30, '', true, false) ?></div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="inputMaxLength" class="control-label">Selecione o Setor:</label>
                        <select name="setorBairro" id="select2-example-basic" class="form-control" style="width: 100%" required>
                          <optgroup label='SETORES'>
                          <?php 
                          $pdo = conectar("membros");
                          $regs = listar_setores_de_bairros();
                          foreach($regs as $reg) {
                              echo ("<option value=" . $reg['id'] . (($reg['id'] == $setorBairro) ? ' selected' : '') . ">" . $reg['setor'] . "</option>");
                          } ?>
                          </optgroup>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <hr>
                        <button name="btn_conf_bairro_edit" type="submit" class="btn btn-primary" value="Editar Bairro">CONCLUIR EDIÇÃO</button>
                      </div>
                      </div>
                    </div>
                  </div>
                </form>
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