<?php
/**
 * Esse arquivo define a página de atualização de dados individuais
 */
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";


$idusuario = base64_decode(filter_input(INPUT_GET, "tkusr", FILTER_SANITIZE_SPECIAL_CHARS));

if ($_SESSION['auth_data']['contahd'] < "3" && $_SESSION['auth_data']['id'] != $idusuario) {
    header('Location: index.php?token2='.base64_encode('Você não possui permissão para isso'));
    exit();
  }

$pdo = conectar("membros");

$consulta = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
$consulta->bindParam(":id", $idusuario, PDO::PARAM_INT);
$consulta->execute();
$reg = $consulta->fetch(PDO::FETCH_ASSOC);

?>
<!doctype html>
<html lang="pt-BR" class="fixed left-sidebar-collapsed">

<head>
  <?php include '../recursos/views/cabecalho.php'; ?>
</head>

<body>
  <div class="wrap">
    <div class="page-header"><?php render_painel_usu('CONTROLE DE PESSOAL'); ?></div>
    <div class="page-body">
      <div class="left-sidebar">
        <?php include 'menu_opc.php'; ?>
      </div>
      <div class="content">
      <?php render_content_header('Atualizar Dados', 'fa fa-user-check'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <form action="<?php echo 'conf_usu_indiv.php?tkusr=' . base64_encode($idusuario) ?>" method="post">
            <div class="col-sm-12 col-md-12">
              <div class="panel"><?php render_cabecalho_painel('MEUS DADOS:', 'fa fa-pencil-alt', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <?php render_painel_dados_usuario($reg); ?>
                    <div class="col-md-12">
                      <hr>
                      <button type="submit" name="action" value='Cadastrou novo usuário' class="btn btn-warning">
                        ALTERAR
                      </button>
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