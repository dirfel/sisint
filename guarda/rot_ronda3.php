<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

$pdo = conectar("membros");
$pdo2 = conectar("guarda");

$postos_sentinelas = array(
  "p1" => "Posto 01",
  "p2" => "Posto 02",
  "p3" => "Posto 03",
  "p4" => "Posto 04",
  "aloj1" => "Alojamento",
  "aloj2" => "Bia Msl"
);

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
      <?php render_content_header('Roteiro de Ronda e Permanência', 'fa fa-list-alt'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <form id="validation" action="rot_ronda4.php" method="post">
            <div class="col-sm-12 col-md-12">
              <div class="panel"><?php render_cabecalho_painel('REGISTRAR PERMANÊNCIA:', 'fas fa-list-alt', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-md-6 mb-sm">
                      <div class="form-group">
                        <label for="inputMaxLength" class="control-label">Selecione a Função do Militar:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-star"></i></span>
                          <select name="idfuncao" class="form-control select" style="width: 100%" required>
                            <?php
                            $consulta = $pdo2->prepare("SELECT * FROM rot_guarda_funcao ORDER BY idfuncao ASC");
                            $consulta->execute();
                            echo ("<option></option>");
                            echo ("<optgroup label='Função do Militar'>");
                            while ($reg = $consulta->fetch(PDO::FETCH_ASSOC)) :
                              echo ("<option value=" . base64_encode($reg['idfuncao']) . ">" . $reg['nomefuncao'] . "</option>");
                            endwhile;
                            echo ("</optgroup>");
                            ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 mb-sm">
                        <?php render_militar_ativo_select('tkusr', 'select2-example-basic', true, true) ?>
                    </div>
                    <div class="col-md-4 mb-sm">
                      <div class="form-group">
                        <label for="inputMaxLength" class="control-label">Hora inicial da Permanência:</label>
                        <div class="input-group bootstrap-timepicker timepicker">
                          <span class="input-group-addon color-darker-1 date-time-color"><i class="fa fa-clock-o"></i></span>
                          <input type="text" class="form-control time" name="hora_p" value="00:00" pattern="^\d{1,2}:\d{2}$" required>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4 mb-sm"><?php render_data_field('data_p', true, 'Data da Permanência:', 'now') ?></div>
                    <div class="col-md-4 mb-sm">
                      <div class="form-group">
                        <label for="inputMaxLength" class="control-label">Alteração na Permanência:</label>
                        <div class="input-group">
                          <span class="input-group-addon color-darker-1"><i class="fas fa-list-alt"></i></span>
                          <select name="alteracao" class="form-control select" style="width: 100%" required>
                            <?php
                            echo ("<option></option>");
                            echo ("<optgroup label='Permanência S/A ou C/A'>");
                            echo ("<option value='0'>Sem alteração</option>");
                            echo ("<option value='1'>Com alteração</option>");
                            echo ("</optgroup>");
                            ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="inputMaxLength" class="control-label">Observações da Permanência:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fas fa-pen-alt"></i></span>
													<textarea name="obs" id="autosize" class="form-control" rows="2" placeholder="Observações"></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <hr>
                      <button type="submit" name="action" value='Cadastrado no Roteiro da Ronda e Permanência' class="btn btn-primary">LANÇAR PERMANENCIA</button>
                    </div>
                  </div>
                </div>
            </div>
          </form>
        </div>
<!--------------------------->
          <form id="validation2" action="rot_ronda4.php" method="post">
              <div class="col-sm-12 col-md-12">
                <div class="panel"><?php render_cabecalho_painel('LANÇAR ROTEIRO DE RONDA:', 'fas fa-list', true); ?>
                    <div class="panel-content">
                        <div class="row">
                            <div class="col-md-6 mb-sm">
                            <div class="form-group">
                                <label for="inputMaxLength" class="control-label">Selecione a Função do Militar:</label>
                                <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-star"></i></span>
                                <select name="idfuncao" class="form-control select" style="width: 100%" required>
                                    <?php $consulta = $pdo2->prepare("SELECT * FROM rot_guarda_funcao ORDER BY idfuncao ASC");
                                    $consulta->execute();
                                    echo ("<option></option>");
                                    echo ("<optgroup label='Função do Militar'>");
                                    while ($reg = $consulta->fetch(PDO::FETCH_ASSOC)) :
                                    echo ("<option value=" . base64_encode($reg['idfuncao']) . ">" . $reg['nomefuncao'] . "</option>");
                                    endwhile;
                                    echo ("</optgroup>");
                                    ?>
                                </select>
                                </div>
                            </div>
                            </div>
                            <div class="col-md-6 mb-sm">
                            <div class="form-group">
                                <label for="inputMaxLength" class="control-label">Selecione o Militar:</label>
                                <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-group"></i></span>
                                <select name="tkusr" class="form-control select" style="width: 100%" required>
                                    <?php
                                    $consulta = $pdo->prepare("SELECT * FROM usuarios WHERE (userativo = 'S' AND idpgrad > 5 AND idpgrad <> 9 AND idpgrad < 16) ORDER BY idpgrad, nomecompleto ASC");
                                    $consulta->execute();
                                    echo ("<option></option>");
                                    echo ("<optgroup label='Militar da OM'>");
                                    while ($reg = $consulta->fetch(PDO::FETCH_ASSOC)) :
                                    echo ("<option value=" . base64_encode($reg['id']) . ">" . getPGrad($reg['idpgrad']) . " " . $reg['nomeguerra'] . "</option>");
                                    endwhile;
                                    echo ("</optgroup>");
                                    ?>
                                </select>
                                </div>
                            </div>
                            </div>
                            <div class="col-md-4 mb-sm">
                            <div class="form-group">
                                <label for="inputMaxLength" class="control-label">Hora inicial da Ronda:</label>
                                <div class="input-group bootstrap-timepicker timepicker">
                                <span class="input-group-addon color-darker-2 date-time-color"><i class="fa fa-clock-o"></i></span>
                                <input type="text" class="form-control time" name="hora_r" value="00:00" pattern="^\d{1,2}:\d{2}$" required>
                                </div>
                            </div>
                            </div>
                            <div class="col-md-4 mb-sm"><?php render_data_field('data_r', true, 'Data da Ronda:', 'now') ?></div>
                            <div class="col-md-4 mb-sm">
                            <div class="form-group">
                                <label for="inputMaxLength" class="control-label">Alteração na Ronda:</label>
                                <div class="input-group">
                                <span class="input-group-addon color-darker-2"><i class="fas fa-list-alt"></i></span>
                                <select name="alteracao" class="form-control select" style="width: 100%" required>
                                    <?php
                                    echo ("<option></option>");
                                    echo ("<optgroup label='Ronda S/A ou C/A'>");
                                    echo ("<option value='0'>Sem alteração</option>");
                                    echo ("<option value='1'>Com alteração</option>");
                                    echo ("</optgroup>");
                                    ?>
                                </select>
                                </div>
                            </div>
                            </div>
                            <?php foreach ($postos_sentinelas as $abr => $posto_sentinela) { ?>
                              <div class="col-md-3 mb-sm">
                            <div class="form-group">
                                <label for="inputMaxLength" class="control-label"><?= $posto_sentinela ?>:</label>
                                <div class="input-group">
                                <span class="input-group-addon color-darker-2"><i class="fa fa-fire"></i></span>
                                <select name="<?= $abr ?>" class="form-control select" style="width: 100%" required>
                                    <?php
                                    $consulta = $pdo->prepare("SELECT * FROM usuarios WHERE (userativo = 'S' AND idpgrad > 14) ORDER BY idpgrad DESC, nomeguerra ASC");
                                    $consulta->execute();
                                    echo ("<option>Sem Militar</option>");
                                    echo ("<optgroup label='Militar da OM'>");
                                    while ($reg = $consulta->fetch(PDO::FETCH_ASSOC)) :
                                    /* Para recuperar um ARRAY utilize PDO::FETCH_ASSOC */
                                    echo ("<option value=" . base64_encode($reg['id']) . ">" . getPGrad($reg['idpgrad']) . " " . $reg['nomeguerra'] . "</option>");
                                    endwhile;
                                    echo ("</optgroup>");
                                    ?>
                                </select>
                                </div>
                            </div>
                            </div>
                            <?php } ?>
                            <div class="col-md-12">
                            <div class="form-group">
                                <label for="inputMaxLength" class="control-label">Observações da Permanência e/ou da Ronda:</label>
                                <div class="input-group">
                                <span class="input-group-addon"><i class="fas fa-pen-alt"></i></span>
                                <textarea name="obs" id="autosize" class="form-control" rows="2" placeholder="Observações"></textarea>
                                </div>
                            </div>
                            </div>
                            <div class="col-md-12">
                            <hr>
                            <button type="submit" name="action" value='Cadastrado no Roteiro da Ronda e Permanência' class="btn btn-primary">LANÇAR RONDA</button>
                            </div>
                    </div>
                </div>
              
          </form>
        </div>
      </div></div>
        <?php include '../recursos/views/scroll_to_top.php'; ?>
    </div>
  </div>
  <?php include '../recursos/views/footer.php'; ?>
</body>

</html>
