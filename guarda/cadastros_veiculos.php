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
        <?php render_content_header('Cadastros de Veículos', 'fa fa-car'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-12">
            <div class="panel"><?php render_cabecalho_painel('CADASTROS DE VEÍCULOS DE VISITANTES', 'fa fa-car', true); ?>
              <div class="panel-content">
                <table id="responsive-table" class="tabela table table-striped table-hover responsive nowrap" width="100%">
                  <thead>
                    <tr>
                      <td align='center' valign='middle' width=5%><font size=3><strong></strong></font></td>
                      <td align='center' valign='middle' width=5%><font size=3><strong></strong></font></td>
                      <td class="sorting_desc" align='center' valign='middle'><font size=3><strong>Ordem</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Placa</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Modelo</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Marca</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Cor</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Tipo</strong></font></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $rel_veiculo = 'veiculo';
                    $consulta_veiculo = $pdo1->prepare("SELECT * FROM $rel_veiculo WHERE situacao = '0' ORDER BY modelo, marca ASC");
                    $consulta_veiculo->execute();
                    $consulta_veiculo_total = $consulta_veiculo->fetchAll(PDO::FETCH_ASSOC);
                    $consulta_total_registro_veiculo = count($consulta_veiculo_total);
                    for ($i = 0; $i < $consulta_total_registro_veiculo; $i++) {
                      $reg_veiculo = $consulta_veiculo_total[$i];
                    ?>
                      <tr>
                        <td align='center' valign='middle'>
                          <button type="button" class="btn btn-danger" style="font-size: 10px; padding: 0px 8px;" data-toggle="modal" data-target="#myModal<?php echo ($reg_veiculo['id']); ?><?php echo ($rel_veiculo); ?>">
                            <i class="fa fa-trash" aria-hidden="true"></i></button>
                          <div class="modal fade" id="myModal<?php echo ($reg_veiculo['id']); ?><?php echo ($rel_veiculo); ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                              <form id="inline-validation" action="cadastros3.php?id=<?php echo base64_encode($reg_veiculo['id']); ?>&rel=<?php echo base64_encode($rel_veiculo); ?>" method="post">
                                <div class="modal-content">
                                  <div class="modal-header modal-danger">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title" id="myModalLabel">Deseja excluir cadastro?</h4>
                                  </div>
                                  <div class="modal-body">
                                    Ordem: <?php echo $reg_veiculo['id']; ?>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="submit" name="action" value="Excluiu cadastro de Veículos" class="btn btn-danger" id="action">EXCLUIR LANÇAMENTO</button>
                                  </div>
                                </div>
                              </form>
                            </div>
                          </div>
                        </td>
                        <td align='center' valign='middle'>
                          <button type="button" class="btn btn-warning" style="font-size: 10px; padding: 0px 8px;" data-toggle="modal" data-target="#myModal2<?php echo ($reg_veiculo['id']); ?><?php echo ($rel_veiculo); ?>">
                            <i class="fa fa-edit" aria-hidden="true"></i>
                          </button>
                          <div class="modal fade" id="myModal2<?php echo ($reg_veiculo['id']); ?><?php echo ($rel_veiculo); ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                              <form id="inline-validation" action="cadastros_edit2.php?id=<?php echo base64_encode($reg_veiculo['id']); ?>&rel=<?php echo base64_encode($rel_veiculo); ?>" method="post">
                                <div class="modal-content">
                                  <div class="modal-header modal-warning">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title" id="myModalLabel">Deseja editar o Veículo Ordem: <?php echo $reg_veiculo['id']; ?>?</h4>
                                  </div>
                                  <div style="text-align: right; padding: 24px;">
                                    <?php
                                    $consulta_veiculo2 = $pdo1->prepare("SELECT * FROM veiculo WHERE situacao = '0' AND id = :id");
                                    $consulta_veiculo2->bindParam(":id", $reg_veiculo['id'], PDO::PARAM_INT);
                                    $consulta_veiculo2->execute();
                                    while ($reg = $consulta_veiculo2->fetch(PDO::FETCH_ASSOC)) { ?>
                                      <div class="row">
                                        <div class="col-md-12" style="padding: 6px 0 0 6px">
                                          <div class="form-group">
                                            <label for="inputMaxLength" class="control-label">Tipo do veículo:</label>
                                            <select name="tipo" id="select2-example-basic2" class="form-control" style="width: 380px" required>
                                              <?php
                                              echo ("<option value='" . $reg['tipo'] . "'>" . $reg['tipo'] . "</option>");
                                              echo ("<optgroup label='Tipo'>");
                                              echo ("<option value='Carro'>Carro</option>");
                                              echo ("<option value='Moto'>Moto</option>");
                                              echo ("</optgroup>");
                                              ?>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-12" style="padding: 6px 0 0 6px">
                                          <div class="form-group">
                                            <label for="inputMaxLength" class="control-label">Marca:</label>
                                            <select name="marca" id="select2-example-basic3" class="form-control" style="width: 380px" required>
                                              <?php
                                              echo ("<option value='" . $reg['marca'] . "'>" . $reg['marca'] . "</option>");
                                              echo ("<optgroup label='Marcas'>");
                                              echo ("<option value='ACURA'>ACURA</option>");
                                              echo ("<option value='AGRALE'>AGRALE</option>");
                                              echo ("<option value='ALFA ROMEO'>ALFA ROMEO</option>");
                                              echo ("<option value='AMERICAR'>AMERICAR</option>");
                                              echo ("<option value='ASIA'>ASIA</option>");
                                              echo ("<option value='ASTON MARTIN'>ASTON MARTIN</option>");
                                              echo ("<option value='AUDI'>AUDI</option>");
                                              echo ("<option value='AUSTIN-HEALEY'>AUSTIN-HEALEY</option>");
                                              echo ("<option value='AVALLONE'>AVALLONE</option>");
                                              echo ("<option value='BENTLEY'>BENTLEY</option>");
                                              echo ("<option value='BIANCO'>BIANCO</option>");
                                              echo ("<option value='BMW'>BMW</option>");
                                              echo ("<option value='BRASFIBRA'>BRASFIBRA</option>");
                                              echo ("<option value='BRM'>BRM</option>");
                                              echo ("<option value='BUGRE'>BUGRE</option>");
                                              echo ("<option value='CADILLAC'>CADILLAC</option>");
                                              echo ("<option value='CHERY'>CHERY</option>");
                                              echo ("<option value='CHEVROLET'>CHEVROLET</option>");
                                              echo ("<option value='CHRYSLER'>CHRYSLER</option>");
                                              echo ("<option value='CITROËN'>CITROËN</option>");
                                              echo ("<option value='DAFRA'>DAFRA</option>");
                                              echo ("<option value='DODGE'>DODGE</option>");
                                              echo ("<option value='FERCAR BUGGY'>FERCAR BUGGY</option>");
                                              echo ("<option value='FERRARI'>FERRARI</option>");
                                              echo ("<option value='FIAT'>FIAT</option>");
                                              echo ("<option value='FORD'>FORD</option>");
                                              echo ("<option value='GMC'>GMC</option>");
                                              echo ("<option value='HARLEY-DAVIDSON'>HARLEY-DAVIDSON</option>");
                                              echo ("<option value='HONDA'>HONDA</option>");
                                              echo ("<option value='HUMMER'>HUMMER</option>");
                                              echo ("<option value='HYUNDAI'>HYUNDAI</option>");
                                              echo ("<option value='INFINITI'>INFINITI</option>");
                                              echo ("<option value='IVECO'>IVECO</option>");
                                              echo ("<option value='JAC'>JAC</option>");
                                              echo ("<option value='JAGUAR'>JAGUAR</option>");
                                              echo ("<option value='JEEP'>JEEP</option>");
                                              echo ("<option value='KASINSKI'>KASINSKI</option>");
                                              echo ("<option value='KAWASAKI'>KAWASAKI</option>");
                                              echo ("<option value='KIA'>KIA</option>");
                                              echo ("<option value='LAMBORGHINI'>LAMBORGHINI</option>");
                                              echo ("<option value='LAMBRETTA'>LAMBRETTA</option>");
                                              echo ("<option value='LAND ROVER'>LAND ROVER</option>");
                                              echo ("<option value='LEXUS'>LEXUS</option>");
                                              echo ("<option value='LIFAN'>LIFAN</option>");
                                              echo ("<option value='LOTUS'>LOTUS</option>");
                                              echo ("<option value='MASERATI'>MASERATI</option>");
                                              echo ("<option value='MAZDA'>MAZDA</option>");
                                              echo ("<option value='MCLAREN'>MCLAREN</option>");
                                              echo ("<option value='MERCEDES-BENZ'>MERCEDES-BENZ</option>");
                                              echo ("<option value='MINI'>MINI</option>");
                                              echo ("<option value='MITSUBISHI'>MITSUBISHI</option>");
                                              echo ("<option value='MOBBY'>MOBBY</option>");
                                              echo ("<option value='NISSAN'>NISSAN</option>");
                                              echo ("<option value='PEUGEOT'>PEUGEOT</option>");
                                              echo ("<option value='PONTIAC'>PONTIAC</option>");
                                              echo ("<option value='PORSCHE'>PORSCHE</option>");
                                              echo ("<option value='PUMA'>PUMA</option>");
                                              echo ("<option value='RENAULT'>RENAULT</option>");
                                              echo ("<option value='ROLLS-ROYCE'>ROLLS-ROYCE</option>");
                                              echo ("<option value='SHINERAY'>SHINERAY</option>");
                                              echo ("<option value='SSANGYONG'>SSANGYONG</option>");
                                              echo ("<option value='SUBARU'>SUBARU</option>");
                                              echo ("<option value='SUZUKI'>SUZUKI</option>");
                                              echo ("<option value='TAC'>TAC</option>");
                                              echo ("<option value='TESLA'>TESLA</option>");
                                              echo ("<option value='TOYOTA'>TOYOTA</option>");
                                              echo ("<option value='TRIUMPH'>TRIUMPH</option>");
                                              echo ("<option value='TROLLER'>TROLLER</option>");
                                              echo ("<option value='VOLKSWAGEN'>VOLKSWAGEN</option>");
                                              echo ("<option value='VOLVO'>VOLVO</option>");
                                              echo ("<option value='YAMAHA'>YAMAHA</option>");
                                              echo ("<option value='OUTRA MARCA'>OUTRA MARCA</option>");
                                              echo ("</optgroup>");
                                              ?>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-12" style="padding: 6px 0 0 6px">
                                          <div class="form-group">
                                            <label for="inputMaxLength" class="control-label">Cor:</label>
                                            <select name="cor" id="select2-example-basic4" class="form-control" style="width: 380px" placeholder="Escolha uma opção" required>
                                              <?php
                                              echo ("<option value='" . $reg['cor'] . "'>" . $reg['cor'] . "</option>");
                                              echo ("<optgroup label='Cores'>");
                                              echo ("<option value='AMARELO'>AMARELO</option>");
                                              echo ("<option value='AZUL'>AZUL</option>");
                                              echo ("<option value='BEGE'>BEGE</option>");
                                              echo ("<option value='BRANCO'>BRANCO</option>");
                                              echo ("<option value='BRONZE'>BRONZE</option>");
                                              echo ("<option value='CINZA'>CINZA</option>");
                                              echo ("<option value='DOURADO'>DOURADO</option>");
                                              echo ("<option value='LARANJA'>LARANJA</option>");
                                              echo ("<option value='MARROM'>MARROM</option>");
                                              echo ("<option value='PRATA'>PRATA</option>");
                                              echo ("<option value='PRETO'>PRETO</option>");
                                              echo ("<option value='ROSA'>ROSA</option>");
                                              echo ("<option value='ROXO'>ROXO</option>");
                                              echo ("<option value='VERDE'>VERDE</option>");
                                              echo ("<option value='VERMELHO'>VERMELHO</option>");
                                              echo ("<option value='VINHO'>VINHO</option>");
                                              echo ("<option value='INDEFINIDA'>INDEFINIDA</option>");
                                              echo ("</optgroup>");
                                              ?>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-12" style="padding: 6px 0 0 6px">
                                          <div class="form-group">
                                            <label for="inputMaxLength" class="control-label">Placa:</label>
                                            <input type="text" class="form-control" value="<?php echo ($reg['placa']); ?>" id="inputMaxLength" name="placa" placeholder="Placa - Apenas números e letras MAIÚSCULAS" minlength="7" maxlength="7" style="width: 380px" required>
                                          </div>
                                        </div>
                                        <div class="col-md-12" style="padding: 6px 0 0 6px">
                                          <div class="form-group">
                                            <label for="inputMaxLength" class="control-label">Modelo</label>
                                            <input type="text" class="form-control" value="<?php echo ($reg['modelo']); ?>" id="inputMaxLength2" name="modelo" placeholder="Modelo - Apenas números e letras MAIÚSCULAS" style="width: 380px" required>
                                          </div>
                                        </div>
                                      </div>
                                    <?php
                                    }
                                    ?>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="submit" name="action_edit" value="Editou cadastro de Veículos" class="btn btn-warning" id="action_edit">EDITAR LANÇAMENTO</button>
                                  </div>
                                </div>
                              </form>
                            </div>
                          </div>
                        </td>
                        <td align='center' valign='middle'><?php echo $reg_veiculo['id']; ?></td>
                        <td align='center' valign='middle'><?php echo $reg_veiculo['placa']; ?></td>
                        <td align='center' valign='middle'><?php echo $reg_veiculo['modelo']; ?></td>
                        <td align='center' valign='middle'><?php echo $reg_veiculo['marca']; ?></td>
                        <td align='center' valign='middle'><?php echo $reg_veiculo['cor']; ?></td>
                        <td align='center' valign='middle'><?php echo $reg_veiculo['tipo']; ?></td>
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
        [2, "desc"]
      ]
    });
  </script>
</body>

</html>