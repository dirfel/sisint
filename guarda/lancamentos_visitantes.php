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
    <?php render_painel_usu('GUARDA', $_SESSION['nivel_guarda']); ?>
    </div>
    <div class="page-body">
      <div class="left-sidebar">
        <?php include 'menu_opc.php'; ?>
      </div>
      <div class="content">
      <?php render_content_header('Lançamentos de Entrada e Saída de Visitantes', 'fa fa-check'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-12">
            <div class="panel"><?php render_cabecalho_painel('RELAÇÃO DE VISITANTES DENTRO DO QUARTEL EM '.$data, 'fa fa-street-view', true); ?>
              <div class="panel-content">
                <table id="responsive-table" class="tabela table table-striped table-hover responsive nowrap" width="100%">
                  <thead>
                    <tr>
                      <td align='center' valign='middle'><font size=3><strong>Ordem</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Visitante</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Veículo</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Crachá</strong></font></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $situacao_entrada_visitantes = 'Entrou no Aquartelamento';
                    $situacao_saida_visitantes = 'Saiu do Aquartelamento';
                    $consulta_rel_visitantes = $pdo1->prepare("SELECT visitante.id, visitante.nomecompleto, visitante.tipo, visitante.idveiculo, visitante.cracha, visitante.celular, visitante.identidade, veiculo.placa, 
                      veiculo.marca, veiculo.modelo, veiculo.cor, veiculo.tipo AS tipo_veiculo FROM visitante LEFT JOIN veiculo ON (visitante.idveiculo = veiculo.id) 
                      WHERE visitante.situacao = '1'");
                    $consulta_rel_visitantes->execute();
                    $consulta_rel_total_visitantes = $consulta_rel_visitantes->fetchAll(PDO::FETCH_ASSOC);
                    $consulta_total_registro = count($consulta_rel_total_visitantes);
                    for ($i = 0; $i < $consulta_total_registro; $i++) {
                      $reg_visitantes = $consulta_rel_total_visitantes[$i];
                      $consulta_usuario = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios 
                                                WHERE usuarios.id = :idusuario");
                      $consulta_usuario->bindParam(":idusuario", $reg_visitantes['idusuario'], PDO::PARAM_INT);
                      $consulta_usuario->execute();
                      while ($reg = $consulta_usuario->fetch(PDO::FETCH_ASSOC)) {
                        $nome_usuarios_lancou_visitantes = $reg['nomeguerra'];
                        $pg_usuarios_lancou_visitantes = getPGrad($reg['idpgrad']);
                      }
                    ?>
                      <tr>
                        <td align='center' valign='middle'><?php echo "" . $reg_visitantes['id'] . ""; ?></td>
                        <td align='center' valign='middle'><?php echo "" . $reg_visitantes['nomecompleto'] . " (" . $reg_visitantes['tipo'] . ") (Tel: " . $reg_visitantes['celular'] . ") (Idt: " . $reg_visitantes['identidade'] . ")"; ?></td>
                        <td align='center' valign='middle'><?php
                          if ($reg_visitantes['idveiculo'] == 0) { echo "Nenhum Veículo";
                          } else { echo "" . $reg_visitantes['placa'] . " - " . $reg_visitantes['modelo'] . " - " . $reg_visitantes['marca'] . " - " . $reg_visitantes['cor'] . " - (" . $reg_visitantes['tipo_veiculo'] . ")";} ?></td>
                        <td align='center' valign='middle'><?php echo "" . $reg_visitantes['cracha'] . ""; ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-md-12">
            <div class="panel"><?php render_cabecalho_painel('TABELA DE LANÇAMENTOS DE '.$data, 'fa fa-check', true); ?>
              <div class="panel-content">
                <table id="responsive-table" class="tabela table table-striped table-hover responsive nowrap" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <td align='center' valign='middle'><font size=3><strong>Ordem</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Quem lançou?</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Visitante</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Veículo</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Situação</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Entrada</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Saída</strong></font></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $rel_visitantes = 'rel_visitantes';
                    $cad_veiculo = 'veiculo';
                    $cad_visitante = 'visitante';
                    $situacao_entrada_visitantes = 'Entrou no Aquartelamento';
                    $situacao_saida_visitantes = 'Saiu do Aquartelamento';
                    $consulta_rel_visitantes = $pdo1->prepare("SELECT $rel_visitantes.id, $rel_visitantes.idvisitante, $rel_visitantes.idusuario, $rel_visitantes.data, $rel_visitantes.hora, $rel_visitantes.situacao, $rel_visitantes.idveiculo, 
                                            $cad_visitante.nomecompleto, $cad_visitante.tipo AS tipo_visit, $cad_veiculo.placa, $cad_veiculo.modelo, $cad_veiculo.marca, $cad_veiculo.tipo AS tipo_veiculo FROM $rel_visitantes 
                                            LEFT JOIN $cad_visitante ON ($rel_visitantes.idvisitante = $cad_visitante.id) 
                                            LEFT JOIN $cad_veiculo ON ($rel_visitantes.idveiculo = $cad_veiculo.id) 
                                            WHERE $rel_visitantes.data = :data ORDER BY id DESC");
                    $consulta_rel_visitantes->bindParam(":data", $data, PDO::PARAM_STR);
                    $consulta_rel_visitantes->execute();
                    $consulta_rel_total_visitantes = $consulta_rel_visitantes->fetchAll(PDO::FETCH_ASSOC);
                    $consulta_total_registro = count($consulta_rel_total_visitantes);
                    for ($i = 0; $i < $consulta_total_registro; $i++) {
                      $reg_visitantes = $consulta_rel_total_visitantes[$i];
                      $consulta_usuario = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios 
                                                WHERE usuarios.id = :idusuario");
                      $consulta_usuario->bindParam(":idusuario", $reg_visitantes['idusuario'], PDO::PARAM_INT);
                      $consulta_usuario->execute();
                      while ($reg = $consulta_usuario->fetch(PDO::FETCH_ASSOC)) {
                        $nome_usuarios_lancou_visitantes = $reg['nomeguerra'];
                        $pg_usuarios_lancou_visitantes = getPGrad($reg['idpgrad']); } ?>
                      <tr>
                        <td align='center' valign='middle'><?php echo "" . $reg_visitantes['id'] . ""; ?></td>
                        <td align='center' valign='middle'><?php echo "" . $pg_usuarios_lancou_visitantes . " " . $nome_usuarios_lancou_visitantes . ""; ?></td>
                        <td align='center' valign='middle'><?php echo "" . $reg_visitantes['nomecompleto'] . " (" . $reg_visitantes['tipo_visit'] . ")"; ?></td>
                        <td align='center' valign='middle'><?php
                          if ($reg_visitantes['idveiculo'] == 0) { echo "<font size=1.0> Nenhum Veículo </font>";
                          } else { echo "<font size=1.0> " . $reg_visitantes['placa'] . " - " . $reg_visitantes['modelo'] . " - " . $reg_visitantes['marca'] . " - (" . $reg_visitantes['tipo_veiculo'] . ") </font>"; } ?></td>
                        <td align='center' valign='middle'><?php echo "" . $reg_visitantes['situacao'] . ""; ?></td>
                        <td align='center' valign='middle'><?php
                          if ($reg_visitantes['situacao'] == $situacao_saida_visitantes) { echo "---";
                          } else { echo "" . $reg_visitantes['data'] . " " . $reg_visitantes['hora'] . ""; } ?></td>
                        <td align='center' valign='middle'><?php
                          if ($reg_visitantes['situacao'] == $situacao_entrada_visitantes) { echo "---";
                          } else { echo "" . $reg_visitantes['data'] . " " . $reg_visitantes['hora'] . ""; } ?></td>
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
      "scrollY": "300px",
      "scrollCollapse": true,
      "paging": false
    });
  </script>
</body>

</html>