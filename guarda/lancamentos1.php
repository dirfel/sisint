<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if (!($_SESSION['nivel_guarda'] == "Cabo Gda" || $_SESSION['nivel_guarda'] == "Oficial e Sargento" || $_SESSION['nivel_guarda'] == "Supervisor" || $_SESSION['nivel_guarda'] == "Administrador")) {
  $msgerro = base64_encode('Usuário não possui acesso! Somente usuário: Cabo Gda, Oficial e Sargento!');
  header('Location: index.php?token=' . $msgerro);
  exit();
}
$pdo1 = conectar("guarda");
$pdo2 = conectar("membros");
?>
<!doctype html>
<html lang="pt-BR" class="fixed left-sidebar-collapsed">
<head><?php include '../recursos/views/cabecalho.php'; ?></head>
<body>
  <div class="wrap">
    <div class="page-header"><?php render_painel_usu('GUARDA', $_SESSION['nivel_guarda']); ?></div>
    <div class="page-body">
      <div class="left-sidebar"><?php include 'menu_opc.php'; ?></div>
      <div class="content">
      <?php render_content_header('Lançamentos Gerais', 'fa fa-check'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-12">
            <div class="panel"><?php render_cabecalho_painel('LANÇAMENTOS DE MILITARES:', 'fa fa-group', true); ?>
              <div class="panel-content">
                <table id="responsive-table" class="tabela table table-striped table-hover responsive nowrap" width="100%">
                  <thead>
                    <tr>
                      <td align='center' valign='middle' width=5%><font size=3><strong>Excluir</strong></font></td>
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
                    $situacao_entrada_militares = 'Entrou DURANTE o expediente';
                    $situacao_saida_militares = 'Saiu DURANTE o expediente';
                    $situacao_entrada2_militares = 'Entrou APÓS o expediente';
                    $situacao_saida2_militares = 'Saiu APÓS o expediente';
                    $consulta_rel = $pdo1->prepare("SELECT * FROM $rel_militares ORDER BY id DESC");
                    $consulta_rel->execute();
                    $consulta_rel_total = $consulta_rel->fetchAll(PDO::FETCH_ASSOC);
                    $consulta_total_registro = count($consulta_rel_total);
                    for ($i = 0; $i < $consulta_total_registro and $i < 60; $i++) {
                      $reg_militares = $consulta_rel_total[$i];
                      $consulta_usuario = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE id = :idmembro");
                      $consulta_usuario->bindParam(":idmembro", $reg_militares['idmembro'], PDO::PARAM_INT);
                      $consulta_usuario->execute();
                      while ($reg = $consulta_usuario->fetch(PDO::FETCH_ASSOC)) {
                        $nome_usuarios_militares = $reg['nomeguerra'];
                        $pg_usuarios_militares = getPGrad($reg['idpgrad']);
                      }
                      $consulta_usuario2 = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE usuarios.id = :idusuario");
                      $consulta_usuario2->bindParam(":idusuario", $reg_militares['idusuario'], PDO::PARAM_INT);
                      $consulta_usuario2->execute();
                      while ($reg = $consulta_usuario2->fetch(PDO::FETCH_ASSOC)) {
                        $nome_usuarios_lancou_militares = $reg['nomeguerra'];
                        $pg_usuarios_lancou_militares = getPGrad($reg['idpgrad']);
                      } ?>
                      <tr>
                        <td align='center' valign='middle'>
                          <!-- Button trigger modal -->
                          <button type="button" class="btn btn-danger" style="font-size: 10px; padding: 0px 8px;" data-toggle="modal" data-target="#myModal<?php echo ($reg_militares['id']); ?><?php echo ($rel_militares); ?>">
                            <i class="fa fa-trash" aria-hidden="true"></i></button>
                          <!-- Modal -->
                          <div class="modal fade" id="myModal<?= ($reg_militares['id']) ?><?= ($rel_militares) ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                              <form id="inline-validation" action="lancamentos2.php?id=<?= base64_encode($reg_militares['id']) ?>&rel=<?php echo base64_encode($rel_militares); ?>" method="post">
                                <div class="modal-content">
                                  <div class="modal-header modal-danger">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Deseja excluir lançamento?</h4>
                                  </div>
                                  <div class="modal-body">Ordem: <?= $reg_militares['id'] ?></div>
                                  <div class="modal-footer">
                                    <button type="submit" name="action" value="Excluiu lançamento de Militares" class="btn btn-danger" id="action">EXCLUIR LANÇAMENTO</button>
                                  </div>
                                </div>
                              </form>
                            </div>
                          </div>
                        </td>
                        <td align='center' valign='middle'>
                          <?= "" . $reg_militares['id'] . ""; ?></td>
                        <td align='center' valign='middle'><?php echo "" . $pg_usuarios_lancou_militares . " " . $nome_usuarios_lancou_militares . ""; ?></td>
                        <td align='center' valign='middle'><?php echo "" . $pg_usuarios_militares . " " . $nome_usuarios_militares . ""; ?></td>
                        <td align='center' valign='middle'><?php echo "" . $reg_militares['situacao'] . ""; ?></td>
                        <td align='center' valign='middle'><?php
                          if ($reg_militares['situacao'] == $situacao_saida_militares or $reg_militares['situacao'] == $situacao_saida2_militares) { echo "---";
                          } else { echo "" . $reg_militares['data'] . " " . $reg_militares['hora'] . ""; } ?></td>
                        <td align='center' valign='middle'><?php
                          if ($reg_militares['situacao'] == $situacao_entrada_militares or $reg_militares['situacao'] == $situacao_entrada2_militares) { echo "---";
                          } else { echo "" . $reg_militares['data'] . " " . $reg_militares['hora'] . ""; } ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-md-12">
            <div class="panel"><?php render_cabecalho_painel('LANÇAMENTOS DE VISITANTES E VEÍCULOS:', 'fa fa-street-view', true); ?>
              <div class="panel-content">
                <table id="responsive-table" class="tabela table table-striped table-hover responsive nowrap" width="100%">
                  <thead>
                    <tr>
                      <td align='center' valign='middle' width=5%><font size=3><strong>Excluir</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Quem lançou?</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Visitante</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Veículo</strong></font></td>
                      <!-- <td align='center' valign='middle'><font size=3><strong>Situação</strong></font></td> -->
                      <td align='center' valign='middle'><font size=3><strong>Entrada</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Saída</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Destino</strong></font></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $rel_visitantes = 'rel_visitantes';
                    $cad_veiculo = 'veiculo';
                    $cad_visitante = 'visitante';
                    $situacao_entrada_visitantes = 'Entrou no Aquartelamento';
                    $situacao_saida_visitantes = 'Saiu do Aquartelamento';
                    $consulta_rel_visitantes = $pdo1->prepare("SELECT $rel_visitantes.id, $rel_visitantes.idvisitante, $rel_visitantes.destino, $rel_visitantes.idusuario, $rel_visitantes.data, $rel_visitantes.hora, $rel_visitantes.situacao, $rel_visitantes.idveiculo, 
                                            $cad_visitante.nomecompleto, $cad_visitante.tipo AS tipo_visit, $cad_veiculo.placa, $cad_veiculo.modelo, $cad_veiculo.marca, $cad_veiculo.tipo AS tipo_veiculo FROM $rel_visitantes 
                                            LEFT JOIN $cad_visitante ON ($rel_visitantes.idvisitante = $cad_visitante.id) 
                                            LEFT JOIN $cad_veiculo ON ($rel_visitantes.idveiculo = $cad_veiculo.id) 
                                            ORDER BY id DESC");
                    $consulta_rel_visitantes->execute();
                    $consulta_rel_total_visitantes = $consulta_rel_visitantes->fetchAll(PDO::FETCH_ASSOC);
                    $consulta_total_registro = count($consulta_rel_total_visitantes);
                    for ($i = 0; $i < $consulta_total_registro and $i < 60; $i++) {
                      $reg_visitantes = $consulta_rel_total_visitantes[$i];
                      $consulta_usuario = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE usuarios.id = :idusuario");
                      $consulta_usuario->bindParam(":idusuario", $reg_visitantes['idusuario'], PDO::PARAM_INT);
                      $consulta_usuario->execute();
                      while ($reg = $consulta_usuario->fetch(PDO::FETCH_ASSOC)) {
                        $nome_usuarios_lancou_visitantes = $reg['nomeguerra'];
                        $pg_usuarios_lancou_visitantes = getPGrad($reg['idpgrad']);
                      } ?>
                      <tr>
                        <td align='center' valign='middle'>
                          <!-- Button trigger modal -->
                          <button type="button" class="btn btn-danger" style="font-size: 10px; padding: 0px 8px;" data-toggle="modal" data-target="#myModal<?php echo ($reg_visitantes['id']); ?><?php echo ($rel_visitantes); ?>">
                            <i class="fa fa-trash" aria-hidden="true"></i></button>
                          <!-- Modal -->
                          <div class="modal fade" id="myModal<?php echo ($reg_visitantes['id']); ?><?php echo ($rel_visitantes); ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                              <form id="inline-validation" action="lancamentos3.php?id=<?php echo base64_encode($reg_visitantes['id']); ?>&rel=<?php echo base64_encode($rel_visitantes); ?>" method="post">
                                <div class="modal-content">
                                  <div class="modal-header modal-danger">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Deseja excluir lançamento?</h4>
                                  </div>
                                  <div class="modal-body">Ordem: <?php echo $reg_visitantes['id']; ?>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="submit" name="action" value="Excluiu lançamento de Visitantes" class="btn btn-danger" id="action">EXCLUIR LANÇAMENTO</button>
                                  </div>
                                </div>
                              </form>
                            </div>
                          </div>
                        </td>
                        <!-- <td align='center' valign='middle'><?php // echo "" . $reg_visitantes['id'] . ""; ?></td> -->
                        <td align='center' valign='middle'><?php echo "" . $pg_usuarios_lancou_visitantes . " " . $nome_usuarios_lancou_visitantes . ""; ?></td>
                        <td align='center' valign='middle'><?php echo "" . $reg_visitantes['nomecompleto'] . " (" . $reg_visitantes['tipo_visit'] . ")"; ?></td>
                        <td align='center' valign='middle'><?php
                          if ($reg_visitantes['idveiculo'] == 0) { echo "<font size=1.0> Nenhum Veículo </font>";
                          } else { echo "<font size=1.0> " . $reg_visitantes['placa'] . " - " . $reg_visitantes['modelo'] . " - " . $reg_visitantes['marca'] . " - (" . $reg_visitantes['tipo_veiculo'] . ") </font>"; } ?></td>
                        <!-- <td align='center' valign='middle'><?php //echo "" . $reg_visitantes['situacao'] . ""; ?></td> -->
                        <td align='center' valign='middle'><?php
                          if ($reg_visitantes['situacao'] == $situacao_saida_visitantes) { echo "---";
                          } else { echo "" . $reg_visitantes['data'] . " " . $reg_visitantes['hora'] . ""; } ?></td>
                        <td align='center' valign='middle'><?php
                          if ($reg_visitantes['situacao'] == $situacao_entrada_visitantes) { echo "---";
                          } else { echo "" . $reg_visitantes['data'] . " " . $reg_visitantes['hora'] . ""; } ?></td>
                        <td align='center' valign='middle'><?php echo "" . $reg_visitantes['destino'] . ""; ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-md-12">
            <div class="panel"><?php render_cabecalho_painel('LANÇAMENTOS DE VIATURAS MILITARES:', 'fa fa-bus', true); ?>
              <div class="panel-content">
                <table id="responsive-table" class="tabela table table-striped table-hover responsive nowrap" width="100%">
                  <thead>
                    <tr>
                      <td align='center' valign='middle' width=5%><font size=3><strong>Excluir</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Ordem</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Quem lançou?</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Ficha</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Viatura</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Chefe de Viatura</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Motorista da Viatura</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Destino</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Odômetro</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Entrada</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Saída</strong></font></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $rel_viaturas = 'rel_viaturas';
                    $cad_viatura = 'viatura';
                    $situacao_entrada_viaturas = 'Entrou no Aquartelamento';
                    $situacao_saida_viaturas = 'Saiu do Aquartelamento';
                    $consulta_rel_viaturas = $pdo1->prepare("SELECT $rel_viaturas.id, $rel_viaturas.idusuario, $rel_viaturas.idvtr, $rel_viaturas.data, $rel_viaturas.hora, $rel_viaturas.situacao, $rel_viaturas.ficha, $rel_viaturas.idchvtr, $rel_viaturas.idmtr, 
                                            $rel_viaturas.odometro, $rel_viaturas.destino , $cad_viatura.placa, $cad_viatura.modelo, $cad_viatura.marca, $cad_viatura.tipo AS tipo_veiculo FROM $rel_viaturas 
                                            LEFT JOIN $cad_viatura ON ($rel_viaturas.idvtr = $cad_viatura.id)
                                            ORDER BY $rel_viaturas.id DESC");
                    $consulta_rel_viaturas->execute();
                    $consulta_rel_total_viaturas = $consulta_rel_viaturas->fetchAll(PDO::FETCH_ASSOC);
                    $consulta_total_registro_viaturas = count($consulta_rel_total_viaturas);
                    for ($i = 0; $i < $consulta_total_registro_viaturas and $i < 50; $i++) {
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
                        <td align='center' valign='middle'>
                          <!-- Button trigger modal -->
                          <button type="button" class="btn btn-danger" style="font-size: 10px; padding: 0px 8px;" data-toggle="modal" data-target="#myModal<?php echo ($reg_viaturas['id']); ?><?php echo ($rel_viaturas); ?>">
                            <i class="fa fa-trash" aria-hidden="true"></i></button>
                          <!-- Modal -->
                          <div class="modal fade" id="myModal<?php echo ($reg_viaturas['id']); ?><?php echo ($rel_viaturas); ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                              <form id="inline-validation" action="lancamentos4.php?id=<?php echo base64_encode($reg_viaturas['id']); ?>&rel=<?php echo base64_encode($rel_viaturas); ?>" method="post">
                                <div class="modal-content">
                                  <div class="modal-header modal-danger">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Deseja excluir lançamento?</h4>
                                  </div>
                                  <div class="modal-body">Ordem: <?php echo $reg_viaturas['id']; ?>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="submit" name="action" value="Excluiu lançamento Vtr" class="btn btn-danger" id="action">EXCLUIR LANÇAMENTO</button>
                                  </div>
                                </div>
                              </form>
                            </div>
                          </div>
                        </td>
                        <td align='center' valign='middle'><?php echo "" . $reg_viaturas['id'] . ""; ?></td>
                        <td align='center' valign='middle'><?php echo "" . $pg_usuarios_lancou_viaturas . " " . $nome_usuarios_lancou_viaturas . ""; ?></td>
                        <td align='center' valign='middle'><?php echo "" . $reg_viaturas['ficha'] . ""; ?></td>
                        <td align='center' valign='middle'><?php echo "" . $reg_viaturas['placa'] . " - " . $reg_viaturas['modelo'] . " - (" . $reg_viaturas['tipo_veiculo'] . ")"; ?></td>
                        <td align='center' valign='middle'><?php echo "" . $pg_usuarios_lancou_ch_vtr . " " . $nome_usuarios_lancou_ch_vtr . ""; ?></td>
                        <td align='center' valign='middle'><?php echo "" . $pg_usuarios_lancou_motorista . " " . $nome_usuarios_lancou_motorista . ""; ?></td>
                        <td align='center' valign='middle'><?php echo "" . $reg_viaturas['destino'] . ""; ?></td>
                        <td align='center' valign='middle'><?php echo "" . $reg_viaturas['odometro'] . ""; ?></td>
                        <td align='center' valign='middle'><?php
                          if ($reg_viaturas['situacao'] == $situacao_saida_viaturas) { echo "---";
                          } else { echo "" . $reg_viaturas['data'] . " " . $reg_viaturas['hora'] . "";
                          } ?></td>
                        <td align='center' valign='middle'><?php
                          if ($reg_viaturas['situacao'] == $situacao_entrada_viaturas) { echo "---";
                          } else { echo "" . $reg_viaturas['data'] . " " . $reg_viaturas['hora'] . ""; } ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-md-12">
            <div class="panel"><?php render_cabecalho_painel('LANÇAMENTOS DO ALOJAMENTO DE CABO E SOLDADO:', 'fa fa-bed', true); ?>
              <div class="panel-content">
                <table id="responsive-table" class="tabela table table-striped table-hover responsive nowrap" width="100%">
                  <thead>
                    <tr>
                      <td align='center' valign='middle' width=5%><font size=3><strong>Excluir</strong></font></td>
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
                    $rel_alojamento = 'rel_alojamento';
                    $situacao_entrada_militares = 'Entrou no Aloj Cb/Sd';
                    $situacao_saida_militares = 'Saiu do Aloj Cb/Sd';
                    $consulta_rel_aloj = $pdo1->prepare("SELECT * FROM $rel_alojamento ORDER BY id DESC");
                    $consulta_rel_aloj->execute();
                    $consulta_rel_total_aloj = $consulta_rel_aloj->fetchAll(PDO::FETCH_ASSOC);
                    $consulta_total_registro_aloj = count($consulta_rel_total_aloj);
                    for ($i = 0; $i < $consulta_total_registro_aloj and $i < 60; $i++) {
                      $reg_alojamento = $consulta_rel_total_aloj[$i];
                      $consulta_usuario = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE id = :idmembro");
                      $consulta_usuario->bindParam(":idmembro", $reg_alojamento['idmembro'], PDO::PARAM_INT);
                      $consulta_usuario->execute();
                      while ($reg = $consulta_usuario->fetch(PDO::FETCH_ASSOC)) {
                        $nome_usuarios_alojamento = $reg['nomeguerra'];
                        $pg_usuarios_alojamento = getPGrad($reg['idpgrad']);
                      }
                      $consulta_usuario2 = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE usuarios.id = :idusuario");
                      $consulta_usuario2->bindParam(":idusuario", $reg_alojamento['idusuario'], PDO::PARAM_INT);
                      $consulta_usuario2->execute();
                      while ($reg = $consulta_usuario2->fetch(PDO::FETCH_ASSOC)) {
                        $nome_usuarios_lancou_alojamento = $reg['nomeguerra'];
                        $pg_usuarios_lancou_alojamento = getPGrad($reg['idpgrad']);
                      } ?>
                      <tr>
                        <td align='center' valign='middle'>
                          <!-- Button trigger modal -->
                          <button type="button" class="btn btn-danger" style="font-size: 10px; padding: 0px 8px;" data-toggle="modal" data-target="#myModal<?php echo ($reg_alojamento['id']); ?><?php echo ($rel_alojamento); ?>">
                            <i class="fa fa-trash" aria-hidden="true"></i></button>
                          <!-- Modal -->
                          <div class="modal fade" id="myModal<?php echo ($reg_alojamento['id']); ?><?php echo ($rel_alojamento); ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                              <form id="inline-validation" action="lancamentos2.php?id=<?php echo base64_encode($reg_alojamento['id']); ?>&rel=<?php echo base64_encode($rel_alojamento); ?>" method="post">
                                <div class="modal-content">
                                  <div class="modal-header modal-danger">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Deseja excluir lançamento?</h4>
                                  </div>
                                  <div class="modal-body">Ordem: <?php echo $reg_alojamento['id']; ?>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="submit" name="action" value="Excluiu lançamento de Militares" class="btn btn-danger" id="action">EXCLUIR LANÇAMENTO</button>
                                  </div>
                                </div>
                              </form>
                            </div>
                          </div>
                        </td>
                        <td align='center' valign='middle'><?php echo "" . $reg_alojamento['id'] . ""; ?></td>
                        <td align='center' valign='middle'><?php echo "" . $pg_usuarios_lancou_alojamento . " " . $nome_usuarios_lancou_alojamento . ""; ?></td>
                        <td align='center' valign='middle'><?php echo "" . $pg_usuarios_alojamento . " " . $nome_usuarios_alojamento . ""; ?></td>
                        <td align='center' valign='middle'><?php echo "" . $reg_alojamento['situacao'] . ""; ?></td>
                        <td align='center' valign='middle'><?php
                          if ($reg_alojamento['situacao'] == $situacao_saida_militares) { echo "---";
                          } else { echo "" . $reg_alojamento['data'] . " " . $reg_alojamento['hora'] . ""; } ?></td>
                        <td align='center' valign='middle'><?php
                          if ($reg_alojamento['situacao'] == $situacao_entrada_militares) { echo "---";
                          } else { echo "" . $reg_alojamento['data'] . " " . $reg_alojamento['hora'] . ""; } ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-md-12">
            <div class="panel"><?php render_cabecalho_painel('LANÇAMENTOS DE PERNOITE DE MILITARES E VISITANTES:', 'fa fa-bed', true); ?>
              <div class="panel-content">
                <table id="responsive-table" class="tabela table table-striped table-hover responsive nowrap" width="100%">
                  <thead>
                    <tr>
                      <td align='center' valign='middle' width=5%><font size=3><strong>Excluir</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Ordem</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Quem lançou?</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Militar</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Visitante</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Alojamento</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Data</strong></font></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $rel_pernoite = 'rel_pernoite';
                    $consulta_rel_pernoite = $pdo1->prepare("SELECT * FROM $rel_pernoite
                                            ORDER BY id DESC");
                    $consulta_rel_pernoite->execute();
                    $consulta_rel_total_pernoite = $consulta_rel_pernoite->fetchAll(PDO::FETCH_ASSOC);
                    $consulta_total_registro_pernoite = count($consulta_rel_total_pernoite);
                    for ($i = 0; $i < $consulta_total_registro_pernoite and $i < 30; $i++) {
                      $reg_pernoite = $consulta_rel_total_pernoite[$i];
                      $consulta_usuario = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE usuarios.id = :idusuario");
                      $consulta_usuario->bindParam(":idusuario", $reg_pernoite['idusuario'], PDO::PARAM_INT);
                      $consulta_usuario->execute();
                      while ($reg = $consulta_usuario->fetch(PDO::FETCH_ASSOC)) {
                        $nome_usuarios_lancou_pernoite = $reg['nomeguerra'];
                        $pg_usuarios_lancou_pernoite = getPGrad($reg['idpgrad']);
                      }
                      $consulta_usuario2 = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE id = :idmembro");
                      $consulta_usuario2->bindParam(":idmembro", $reg_pernoite['idmembro'], PDO::PARAM_INT);
                      $consulta_usuario2->execute();
                      while ($reg = $consulta_usuario2->fetch(PDO::FETCH_ASSOC)) {
                        $nome_usuarios_pernoite = $reg['nomeguerra'];
                        $pg_usuarios_pernoite = getPGrad($reg['idpgrad']);
                      }
                      $consulta_usuario3 = $pdo1->prepare("SELECT nomecompleto FROM visitante WHERE id = :id");
                      $consulta_usuario3->bindParam(":id", $reg_pernoite['idvisitante'], PDO::PARAM_INT);
                      $consulta_usuario3->execute();
                      while ($reg = $consulta_usuario3->fetch(PDO::FETCH_ASSOC)) {
                        $nome_visitante = $reg['nomecompleto'];
                      } ?>
                      <tr>
                        <td align='center' valign='middle'>
                          <!-- Button trigger modal -->
                          <button type="button" class="btn btn-danger" style="font-size: 10px; padding: 0px 8px;" data-toggle="modal" data-target="#myModal<?php echo ($reg_pernoite['id']); ?><?php echo ($rel_pernoite); ?>">
                            <i class="fa fa-trash" aria-hidden="true"></i></button>
                          <!-- Modal -->
                          <div class="modal fade" id="myModal<?php echo ($reg_pernoite['id']); ?><?php echo ($rel_pernoite); ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                              <form id="inline-validation" action="lancamentos5.php?id=<?php echo base64_encode($reg_pernoite['id']); ?>&rel=<?php echo base64_encode($rel_pernoite); ?>" method="post">
                                <div class="modal-content">
                                  <div class="modal-header modal-danger">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Deseja excluir lançamento?</h4>
                                  </div>
                                  <div class="modal-body">Ordem: <?php echo $reg_pernoite['id']; ?>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="submit" name="action" value="Excluiu lançamento de Pernoite" class="btn btn-danger" id="action">EXCLUIR LANÇAMENTO</button>
                                  </div>
                                </div>
                              </form>
                            </div>
                          </div>
                        </td>
                        <td align='center' valign='middle'><?php echo "" . $reg_pernoite['id'] . ""; ?></td>
                        <td align='center' valign='middle'><?php echo "" . $pg_usuarios_lancou_pernoite . " " . $nome_usuarios_lancou_pernoite . ""; ?></td>
                        <td align='center' valign='middle'><?= ($reg_pernoite['idmembro'] == 0) ? "---" : "" . $pg_usuarios_pernoite . " " . $nome_usuarios_pernoite . ""; ?></td>
                        <td align='center' valign='middle'><?= ($reg_pernoite['idvisitante'] == 0) ? "---" : "" . $nome_visitante . ""; ?></td>
                        <td align='center' valign='middle'><?php echo "" . $reg_pernoite['situacao'] . ""; ?></td>
                        <td align='center' valign='middle'><?php echo "" . $reg_pernoite['data'] . " " . $reg_pernoite['hora'] . ""; ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-md-12">
            <div class="panel"><?php render_cabecalho_painel('LANÇAMENTOS DO ROTEIRO DA GUARDA:', 'fa fa-book', true); ?>
              <div class="panel-content">
                <table id="responsive-table" class="tabela table table-striped table-hover responsive nowrap" width="100%">
                  <thead>
                    <tr>
                      <td align='center' valign='middle' width=5%><font size=3><strong>Excluir</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Ordem</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Quem lançou?</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Militar</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Função</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Armamento</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Quarto</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Data</strong></font></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $rel_guarda = 'rel_rot_guarda';
                    $tabela_funcao = 'rot_guarda_funcao';
                    $tabela_quarto = 'rot_guarda_quarto';
                    $consulta_rel_guarda = $pdo1->prepare("SELECT $rel_guarda.id, $rel_guarda.idmembro, $rel_guarda.data, $rel_guarda.idusuario, $rel_guarda.armamento1, $rel_guarda.armamento2, $rel_guarda.num_armamento1, $rel_guarda.num_armamento2, 
                                            $rel_guarda.idquarto, $tabela_funcao.nomefuncao, $tabela_quarto.nomequarto FROM $rel_guarda LEFT JOIN $tabela_funcao ON ($rel_guarda.idfuncao = $tabela_funcao.idfuncao) 
                                            LEFT JOIN $tabela_quarto ON ($rel_guarda.idquarto = $tabela_quarto.idquarto)
                                            ORDER BY $rel_guarda.id DESC");
                    $consulta_rel_guarda->execute();
                    $consulta_rel_total_guarda = $consulta_rel_guarda->fetchAll(PDO::FETCH_ASSOC);
                    $consulta_total_registro_guarda = count($consulta_rel_total_guarda);
                    for ($i = 0; $i < $consulta_total_registro_guarda and $i < 40; $i++) {
                      $reg_guarda = $consulta_rel_total_guarda[$i];
                      $consulta_usuario = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE usuarios.id = :idusuario");
                      $consulta_usuario->bindParam(":idusuario", $reg_guarda['idusuario'], PDO::PARAM_INT);
                      $consulta_usuario->execute();
                      while ($reg = $consulta_usuario->fetch(PDO::FETCH_ASSOC)) {
                        $nome_usuarios_lancou_guarda = $reg['nomeguerra'];
                        $pg_usuarios_lancou_guarda = getPGrad($reg['idpgrad']);
                      }
                      $consulta_usuario2 = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE id = :idmembro");
                      $consulta_usuario2->bindParam(":idmembro", $reg_guarda['idmembro'], PDO::PARAM_INT);
                      $consulta_usuario2->execute();
                      while ($reg = $consulta_usuario2->fetch(PDO::FETCH_ASSOC)) {
                        $nome_usuarios_guarda = $reg['nomeguerra'];
                        $pg_usuarios_guarda = getPGrad($reg['idpgrad']);
                      } ?>
                      <tr>
                        <td align='center' valign='middle'>
                          <!-- Button trigger modal -->
                          <button type="button" class="btn btn-danger" style="font-size: 10px; padding: 0px 8px;" data-toggle="modal" data-target="#myModal<?php echo ($reg_guarda['id']); ?><?php echo ($rel_guarda); ?>">
                            <i class="fa fa-trash" aria-hidden="true"></i></button>
                          <!-- Modal -->
                          <div class="modal fade" id="myModal<?php echo ($reg_guarda['id']); ?><?php echo ($rel_guarda); ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                              <form id="inline-validation" action="lancamentos6.php?id=<?php echo base64_encode($reg_guarda['id']); ?>&rel=<?php echo base64_encode($rel_guarda); ?>" method="post">
                                <div class="modal-content">
                                  <div class="modal-header modal-danger">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Deseja excluir lançamento?</h4>
                                  </div>
                                  <div class="modal-body">Ordem: <?php echo $reg_guarda['id']; ?>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="submit" name="action" value="Excluiu lançamento da Guarda" class="btn btn-danger" id="action">EXCLUIR LANÇAMENTO</button>
                                  </div>
                                </div>
                              </form>
                            </div>
                          </div>
                        </td>
                        <td align='center' valign='middle'><?php echo $reg_guarda['id']; ?></td>
                        <td align='center' valign='middle'><?php echo "" . $pg_usuarios_lancou_guarda . " " . $nome_usuarios_lancou_guarda . ""; ?></td>
                        <td align='center' valign='middle'><?php echo "" . $pg_usuarios_guarda . " " . $nome_usuarios_guarda . ""; ?></td>
                        <td align='center' valign='middle'><?php echo "" . $reg_guarda['nomefuncao'] . ""; ?></td>
                        <td align='center' valign='middle'><?php
                          if ($reg_guarda['num_armamento1'] == '0' and $reg_guarda['num_armamento2'] == '0') { echo " Sem armamento ";
                          } else if ($reg_guarda['num_armamento2'] == '0') { echo "" . $reg_guarda['armamento1'] . ": " . $reg_guarda['num_armamento1'] . "";
                          } else if ($reg_guarda['num_armamento1'] == '0') { echo "" . $reg_guarda['armamento2'] . ": " . $reg_guarda['num_armamento2'] . "";
                          } else { echo "" . $reg_guarda['armamento1'] . ": " . $reg_guarda['num_armamento1'] . " / " . $reg_guarda['armamento2'] . ": " . $reg_guarda['num_armamento2'] . ""; } ?></td>
                        <td align='center' valign='middle'>
                          <?php if ($reg_guarda['idquarto'] == 0) { echo " --- ";
                          } else { echo "" . $reg_guarda['nomequarto'] . ""; } ?></td>
                        <td align='center' valign='middle'><?php echo "" . $reg_guarda['data'] . ""; ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-md-12">
            <div class="panel"><?php render_cabecalho_painel('LANÇAMENTOS DO ROTEIRO DOS POSTOS:', 'fa fa-book', true); ?>
              <div class="panel-content">
                <table id="responsive-table" class="tabela table table-striped table-hover responsive nowrap" width="100%">
                  <thead>
                    <tr>
                      <td align='center' valign='middle' width=5%><font size=3><strong>Excluir</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Ordem</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Data</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Quarto de Hora</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>P1</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>P2</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>P3</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>P4</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>P5</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>P6</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Aloj</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>FuSEx</strong></font></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $rel_postos = 'rel_rot_postos';
                    $consulta_postos = $pdo1->prepare("SELECT rel_rot_postos.id, rel_rot_postos.idusuario, rel_rot_postos.idquartohora, rel_rot_postos.data, rel_rot_postos.p1, rel_rot_postos.p2, 
                      rel_rot_postos.p3, rel_rot_postos.p4, rel_rot_postos.p5, rel_rot_postos.p6, rel_rot_postos.aloj1, rel_rot_postos.aloj2, rot_postos_quartohora.quartohora 
                      FROM rel_rot_postos LEFT JOIN rot_postos_quartohora ON (rel_rot_postos.idquartohora = rot_postos_quartohora.id) ORDER BY rel_rot_postos.id DESC");
                    $consulta_postos->execute();
                    $consulta_postos_total = $consulta_postos->fetchAll(PDO::FETCH_ASSOC);
                    $consulta_postos_count = count($consulta_postos_total);
                    for ($i = 0; $i < $consulta_postos_count and $i < 40; $i++) {
                      $reg_postos = $consulta_postos_total[$i];
                      $consulta_usuario = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE usuarios.id = :idusuario");
                      $consulta_usuario->bindParam(":idusuario", $reg_postos['idusuario'], PDO::PARAM_INT);
                      $consulta_usuario->execute();
                      while ($reg = $consulta_usuario->fetch(PDO::FETCH_ASSOC)) {
                        $nome_usuario_postos = $reg['nomeguerra'];
                        $pg_usuario_postos = getPGrad($reg['idpgrad']);
                      }
                      if ($reg_postos['p1'] > 0) { $consulta_p1 = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE usuarios.id = :id");
                        $consulta_p1->bindParam(":id", $reg_postos['p1'], PDO::PARAM_INT);
                        $consulta_p1->execute();
                        while ($reg = $consulta_p1->fetch(PDO::FETCH_ASSOC)) { $nome_p1_postos = $reg['nomeguerra']; $pg_p1_postos = getPGrad($reg['idpgrad']); }
                      } else { $nome_p1_postos = 'Militar'; $pg_p1_postos = 'Sem'; }
                      if ($reg_postos['p2'] > 0) {
                        $consulta_p2 = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE usuarios.id = :id");
                        $consulta_p2->bindParam(":id", $reg_postos['p2'], PDO::PARAM_INT);
                        $consulta_p2->execute();
                        while ($reg = $consulta_p2->fetch(PDO::FETCH_ASSOC)) { $nome_p2_postos = $reg['nomeguerra']; $pg_p2_postos = getPGrad($reg['idpgrad']); }
                      } else { $nome_p2_postos = 'Militar'; $pg_p2_postos = 'Sem'; }
                      if ($reg_postos['p3'] > 0) {
                        $consulta_p3 = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE usuarios.id = :id");
                        $consulta_p3->bindParam(":id", $reg_postos['p3'], PDO::PARAM_INT);
                        $consulta_p3->execute();
                        while ($reg = $consulta_p3->fetch(PDO::FETCH_ASSOC)) { $nome_p3_postos = $reg['nomeguerra']; $pg_p3_postos = getPGrad($reg['idpgrad']); }
                      } else { $nome_p3_postos = 'Militar'; $pg_p3_postos = 'Sem'; }
                      if ($reg_postos['p4'] > 0) {
                        $consulta_p4 = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE usuarios.id = :id");
                        $consulta_p4->bindParam(":id", $reg_postos['p4'], PDO::PARAM_INT);
                        $consulta_p4->execute();
                        while ($reg = $consulta_p4->fetch(PDO::FETCH_ASSOC)) { $nome_p4_postos = $reg['nomeguerra']; $pg_p4_postos = getPGrad($reg['idpgrad']); }
                      } else { $nome_p4_postos = 'Militar'; $pg_p4_postos = 'Sem'; }
                      if ($reg_postos['p5'] > 0) {
                        $consulta_p5 = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE usuarios.id = :id");
                        $consulta_p5->bindParam(":id", $reg_postos['p5'], PDO::PARAM_INT);
                        $consulta_p5->execute();
                        while ($reg = $consulta_p5->fetch(PDO::FETCH_ASSOC)) { $nome_p5_postos = $reg['nomeguerra']; $pg_p5_postos = getPGrad($reg['idpgrad']); }
                      } else { $nome_p5_postos = 'Militar'; $pg_p5_postos = 'Sem'; }
                      if ($reg_postos['p6'] > 0) {
                        $consulta_p6 = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE usuarios.id = :id");
                        $consulta_p6->bindParam(":id", $reg_postos['p6'], PDO::PARAM_INT);
                        $consulta_p6->execute();
                        while ($reg = $consulta_p6->fetch(PDO::FETCH_ASSOC)) { $nome_p6_postos = $reg['nomeguerra']; $pg_p6_postos = getPGrad($reg['idpgrad']); }
                      } else { $nome_p6_postos = 'Militar'; $pg_p6_postos = 'Sem'; }
                      if ($reg_postos['aloj1'] > 0) {
                        $consulta_aloj1 = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios 
                                                  WHERE usuarios.id = :id");
                        $consulta_aloj1->bindParam(":id", $reg_postos['aloj1'], PDO::PARAM_INT);
                        $consulta_aloj1->execute();
                        while ($reg = $consulta_aloj1->fetch(PDO::FETCH_ASSOC)) { $nome_aloj1_postos = $reg['nomeguerra']; $pg_aloj1_postos = getPGrad($reg['idpgrad']); }
                      } else { $nome_aloj1_postos = 'Militar'; $pg_aloj1_postos = 'Sem'; }
                      if ($reg_postos['aloj2'] > 0) {
                        $consulta_aloj2 = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE usuarios.id = :id");
                        $consulta_aloj2->bindParam(":id", $reg_postos['aloj2'], PDO::PARAM_INT);
                        $consulta_aloj2->execute();
                        while ($reg = $consulta_aloj2->fetch(PDO::FETCH_ASSOC)) { $nome_aloj2_postos = $reg['nomeguerra']; $pg_aloj2_postos = getPGrad($reg['idpgrad']); }
                      } else { $nome_aloj2_postos = 'Militar'; $pg_aloj2_postos = 'Sem'; } ?>
                      <tr>
                        <td align='center' valign='middle'>
                          <!-- Button trigger modal -->
                          <button type="button" class="btn btn-danger" style="font-size: 10px; padding: 0px 8px;" data-toggle="modal" data-target="#myModal<?php echo ($reg_postos['id']); ?><?php echo ($rel_postos); ?>">
                            <i class="fa fa-trash" aria-hidden="true"></i></button>
                          <!-- Modal -->
                          <div class="modal fade" id="myModal<?php echo ($reg_postos['id']); ?><?php echo ($rel_postos); ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                              <form id="inline-validation" action="lancamentos8.php?id=<?php echo base64_encode($reg_postos['id']); ?>&rel=<?php echo base64_encode($rel_postos); ?>" method="post">
                                <div class="modal-content">
                                  <div class="modal-header modal-danger">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Deseja excluir lançamento?</h4>
                                  </div>
                                  <div class="modal-body">Ordem: <?php echo $reg_postos['id']; ?>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="submit" name="action" value="Excluiu lançamento do Roteiro dos Postos" class="btn btn-danger" id="action">EXCLUIR LANÇAMENTO</button>
                                  </div>
                                </div>
                              </form>
                            </div>
                          </div>
                        </td>
                        <td align='center' valign='middle'><?php echo $reg_postos['id']; ?></td>
                        <td align='center' valign='middle'><?php echo "" . $reg_postos['data'] . ""; ?></td>
                        <td align='center' valign='middle'><?php echo $reg_postos['quartohora'] . " "; ?></td>
                        <td align='center' valign='middle'><?php echo $pg_p1_postos . " " . $nome_p1_postos; ?></td>
                        <td align='center' valign='middle'><?php echo $pg_p2_postos . " " . $nome_p2_postos; ?></td>
                        <td align='center' valign='middle'><?php echo $pg_p3_postos . " " . $nome_p3_postos; ?></td>
                        <td align='center' valign='middle'><?php echo $pg_p4_postos . " " . $nome_p4_postos; ?></td>
                        <td align='center' valign='middle'><?php echo $pg_p5_postos . " " . $nome_p5_postos; ?></td>
                        <td align='center' valign='middle'><?php echo $pg_p6_postos . " " . $nome_p6_postos; ?></td>
                        <td align='center' valign='middle'><?php echo $pg_aloj1_postos . " " . $nome_aloj1_postos; ?></td>
                        <td align='center' valign='middle'><?php echo $pg_aloj2_postos . " " . $nome_aloj2_postos; ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-md-12">
            <div class="panel"><?php render_cabecalho_painel('LANÇAMENTOS DO ROTEIRO DA PERMANÊNCIA E RONDA:', 'fa fa-book', true); ?>
              <div class="panel-content">
                <table id="responsive-table" class="tabela table table-striped table-hover responsive nowrap" width="100%">
                  <thead>
                    <tr>
                      <td align='center' valign='middle' width=5%><font size=3><strong>Excluir</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Ordem</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Quem lançou?</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Militar</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Horário Permanência</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Horário Ronda</strong></font></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $rel_ronda = 'rel_rot_ronda';
                    $consulta_rel_ronda = $pdo1->prepare("SELECT $rel_ronda.id, $rel_ronda.idusuario, $rel_ronda.data_p, $rel_ronda.hora_p, $rel_ronda.data_r, $rel_ronda.hora_r, $tabela_funcao.nomefuncao 
                                            FROM $rel_ronda LEFT JOIN $tabela_funcao ON ($rel_ronda.idfuncao = $tabela_funcao.idfuncao)
                                            ORDER BY $rel_ronda.id DESC");
                    $consulta_rel_ronda->execute();
                    $consulta_rel_total_ronda = $consulta_rel_ronda->fetchAll(PDO::FETCH_ASSOC);
                    $consulta_total_registro_ronda = count($consulta_rel_total_ronda);
                    for ($i = 0; $i < $consulta_total_registro_ronda and $i < 20; $i++) {
                      $reg_ronda = $consulta_rel_total_ronda[$i];
                      $consulta_usuario = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE usuarios.id = :idusuario");
                      $consulta_usuario->bindParam(":idusuario", $reg_ronda['idusuario'], PDO::PARAM_INT);
                      $consulta_usuario->execute();
                      while ($reg = $consulta_usuario->fetch(PDO::FETCH_ASSOC)) {
                        $nome_usuarios_ronda = $reg['nomeguerra'];
                        $pg_usuarios_ronda = getPGrad($reg['idpgrad']);
                      } ?>
                      <tr>
                        <td align='center' valign='middle'>
                          <!-- Button trigger modal -->
                          <button type="button" class="btn btn-danger" style="font-size: 10px; padding: 0px 8px;" data-toggle="modal" data-target="#myModal<?php echo ($reg_ronda['id']); ?><?php echo ($rel_ronda); ?>">
                            <i class="fa fa-trash" aria-hidden="true"></i></button>
                          <!-- Modal -->
                          <div class="modal fade" id="myModal<?php echo ($reg_ronda['id']); ?><?php echo ($rel_ronda); ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                              <form id="inline-validation" action="lancamentos7.php?id=<?php echo base64_encode($reg_ronda['id']); ?>&rel=<?php echo base64_encode($rel_ronda); ?>" method="post">
                                <div class="modal-content">
                                  <div class="modal-header modal-danger">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Deseja excluir lançamento?</h4>
                                  </div>
                                  <div class="modal-body">Ordem: <?php echo $reg_ronda['id']; ?>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="submit" name="action" value="Excluiu lançamento da Permanência e Ronda" class="btn btn-danger" id="action">EXCLUIR LANÇAMENTO</button>
                                  </div>
                                </div>
                              </form>
                            </div>
                          </div>
                        </td>
                        <td align='center' valign='middle'><?php echo $reg_ronda['id']; ?></td>
                        <td align='center' valign='middle'><?php echo "" . $pg_usuarios_ronda . " " . $nome_usuarios_ronda . ""; ?></td>
                        <td align='center' valign='middle'><?php echo $reg_ronda['nomefuncao'] . " "; ?></td>
                        <td align='center' valign='middle'><?php echo $reg_ronda['hora_p'] . " " . $reg_ronda['data_p']; ?></td>
                        <td align='center' valign='middle'><?php echo $reg_ronda['hora_r'] . " " . $reg_ronda['data_r']; ?></td>
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
        [1, "desc"]
      ],
      "pageLength": 5,
      "lengthMenu": [
        [5, 10, 20, -1],
        [5, 10, 20, 'Todos']
      ]
    });
  </script>
</body>

</html>