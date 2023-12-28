<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

$token = base64_decode(filter_input(INPUT_GET, "token"));
$token2 = base64_decode(filter_input(INPUT_GET, "token2"));

$pdo1 = conectar("guarda");
$pdo2 = conectar("membros");

$sistema = base64_encode('GUARDA');

$idmembro = $_SESSION['auth_data']['id'];
$idusuario = $idmembro;

$consulta = $pdo2->prepare("SELECT * FROM usuarios WHERE id = $idusuario AND userativo = 'S'");
$consulta->execute();
$users = $consulta->fetchAll(PDO::FETCH_ASSOC);
$user = $users[0];

$nomeguerra = $user['nomeguerra'];
$idpgrad = $user['idpgrad'];

$data = date("d/m/Y");
?>
<!doctype html>
<html lang="pt-BR" class="fixed left-sidebar-collapsed">

<head>
  <?php include '../recursos/views/cabecalho.php'; ?>
</head>

<body>
  <div class="wrap">
    <div class="page-header">
      <?php include 'painel_usu.php'; ?>
    </div>
    <div class="page-body">
      <div class="left-sidebar">
        <?php include 'menu_opc.php'; ?>
      </div>
      <div class="content">
      <?php render_content_header('Entrada e Saída de Militares Fora do Expediente', 'fa fa-umbrella-beach'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-12">
            <div class="panel"><?php render_cabecalho_painel('TABELA DE LANÇAMENTOS DE '.$data, 'fa fa-check', true); ?>
              <div class="panel-content">
                <table id="responsive-table" class="tabela table table-striped table-hover responsive nowrap" width="100%">
                  <thead>
                    <tr>
                      <td align='center' valign='middle'><font size=3><strong>Ordem</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Quem lançou?</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Militar</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Situacao</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Entrada</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Saída</strong></font></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $rel_militares = 'rel_militares';
                    //$situacao_entrada_militares = 'Entrou DURANTE o expediente';
                    //$situacao_saida_militares = 'Saiu DURANTE o expediente';
                    $situacao_entrada2_militares = 'Entrou APÓS o expediente';
                    $situacao_saida2_militares = 'Saiu APÓS o expediente';
                    $consulta_rel = $pdo1->prepare("SELECT * FROM $rel_militares WHERE ((situacao = :situacao1 OR situacao = :situacao2) AND data = :data) ORDER BY id DESC");
                    $consulta_rel->bindParam(":situacao1", $situacao_entrada2_militares, PDO::PARAM_STR);
                    $consulta_rel->bindParam(":situacao2", $situacao_saida2_militares, PDO::PARAM_STR);
                    $consulta_rel->bindParam(":data", $data, PDO::PARAM_STR);
                    $consulta_rel->execute();
                    $consulta_rel_total = $consulta_rel->fetchAll(PDO::FETCH_ASSOC);
                    $consulta_total_registro = count($consulta_rel_total);
                    for ($i = 0; $i < $consulta_total_registro and $i < 200; $i++) {
                      $reg_militares = $consulta_rel_total[$i];
                      $consulta_usuario = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios 
                                                WHERE usuarios.id = :idmembro");
                      $consulta_usuario->bindParam(":idmembro", $reg_militares['idmembro'], PDO::PARAM_INT);
                      $consulta_usuario->execute();
                      while ($reg = $consulta_usuario->fetch(PDO::FETCH_ASSOC)) {
                        $nome_usuarios_militares = $reg['nomeguerra'];
                        $pg_usuarios_militares = getPGrad($reg['idpgrad']);
                      }
                      $consulta_usuario2 = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios 
                                                WHERE usuarios.id = :idusuario");
                      $consulta_usuario2->bindParam(":idusuario", $reg_militares['idusuario'], PDO::PARAM_INT);
                      $consulta_usuario2->execute();
                      while ($reg = $consulta_usuario2->fetch(PDO::FETCH_ASSOC)) {
                        $nome_usuarios_lancou_militares = $reg['nomeguerra'];
                        $pg_usuarios_lancou_militares = getPGrad($reg['idpgrad']);
                      } ?>
                      <tr>
                        <td align='center' valign='middle'><?php echo "" . $reg_militares['id'] . ""; ?></td>
                        <td align='center' valign='middle'><?php echo "" . $pg_usuarios_lancou_militares . " " . $nome_usuarios_lancou_militares . ""; ?></td>
                        <td align='center' valign='middle'><?php echo "" . $pg_usuarios_militares . " " . $nome_usuarios_militares . ""; ?></td>
                        <td align='center' valign='middle'><?php echo "" . $reg_militares['situacao'] . ""; ?></td>
                        <td align='center' valign='middle'><?php
                          if ($reg_militares['situacao'] == $situacao_saida_militares or $reg_militares['situacao'] == $situacao_saida2_militares) { echo "---";
                          } else { echo "" . $reg_militares['data'] . " " . $reg_militares['hora'] . "";
                          } ?></td>
                        <td align='center' valign='middle'><?php
                          if ($reg_militares['situacao'] == $situacao_entrada_militares or $reg_militares['situacao'] == $situacao_entrada2_militares) { echo "---";
                          } else { echo "" . $reg_militares['data'] . " " . $reg_militares['hora'] . "";
                          } ?></td>
                      </tr>
                    <?php } ?>
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
  <script type="text/javascript">
    $('.tabela').DataTable({
      "order": [
        [0, "desc"]
      ],
      "scrollY": "600px",
      "scrollCollapse": true,
      "paging": false
    });
  </script>
</body>

</html>
