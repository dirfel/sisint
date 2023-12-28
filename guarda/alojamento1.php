<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if (!($_SESSION['nivel_guarda'] == "Anotador Gda" || $_SESSION['nivel_guarda'] == "Anotador Aloj" ||
    $_SESSION['nivel_guarda'] == "Oficial e Sargento" ||
    $_SESSION['nivel_guarda'] == "Supervisor" || $_SESSION['nivel_guarda'] == "Administrador")) {
  $msgerro = base64_encode('Usuário não possui acesso! Somente usuário: Anotador Aloj!');
  header('Location: index.php?token=' . $msgerro);
  exit();
}

$pdo = conectar("membros");
$data = date("d/m/Y");
$hora = date("H:i");

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
      <?php render_content_header('Entrada e Saída no Alojamento de Cb/Sd', 'fa fa-door-open'); ?>
        <form id="validation" action="alojamento2.php" method="post">
          <div class="row animated fadeInUp">
            <?php include '../recursos/views/token.php'; ?>
            <div class="col-sm-12 col-md-6">
              <div class="panel"><?php render_cabecalho_painel('LANÇAR ENTRADA OU SAÍDA NO ALOJ CB/SD:', 'fas fa-door-open', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-md-12"><?php render_militar_ativo_select('tkusr', 'select2-example-basic', true, true) ?></div>
                    <div class="col-md-6 mb-sm"><?php render_hora_field('hora', true, 'Hora:', true) ?></div>
                    <div class="col-md-6 mb-sm"><?php render_data_field('data', true, 'Data:', 'now') ?></div>
                    <div class="col-md-12">
                      <hr>
                      <button type="submit" name="action" value='Entrou no Aloj Cb/Sd' class="btn btn-darker-1" style="width: 220px;">ENTROU NO ALOJAMENTO</button>
                      <button type="submit" name="action" value='Saiu do Aloj Cb/Sd' class="btn btn-darker-2" style="width: 220px;">SAIU DO ALOJAMENTO</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </form>
      </div>
        <?php include '../recursos/views/scroll_to_top.php'; ?>
    </div>
  </div>
  <?php include '../recursos/views/footer.php'; ?>
</body>

</html>