<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if (!($_SESSION['nivel_guarda'] == "Anotador Aloj" || $_SESSION['nivel_guarda'] == "Cabo Gda" || $_SESSION['nivel_guarda'] == "Oficial e Sargento" || $_SESSION['nivel_guarda'] == "Supervisor" || $_SESSION['nivel_guarda'] == "Administrador")) {
  $msgerro = base64_encode('Usuário não possui acesso! Somente usuário: Anotador Aloj, Cabo Gda, Oficial e Sargento!');
  header('Location: index.php?token=' . $msgerro);
  exit();
}

$pdo = conectar("membros");
$pdo2 = conectar("guarda");
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
      <?php render_content_header('Militares e Visitantes que Pernoitam na OM', 'fa fa-bed'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <form id="validation" action="pernoite2.php" method="post">
            <div class="col-sm-12 col-md-6">
              <div class="panel"><?php render_cabecalho_painel('LANÇAR MILITAR DA OM:', 'fa fa-bed', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-md-12"><?php render_militar_ativo_select('tkusr', 'select2-example-basic', true, true) ?></div>
                    <div class="col-md-6 mb-sm">
                      <div class="form-group">
                        <label for="inputMaxLength" class="control-label">Selecione o Alojamento na lista:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fas fa-door-open"></i></span>
                          <select name="tkaloj" class="form-control select" style="width: 100%" required>
                            <?php
                            echo ("<option></option>");
                            echo ("<optgroup label='Alojamentos'>");
                            echo ("<option value='Aloj Visitantes'>Aloj Visitantes</option>");
                            echo ("<option value='Aloj Cb/ Sd'>Aloj Cb/ Sd</option>");
                            echo ("<option value='Aloj ST/ Sgt'>Aloj ST/ Sgt</option>");
                            echo ("<option value='Aloj Of'>Aloj Of</option>");
                            echo ("<option value='Aloj Feminino'>Aloj Feminino</option>");
                            echo ("</optgroup>");
                            ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 mb-sm"><?php render_data_field('data', true, 'Data do pernoite:', 'ontem'); ?></div>
                    <div class="col-md-12">
                      <hr>
                      <button type="submit" name="action" value='Pernoitou na OM' class="btn btn-primary" style="width: 140px;">PERNOITOU</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
          <form id="validation" action="pernoite3.php" method="post">
            <div class="col-sm-12 col-md-6">
              <div class="panel"><?php render_cabecalho_painel('LANÇAR VISITANTE OU MILITAR DE OUTRA OM:', 'fa fa-street-view', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-md-12 mb-sm">
                      <div class="form-group">
                        <label for="inputMaxLength" class="control-label">Selecione o Visitante na lista:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-street-view"></i></span>
                          <select name="tkvisit" class="form-control select" style="width: 100%" required>
                            <?php
                            $consulta = $pdo2->prepare("SELECT visitante.id, visitante.nomecompleto, visitante.tipo, visitante.idveiculo, veiculo.placa, veiculo.marca, veiculo.modelo, veiculo.tipo AS veiculo_tipo FROM visitante LEFT JOIN veiculo ON (visitante.idveiculo = veiculo.id) WHERE visitante.situacao = '1'");
                            $consulta->execute();
                            echo ("<option></option>");
                            echo ("<optgroup label='Visitante'>");
                            while ($reg = $consulta->fetch(PDO::FETCH_ASSOC)) :
                              if ($reg['idveiculo'] == 0) {
                                echo ("<option value=" . base64_encode($reg['id']) . ">" . $reg['nomecompleto'] . " (" . $reg['tipo'] . ") (Nenhum Veículo)</option>");
                              } else {
                                echo ("<option value=" . base64_encode($reg['id']) . ">" . $reg['nomecompleto'] . " (" . $reg['tipo'] . ") (" . $reg['placa'] . " - " . $reg['modelo'] . " - " . $reg['marca'] . " - " . $reg['veiculo_tipo'] . ")</option>");
                              }
                            endwhile;
                            echo ("</optgroup>");
                            ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 mb-sm">
                      <div class="form-group">
                        <label for="inputMaxLength" class="control-label">Selecione o Alojamento na lista:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fas fa-door-open"></i></span>
                          <select name="tkaloj" class="form-control select" style="width: 100%" required>
                            <?php
                            echo ("<option></option>");
                            echo ("<optgroup label='Alojamentos'>");
                            echo ("<option value='Aloj Visitantes'>Aloj Visitantes</option>");
                            echo ("<option value='Aloj Cb/ Sd'>Aloj Cb/ Sd</option>");
                            echo ("<option value='Aloj ST/ Sgt'>Aloj ST/ Sgt</option>");
                            echo ("<option value='Aloj Of'>Aloj Of</option>");
                            echo ("<option value='Aloj Feminino'>Aloj Feminino</option>");
                            echo ("</optgroup>");
                            ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 mb-sm"><?php render_data_field('data', true, 'Data do pernoite:', 'ontem'); ?></div>
                    <div class="col-md-12"><h6>Obs: Aqui aparecerão apenas visitantes que estão no interior da OM.</h6></div>
                    <div class="col-md-12">
                      <hr>
                      <button type="submit" name="action" value='Pernoitou na OM' class="btn btn-darker-1" style="width: 140px;">PERNOITOU</button>
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
