<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if ($_SESSION['auth_data']['contarancho'] < "2") {
  header('Location: index.php');
  exit();
}

$p1 = conectar("membros");
$sistema = base64_encode('SISTEMA DE ARRANCHAMENTO');

if ($_SESSION['auth_data']['contarancho'] == "2") {
  $msgm = "Você está acessando com a conta de FURRIEL. Poderá arranchar somente os usuários pertencentes a sua companhia.";
}
if ($_SESSION['auth_data']['contarancho'] == "3") {
  $msgm = "Você está acessando com a conta de APROVISIONADOR. Poderá arranchar TODOS os usuários cadastrados selecionados pela opção.";
}
if ($_SESSION['auth_data']['contarancho'] == "4") {
  $msgm = "Você está acessando com a conta de ADMINISTRADOR. Poderá arranchar TODOS os usuários cadastrados selecionados pela opção.";
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
    <?php render_painel_usu('ARRANCHAMENTO', $_SESSION['nivel_arranchamento']); ?>
    </div>
  </div>
  <div class="page-body">
    <div class="left-sidebar">
      <?php include 'menu_opc.php'; ?>
    </div>
  </div>
  <div class="content">
  <?php render_content_header('Arranchamento por Posto/Grad', 'fa fa-star'); ?>
    <div class="row animated fadeInUp">
      <?php include '../recursos/views/token.php'; ?>
      <form action="gravporpg.php" method="post">
        <div class="col-sm-12 col-md-6">
          <div class="panel"><?php render_cabecalho_painel('ARRANCHAR POR POSTO/GRADUAÇÃO', 'fa fa-star', true); ?>
            <div class="panel-content">
              <div class="row">
                <div class="col-sm-12"><?php render_pgrad_select('postograd', 30, true); ?></div>
                <div class="col-sm-12"><?php render_data_field('datarancho', true, 'Data:', null); ?></div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="inputMaxLength" class="control-label mb-sm">Marque a refeição:</label>
                    <div class="col-sm-12 ph-none">
                      <div class="input-group">
                        <div class="checkbox-custom checkbox-inline checkbox-primary">
                          <input type="checkbox" id="checkboxCustom2" name="cafe" value="SIM">
                          <label class="check" for="checkboxCustom2"><i class="fa fa-coffee" aria-hidden="true"></i> Café</label>
                        </div>
                        <div class="checkbox-custom checkbox-inline checkbox-primary ml-md">
                          <input type="checkbox" id="checkboxCustom3" name="almoco" value="SIM">
                          <label class="check" for="checkboxCustom3"><i class="fas fa-drumstick-bite" aria-hidden="true"></i> Almoço</label>
                        </div>
                        <div class="checkbox-custom checkbox-inline checkbox-primary ml-md">
                          <input type="checkbox" id="checkboxCustom4" name="jantar" value="SIM">
                          <label class="check" for="checkboxCustom4"><i class="fas fa-hotdog" aria-hidden="true"></i> Jantar</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-12"><hr><button type="submit" name="action" class="btn btn-warning">ARRANCHAR</button></div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
    <?php include '../recursos/views/scroll_to_top.php'; ?>
  </div>
  <?php include '../recursos/views/footer.php'; ?>
</body>

</html>