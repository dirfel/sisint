<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

$pdo1 = conectar("guarda");
$pdo2 = conectar("membros");

$data = date("d/m/Y");
$prontoViatura = "Pronto de Viaturas";

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
      <?php render_content_header('Lançamentos de Entrada e Saída de Viaturas Militares', 'fa fa-bus'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-12">
            <div class="panel"><?php render_cabecalho_painel('RELAÇÃO DE VIATURAS MILITARES FORA DO QUARTEL EM '.$data, 'fa fa-bus', true); ?>
              <div class="panel-content">
                <table id="responsive-table" class="tabela table table-striped table-hover responsive nowrap" width="100%">
                  <thead>
                    <tr>
                      <td align='center' valign='middle'><font size=3><strong>Ordem Vtr</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Viatura</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Chefe de Viatura</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Motorista da Viatura</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Ficha</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Destino</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Odômetro</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Saída</strong></font></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $consulta = $pdo1->prepare("SELECT * FROM viatura WHERE situacao = '1' ORDER BY tipo, marca, modelo, placa ASC");
                    $consulta->execute();
                    $consulta_total = $consulta->fetchAll(PDO::FETCH_ASSOC);
                    $consulta_total_registro = count($consulta_total);
                    for ($i = 0; $i < $consulta_total_registro; $i++) {
                      $reg_viaturas = $consulta_total[$i];
                      $consulta2 = $pdo2->prepare("SELECT id, idpgrad, nomeguerra FROM usuarios WHERE id = :id ");
                      $consulta2->bindParam(":id", $reg_viaturas['idchvtr'], PDO::PARAM_INT);
                      $consulta2->execute();
                      while ($reg = $consulta2->fetch(PDO::FETCH_ASSOC)) {
                        $nomeguerra_chvtr = $reg['nomeguerra'];
                        $pgrad_chvtr = getPGrad($reg['idpgrad']);
                      }
                      $consulta3 = $pdo2->prepare("SELECT id, idpgrad, nomeguerra FROM usuarios WHERE id = :id ");
                      $consulta3->bindParam(":id", $reg_viaturas['idmtr'], PDO::PARAM_INT);
                      $consulta3->execute();
                      while ($reg = $consulta3->fetch(PDO::FETCH_ASSOC)) {
                        $nomeguerra_mtr = $reg['nomeguerra'];
                        $pgrad_mtr = getPGrad($reg['idpgrad']);
                      }
                      $consulta4 = $pdo1->prepare("SELECT * FROM rel_viaturas WHERE idvtr = :id ORDER BY id DESC LIMIT 1");
                      $consulta4->bindParam(":id", $reg_viaturas['id'], PDO::PARAM_INT);
                      $consulta4->execute();
                      while ($reg = $consulta4->fetch(PDO::FETCH_ASSOC)) {
                        $data2 = $reg['data'];
                        $hora = $reg['hora'];
                        $ficha = $reg['ficha'];
                        $odometro = $reg['odometro'];
                        $destino = $reg['destino'];
                      }
                    ?>
                      <tr>
                        <td align='center' valign='middle'><?= "" . $reg_viaturas['id'] . "" ?></td>
                        <td align='center' valign='middle'><?= "" . $reg_viaturas['placa'] . " - " . $reg_viaturas['modelo'] . " - " . $reg_viaturas['marca'] . " - (" . $reg_viaturas['tipo'] . ")" ?></td>
                        <td align='center' valign='middle'><?= "" . $pgrad_chvtr . " " . $nomeguerra_chvtr . "" ?></td>
                        <td align='center' valign='middle'><?= "" . $pgrad_mtr . " " . $nomeguerra_mtr . "" ?></td>
                        <td align='center' valign='middle'><?= "" . $ficha . "" ?></td>
                        <td align='center' valign='middle'><?= "" . $destino . "" ?></td>
                        <td align='center' valign='middle'><?= "" . $odometro . " Km" ?></td>
                        <td align='center' valign='middle'><?= ($reg_viaturas['situacao'] == $situacao_entrada_viaturas) ? "---" : "" . $data2 . " " . $hora . "" ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
                <div class="row">
                  <div class="col-md-12">
                    <form target="_blank" action="documentos_gerados.php?token='<?= base64_encode($prontoViatura) ?>'" method="post">
                      <hr>
                      <button type="submit" name="action" value='Gerou Documento' class="btn btn-primary"><i class="fas fa-print"></i> GERAR PRONTO DE VIATURAS
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-md-12">
            <div class="panel"><?php render_cabecalho_painel('TABELA RESUMIDA DE LANÇAMENTOS DE '.$data, 'fa fa-check', true); ?>
              <div class="panel-content">
                <table id="responsive-table" class="tabela table table-striped table-hover responsive nowrap" width="100%">
                  <thead>
                    <tr>
                      <td align='center' valign='middle'><font size=3><strong>Ordem</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Ficha</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Viatura</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Chefe de Viatura</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Motorista da Viatura</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Destino</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Distância</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Consumo</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Saída</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Entrada</strong></font></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $consulta_rel_viaturas = $pdo1->prepare("SELECT rel_viaturas.id, rel_viaturas.idusuario, rel_viaturas.idvtr, rel_viaturas.data, rel_viaturas.hora, rel_viaturas.situacao, rel_viaturas.ficha, rel_viaturas.idchvtr, rel_viaturas.idmtr, 
                                            rel_viaturas.odometro, rel_viaturas.destino, rel_viaturas.idsaida, viatura.placa, viatura.modelo, viatura.marca, viatura.tipo AS tipo_veiculo, viatura.combustivel, viatura.consumo FROM rel_viaturas 
                                            LEFT JOIN viatura ON (rel_viaturas.idvtr = viatura.id)
                                            WHERE (rel_viaturas.data = :data AND rel_viaturas.idsaida is NOT NULL) ORDER BY rel_viaturas.id DESC");
                    $consulta_rel_viaturas->bindParam(":data", $data, PDO::PARAM_STR);
                    $consulta_rel_viaturas->execute();
                    $consulta_rel_total_viaturas = $consulta_rel_viaturas->fetchAll(PDO::FETCH_ASSOC);
                    $consulta_total_registro_viaturas = count($consulta_rel_total_viaturas);
                    for ($i = 0; $i < $consulta_total_registro_viaturas; $i++) {
                      $reg_viaturas = $consulta_rel_total_viaturas[$i];
                      $consulta_usuario2 = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE usuarios.id = :idchvtr");
                      $consulta_usuario2->bindParam(":idchvtr", $reg_viaturas['idchvtr'], PDO::PARAM_INT);
                      $consulta_usuario2->execute();
                      while ($reg = $consulta_usuario2->fetch(PDO::FETCH_ASSOC)) {
                        $nome_usuarios_lancou_ch_vtr = $reg['nomeguerra'];
                        $pg_usuarios_lancou_ch_vtr = getPGrad($reg['idpgrad']);
                      }
                      $consulta_usuario3 = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE usuarios.id = :idmtr");
                      $consulta_usuario3->bindParam(":idmtr", $reg_viaturas['idmtr'], PDO::PARAM_INT);
                      $consulta_usuario3->execute();
                      while ($reg = $consulta_usuario3->fetch(PDO::FETCH_ASSOC)) {
                        $nome_usuarios_lancou_motorista = $reg['nomeguerra'];
                        $pg_usuarios_lancou_motorista = getPGrad($reg['idpgrad']);
                      }
                      $consulta_rel_viaturas2 = $pdo1->prepare("SELECT rel_viaturas.data, rel_viaturas.hora, rel_viaturas.odometro FROM rel_viaturas 
                      WHERE rel_viaturas.id = :id");
                      $consulta_rel_viaturas2->bindParam(":id", $reg_viaturas['idsaida'], PDO::PARAM_INT);
                      $consulta_rel_viaturas2->execute();
                      while ($reg = $consulta_rel_viaturas2->fetch(PDO::FETCH_ASSOC)) {
                        $reg_viaturas2_data = $reg['data'];
                        $reg_viaturas2_hora = $reg['hora'];
                        $reg_viaturas2_odometro = $reg['odometro'];
                      }
                    ?>
                      <tr>
                        <td align='center' valign='middle'><?= "" . $reg_viaturas['id'] . "" ?></td>
                        <td align='center' valign='middle'><?= "" . $reg_viaturas['ficha'] . "" ?></td>
                        <td align='center' valign='middle'><?= "" . $reg_viaturas['placa'] . " - " . $reg_viaturas['modelo'] . " - (" . $reg_viaturas['tipo_veiculo'] . ")" ?></td>
                        <td align='center' valign='middle'><?= "" . $pg_usuarios_lancou_ch_vtr . " " . $nome_usuarios_lancou_ch_vtr . "" ?></td>
                        <td align='center' valign='middle'><?= "" . $pg_usuarios_lancou_motorista . " " . $nome_usuarios_lancou_motorista . "" ?></td>
                        <td align='center' valign='middle'><?= "" . $reg_viaturas['destino'] . "" ?></td>
                        <td align='center' valign='middle'><?= "" . ($reg_viaturas['odometro'] - $reg_viaturas2_odometro) . " Km" ?></td>
                        <td align='center' valign='middle'><?= "" . number_format(($ditancia / $reg_viaturas['consumo']), 2, ',', '.') . "L " . $reg_viaturas['combustivel'] ?></td>
                        <td align='center' valign='middle'><?= "" . $reg_viaturas2_data . " " . $reg_viaturas2_hora . " (" . $reg_viaturas2_odometro . " Km)" ?></td>
                        <td align='center' valign='middle'><?= "" . $reg_viaturas['data'] . " " . $reg_viaturas['hora'] . " (" . $reg_viaturas['odometro'] . " Km)" ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-md-12">
            <div class="panel"><?php render_cabecalho_painel('TABELA COMPLETA DE LANÇAMENTOS DE '.$data, 'fa fa-check', true); ?>
              <div class="panel-content">
                <table id="responsive-table" class="tabela table table-striped table-hover responsive nowrap" width="100%">
                  <thead>
                    <tr>
                      <td align='center' valign='middle'><font size=3><strong>Ordem</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Quem lançou?</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Ficha</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Viatura</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Chefe de Viatura</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Motorista da Viatura</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Destino</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Odômetro</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Saída</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Entrada</strong></font></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $situacao_entrada_viaturas = 'Entrou no Aquartelamento';
                    $situacao_saida_viaturas = 'Saiu do Aquartelamento';
                    $consulta_rel_viaturas = $pdo1->prepare("SELECT rel_viaturas.id, rel_viaturas.idusuario, rel_viaturas.idvtr, rel_viaturas.data, rel_viaturas.hora, rel_viaturas.situacao, rel_viaturas.ficha, rel_viaturas.idchvtr, rel_viaturas.idmtr, 
                                            rel_viaturas.odometro, rel_viaturas.destino , viatura.placa, viatura.modelo, viatura.marca, viatura.tipo AS tipo_veiculo FROM rel_viaturas 
                                            LEFT JOIN viatura ON (rel_viaturas.idvtr = viatura.id)
                                            WHERE rel_viaturas.data = :data ORDER BY rel_viaturas.id DESC");
                    $consulta_rel_viaturas->bindParam(":data", $data, PDO::PARAM_STR);
                    $consulta_rel_viaturas->execute();
                    $consulta_rel_total_viaturas = $consulta_rel_viaturas->fetchAll(PDO::FETCH_ASSOC);
                    $consulta_total_registro_viaturas = count($consulta_rel_total_viaturas);
                    for ($i = 0; $i < $consulta_total_registro_viaturas; $i++) {
                      $reg_viaturas = $consulta_rel_total_viaturas[$i];
                      $consulta_usuario = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE usuarios.id = :idusuario");
                      $consulta_usuario->bindParam(":idusuario", $reg_viaturas['idusuario'], PDO::PARAM_INT);
                      $consulta_usuario->execute();
                      while ($reg = $consulta_usuario->fetch(PDO::FETCH_ASSOC)) {
                        $nome_usuarios_lancou_viaturas = $reg['nomeguerra'];
                        $pg_usuarios_lancou_viaturas = getPGrad($reg['idpgrad']);
                      }
                      $consulta_usuario2 = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE usuarios.id = :idchvtr");
                      $consulta_usuario2->bindParam(":idchvtr", $reg_viaturas['idchvtr'], PDO::PARAM_INT);
                      $consulta_usuario2->execute();
                      while ($reg = $consulta_usuario2->fetch(PDO::FETCH_ASSOC)) {
                        $nome_usuarios_lancou_ch_vtr = $reg['nomeguerra'];
                        $pg_usuarios_lancou_ch_vtr = getPGrad($reg['idpgrad']);
                      }
                      $consulta_usuario3 = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE usuarios.id = :idmtr");
                      $consulta_usuario3->bindParam(":idmtr", $reg_viaturas['idmtr'], PDO::PARAM_INT);
                      $consulta_usuario3->execute();
                      while ($reg = $consulta_usuario3->fetch(PDO::FETCH_ASSOC)) {
                        $nome_usuarios_lancou_motorista = $reg['nomeguerra'];
                        $pg_usuarios_lancou_motorista = getPGrad($reg['idpgrad']);
                      } ?>
                      <tr>
                        <td align='center' valign='middle'><?= "" . $reg_viaturas['id'] . "" ?></td>
                        <td align='center' valign='middle'><?= "" . $pg_usuarios_lancou_viaturas . " " . $nome_usuarios_lancou_viaturas . "" ?></td>
                        <td align='center' valign='middle'><?= "" . $reg_viaturas['ficha'] . "" ?></td>
                        <td align='center' valign='middle'><?= "" . $reg_viaturas['placa'] . " - " . $reg_viaturas['modelo'] . " - (" . $reg_viaturas['tipo_veiculo'] . ")" ?></td>
                        <td align='center' valign='middle'><?= "" . $pg_usuarios_lancou_ch_vtr . " " . $nome_usuarios_lancou_ch_vtr . "" ?></td>
                        <td align='center' valign='middle'><?= "" . $pg_usuarios_lancou_motorista . " " . $nome_usuarios_lancou_motorista . "" ?></td>
                        <td align='center' valign='middle'><?= "" . $reg_viaturas['destino'] . "" ?></td>
                        <td align='center' valign='middle'><?= "" . $reg_viaturas['odometro'] . "" ?></td>
                        <td align='center' valign='middle'><?= ($reg_viaturas['situacao'] == $situacao_entrada_viaturas) ? "---" : "" . $reg_viaturas['data'] . " " . $reg_viaturas['hora'] . "" ?></td>
                        <td align='center' valign='middle'><?= ($reg_viaturas['situacao'] == $situacao_saida_viaturas) ? "---" : "" . $reg_viaturas['data'] . " " . $reg_viaturas['hora'] . "" ?></td>
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