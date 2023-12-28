<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if (!($_SESSION['nivel_guarda'] == "Cabo Gda" || $_SESSION['nivel_guarda'] == "Oficial e Sargento" || $_SESSION['nivel_guarda'] == "Supervisor" || $_SESSION['nivel_guarda'] == "Administrador")) {
  $msgerro = base64_encode('Usuário não possui acesso! Somente usuário: Cabo Gda, Oficial e Sargento!');
  header('Location: index.php?token=' . $msgerro);
  exit();
}


$pdo = conectar("guarda");
$pdo2 = conectar("membros");

$data = filter_input(INPUT_POST, "data", FILTER_SANITIZE_STRING);
$data2 = date_converter($data);
$quartohora_tipo = filter_input(INPUT_POST, "quartohora_tipo", FILTER_SANITIZE_STRING);
if ($quartohora_tipo == 'P') {
  $quartohora_tipo_completo = 'PAR';
} else {
  $quartohora_tipo_completo = 'ÍMPAR';
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
    <?php render_painel_usu('GUARDA', $_SESSION['nivel_guarda']); ?>
    </div>
    <div class="page-body">
      <div class="left-sidebar">
        <?php include 'menu_opc.php'; ?>
      </div>
      <div class="content">
      <?php render_content_header('Roteiro dos Postos', 'fa fa-fire-alt'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-12">
            <form id="validation" action="rot_gda4.php?data=<?php echo base64_encode($data) ?>" method="post">
              <div class="panel"><?php render_cabecalho_painel('LANÇAR ROTEIRO DOS POSTOS:', 'fas fa-fire-alt', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-sm-12 form-group">
                      <label style="font-size: 15px;" for="inputMaxLength" class="control-label">O Cabo da Guarda deve lançar os Sentinela e os Plantões da hora.</label>
                    </div>
                    <div class="col-md-12 mb-sm">
                      <div class="form-group col-md-4">
                        <label for="inputMaxLength" class="control-label">Selecione o Quarto de hora na lista:</label>
                        <div class="input-group mb-sm">
                          <span class="input-group-addon"><i class="fas fa-hourglass-half"></i></span>
                          <select name="quartohora" class="form-control select" style="width: 100%" required>
                            <?php
                            $consulta = $pdo->prepare("SELECT id, quartohora FROM rot_postos_quartohora WHERE tipo = :tipo ORDER BY id ASC");
                            $consulta->bindParam(":tipo", $quartohora_tipo, PDO::PARAM_STR);
                            $consulta->execute();
                            echo ("<option></option>");
                            echo ("<optgroup label='Quarto de hora'>");
                            while ($reg = $consulta->fetch(PDO::FETCH_ASSOC)) {
                              echo ("<option value=" . base64_encode($reg['id']) . ">" . $reg['quartohora'] . "</option>");
                            }
                            echo ("</optgroup>");
                            ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group col-md-4">
                        <label for="inputMaxLength" class="control-label">Data assumiu o Serviço:</label><br>
                        <label for="inputMaxLength" class="control-label"><?php echo $data ?></label>
                      </div>
                      <div class="form-group col-md-4">
                        <label for="inputMaxLength" class="control-label">Início do Quarto de hora:</label><br>
                        <label for="inputMaxLength" class="control-label"><?php echo $quartohora_tipo_completo ?></label>
                      </div>
                    </div>
                    <div class="form-group col-md-3">
                      <label for="inputMaxLength" class="control-label">Posto 01:</label>
                      <div class="input-group mb-sm">
                        <span class="input-group-addon "><i class="fa fa-fire"></i></span>
                        <select name="p1" id="p1" class="form-control select" style="width: 100%" required>
                          <?php
                          $consulta = $pdo->prepare("SELECT rel_rot_guarda.idmembro, rot_guarda_funcao.nomefuncao FROM rel_rot_guarda LEFT JOIN rot_guarda_funcao ON rel_rot_guarda.idfuncao = rot_guarda_funcao.idfuncao 
                            WHERE rel_rot_guarda.idfuncao > 9 AND rel_rot_guarda.data = :data ORDER BY rel_rot_guarda.id ASC, rel_rot_guarda.idfuncao ASC");
                          $consulta->bindParam(":data", $data, PDO::PARAM_STR);
                          $consulta->execute();
                          echo ("<option>Sem Militar</option>");
                          echo ("<optgroup label='Militar da OM'>");
                          while ($reg = $consulta->fetch(PDO::FETCH_ASSOC)) {
                            $select_usuarios = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE usuarios.id = :idmembro");
                            $select_usuarios->bindParam(":idmembro", $reg['idmembro'], PDO::PARAM_INT);
                            $select_usuarios->execute();
                            while ($reg2 = $select_usuarios->fetch(PDO::FETCH_ASSOC)) {
                              $nome_usuarios = $reg2['nomeguerra'];
                              $pg_usuarios = getPGrad($reg2['idpgrad']);
                            }
                            echo ("<option value=" . base64_encode($reg['idmembro']) . ">" . $pg_usuarios . " - " . $nome_usuarios . " - (" . $reg['nomefuncao'] . ")</option>");
                          }
                          echo ("</optgroup>");
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group col-md-3">
                      <label for="inputMaxLength" class="control-label">Posto 02:</label>
                      <div class="input-group mb-sm">
                        <span class="input-group-addon "><i class="fa fa-fire"></i></span>
                        <select name="p2" id="p2" class="form-control select" style="width: 100%" required>
                          <?php
                          $consulta = $pdo->prepare("SELECT rel_rot_guarda.idmembro, rot_guarda_funcao.nomefuncao FROM rel_rot_guarda LEFT JOIN rot_guarda_funcao ON rel_rot_guarda.idfuncao = rot_guarda_funcao.idfuncao 
                            WHERE rel_rot_guarda.idfuncao > 9 AND rel_rot_guarda.data = :data ORDER BY rel_rot_guarda.id ASC, rel_rot_guarda.idfuncao ASC");
                          $consulta->bindParam(":data", $data, PDO::PARAM_STR);
                          $consulta->execute();
                          echo ("<option>Sem Militar</option>");
                          echo ("<optgroup label='Militar da OM'>");
                          while ($reg = $consulta->fetch(PDO::FETCH_ASSOC)) {
                            $select_usuarios = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE usuarios.id = :idmembro");
                            $select_usuarios->bindParam(":idmembro", $reg['idmembro'], PDO::PARAM_INT);
                            $select_usuarios->execute();
                            while ($reg2 = $select_usuarios->fetch(PDO::FETCH_ASSOC)) {
                              $nome_usuarios = $reg2['nomeguerra'];
                              $pg_usuarios = getPGrad($reg2['idpgrad']);
                            }
                            echo ("<option value=" . base64_encode($reg['idmembro']) . ">" . $pg_usuarios . " - " . $nome_usuarios . " - (" . $reg['nomefuncao'] . ")</option>");
                          }
                          echo ("</optgroup>");
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group col-md-3">
                      <label for="inputMaxLength" class="control-label">Posto 03:</label>
                      <div class="input-group mb-sm">
                        <span class="input-group-addon "><i class="fa fa-fire"></i></span>
                        <select name="p3" id="p3" class="form-control select" style="width: 100%" required>
                          <?php
                          $consulta = $pdo->prepare("SELECT rel_rot_guarda.idmembro, rot_guarda_funcao.nomefuncao FROM rel_rot_guarda LEFT JOIN rot_guarda_funcao ON rel_rot_guarda.idfuncao = rot_guarda_funcao.idfuncao 
                            WHERE rel_rot_guarda.idfuncao > 9 AND rel_rot_guarda.data = :data ORDER BY rel_rot_guarda.id ASC, rel_rot_guarda.idfuncao ASC");
                          $consulta->bindParam(":data", $data, PDO::PARAM_STR);
                          $consulta->execute();
                          echo ("<option>Sem Militar</option>");
                          echo ("<optgroup label='Militar da OM'>");
                          while ($reg = $consulta->fetch(PDO::FETCH_ASSOC)) {
                            $select_usuarios = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE usuarios.id = :idmembro");
                            $select_usuarios->bindParam(":idmembro", $reg['idmembro'], PDO::PARAM_INT);
                            $select_usuarios->execute();
                            while ($reg2 = $select_usuarios->fetch(PDO::FETCH_ASSOC)) {
                              $nome_usuarios = $reg2['nomeguerra'];
                              $pg_usuarios = getPGrad($reg2['idpgrad']);
                            }
                            echo ("<option value=" . base64_encode($reg['idmembro']) . ">" . $pg_usuarios . " - " . $nome_usuarios . " - (" . $reg['nomefuncao'] . ")</option>");
                          }
                          echo ("</optgroup>");
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group col-md-3">
                      <label for="inputMaxLength" class="control-label">Posto 04:</label>
                      <div class="input-group mb-sm">
                        <span class="input-group-addon "><i class="fa fa-fire"></i></span>
                        <select name="p4" id="p4" class="form-control select" style="width: 100%" required>
                          <?php
                          $consulta = $pdo->prepare("SELECT rel_rot_guarda.idmembro, rot_guarda_funcao.nomefuncao FROM rel_rot_guarda LEFT JOIN rot_guarda_funcao ON rel_rot_guarda.idfuncao = rot_guarda_funcao.idfuncao 
                            WHERE rel_rot_guarda.idfuncao > 9 AND rel_rot_guarda.data = :data ORDER BY rel_rot_guarda.id ASC, rel_rot_guarda.idfuncao ASC");
                          $consulta->bindParam(":data", $data, PDO::PARAM_STR);
                          $consulta->execute();
                          echo ("<option>Sem Militar</option>");
                          echo ("<optgroup label='Militar da OM'>");
                          while ($reg = $consulta->fetch(PDO::FETCH_ASSOC)) {
                            $select_usuarios = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE usuarios.id = :idmembro");
                            $select_usuarios->bindParam(":idmembro", $reg['idmembro'], PDO::PARAM_INT);
                            $select_usuarios->execute();
                            while ($reg2 = $select_usuarios->fetch(PDO::FETCH_ASSOC)) {
                              $nome_usuarios = $reg2['nomeguerra'];
                              $pg_usuarios = getPGrad($reg2['idpgrad']);
                            }
                            echo ("<option value=" . base64_encode($reg['idmembro']) . ">" . $pg_usuarios . " - " . $nome_usuarios . " - (" . $reg['nomefuncao'] . ")</option>");
                          }
                          echo ("</optgroup>");
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group col-md-3">
                      <label for="inputMaxLength" class="control-label">Alojamento:</label>
                      <div class="input-group mb-sm">
                        <span class="input-group-addon "><i class="fa fa-fire"></i></span>
                        <select name="aloj1" id="aloj1" class="form-control select" style="width: 100%" required>
                          <?php
                          $consulta = $pdo->prepare("SELECT rel_rot_guarda.idmembro, rot_guarda_funcao.nomefuncao FROM rel_rot_guarda LEFT JOIN rot_guarda_funcao ON rel_rot_guarda.idfuncao = rot_guarda_funcao.idfuncao 
                            WHERE (rel_rot_guarda.idfuncao = 8 OR rel_rot_guarda.idfuncao = 9) AND rel_rot_guarda.data = :data ORDER BY rel_rot_guarda.id ASC, rel_rot_guarda.idfuncao ASC");
                          $consulta->bindParam(":data", $data, PDO::PARAM_STR);
                          $consulta->execute();
                          echo ("<option>Sem Militar</option>");
                          echo ("<optgroup label='Militar da OM'>");
                          while ($reg = $consulta->fetch(PDO::FETCH_ASSOC)) {
                            $select_usuarios = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE usuarios.id = :idmembro");
                            $select_usuarios->bindParam(":idmembro", $reg['idmembro'], PDO::PARAM_INT);
                            $select_usuarios->execute();
                            while ($reg2 = $select_usuarios->fetch(PDO::FETCH_ASSOC)) {
                              $nome_usuarios = $reg2['nomeguerra'];
                              $pg_usuarios = getPGrad($reg2['idpgrad']);
                            }
                            echo ("<option value=" . base64_encode($reg['idmembro']) . ">" . $pg_usuarios . " - " . $nome_usuarios . " - (" . $reg['nomefuncao'] . ")</option>");
                          }
                          echo ("</optgroup>");
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group col-md-3">
                      <label for="inputMaxLength" class="control-label">Bia Msl:</label>
                      <div class="input-group mb-sm">
                        <span class="input-group-addon "><i class="fa fa-fire"></i></span>
                        <select name="aloj2" id="aloj2" class="form-control select" style="width: 100%" required>
                          <?php
                          $consulta = $pdo->prepare("SELECT rel_rot_guarda.idmembro, rot_guarda_funcao.nomefuncao FROM rel_rot_guarda LEFT JOIN rot_guarda_funcao ON rel_rot_guarda.idfuncao = rot_guarda_funcao.idfuncao 
                            WHERE (rel_rot_guarda.idfuncao = 8 OR rel_rot_guarda.idfuncao = 9) AND rel_rot_guarda.data = :data ORDER BY rel_rot_guarda.id ASC, rel_rot_guarda.idfuncao ASC");
                          $consulta->bindParam(":data", $data, PDO::PARAM_STR);
                          $consulta->execute();
                          echo ("<option>Sem Militar</option>");
                          echo ("<optgroup label='Militar da OM'>");
                          while ($reg = $consulta->fetch(PDO::FETCH_ASSOC)) {
                            $select_usuarios = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE usuarios.id = :idmembro");
                            $select_usuarios->bindParam(":idmembro", $reg['idmembro'], PDO::PARAM_INT);
                            $select_usuarios->execute();
                            while ($reg2 = $select_usuarios->fetch(PDO::FETCH_ASSOC)) {
                              $nome_usuarios = $reg2['nomeguerra'];
                              $pg_usuarios = getPGrad($reg2['idpgrad']);
                            }
                            echo ("<option value=" . base64_encode($reg['idmembro']) . ">" . $pg_usuarios . " - " . $nome_usuarios . " - (" . $reg['nomefuncao'] . ")</option>");
                          }
                          echo ("</optgroup>");
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <hr>
                      <a href="rot_gda1.php">
                        <button type="button" value='Voltar' class="btn btn-lighter-1" style="width: 140px;">
                          VOLTAR
                        </button>
                      </a>
                      <button type="submit" name="action" value='Lançou no Roteiro dos Postos' class="btn btn-darker-1" style="width: 140px;">
                        LANÇAR
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
        <?php include '../recursos/views/scroll_to_top.php'; ?>
    </div>
  </div>
  <?php include '../recursos/views/footer.php'; ?>
</body>

</html>
