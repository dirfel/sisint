<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if (!($_SESSION['nivel_guarda'] == "Cabo Gda" || $_SESSION['nivel_guarda'] == "Oficial e Sargento" || $_SESSION['nivel_guarda'] == "Supervisor" || $_SESSION['nivel_guarda'] == "Administrador")) {
  $msgerro = base64_encode('Usuário não possui acesso! Somente usuário: Cabo Gda, Oficial e Sargento!');
  header('Location: index.php?token=' . $msgerro);
  exit();
}

$tipos_visitante = array('Militar da Ativa', 'Militar Inativo', 'Pensionista Militar', 'Dependente de Militar', 'Civil');

$pdo1 = conectar("guarda");
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
      <?php render_content_header('Cadastros de Visitantes', 'fa fa-street-view'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <?php foreach($tipos_visitante as $tipo_visitante) { 
            $consulta_visitante = $pdo1->prepare("SELECT * FROM visitante WHERE userativo = 'S' AND (situacao = '0' AND tipo = '".$tipo_visitante."') ORDER BY nomecompleto ASC LIMIT 1000");
            $consulta_visitante->execute();
            $registros = $consulta_visitante->fetchAll(PDO::FETCH_ASSOC);
          ?>
          <div class="col-sm-12 col-md-6">
            <div class="panel"><?php render_cabecalho_painel('CADASTROS DE VISITANTES '.$tipo_visitante, 'fa fa-user-plus', true); ?>
              <div class="panel-content">
                <table id="responsive-table" class="tabela table table-striped table-hover responsive nowrap" width="100%">
                  <thead>
                    <tr>
                      <td align='center' valign='middle' width=5%></td>
                      <td align='center' valign='middle' width=5%></td>
                      <td align='center' valign='middle'><font size=3><strong>Ordem</strong></font></td>
                      <td align='center' valign='middle'><font size=3><strong>Nome</strong></font></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($registros as $reg) { ?>
                      <tr>
                        <td align='center' valign='middle'>
                          <button type="button" class="btn btn-danger" style="font-size: 10px; padding: 0px 8px;" data-toggle="modal" data-target="#myModal<?= $reg['id'] ?>visitante">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                          </button>
                          <div class="modal fade" id="myModal<?= $reg['id'] ?>visitante" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                              <form id="inline-validation" action="cadastros2.php?id=<?= base64_encode($reg['id']) ?>&rel=<?= base64_encode('visitante') ?>" method="post">
                                <div class="modal-content">
                                  <div class="modal-header modal-danger">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Deseja excluir cadastro?</h4>
                                  </div>
                                  <div class="modal-body">Ordem: <?= $reg['id'] ?></div>
                                  <div class="modal-footer">
                                    <button type="submit" name="action" value="Excluiu cadastro de Visitantes" class="btn btn-danger" id="action">EXCLUIR CADASTRO</button>
                                  </div>
                                </div>
                              </form>
                            </div>
                          </div>
                        </td>
                        <td align='center' valign='middle'>
                          <button type="button" class="btn btn-warning" style="font-size: 10px; padding: 0px 8px;" data-toggle="modal" data-target="#myModal2<?= $reg['id'] ?>visitante">
                            <i class="fa fa-edit" aria-hidden="true"></i>
                          </button>
                          <div class="modal" id="myModal2<?= $reg['id'] ?>visitante" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                              <form id="inline-validation" action="cadastros_edit1.php?id=<?= base64_encode($reg['id']) ?>&rel=<?= base64_encode('visitante') ?>" method="post">
                                <div class="modal-content">
                                  <div class="modal-header modal-warning">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Deseja editar o Visitantes Ordem: <?= $reg['id'] ?>?</h4>
                                  </div>
                                  <div style="text-align: right; padding: 24px;">
                                      <div class="row">
                                        <div class="col-md-12" style="padding: 6px 0 0 6px">
                                          <div class="form-group">
                                            <label for="inputMaxLength" class="control-label">Nome completo:</label>
                                            <input type="text" class="form-control" value="<?= ($reg['nomecompleto']) ?>" id="inputMaxLength" name="nomecompleto" placeholder="Nome Completo - Apenas letras MAIÚSCULAS" maxlength="100" style="width: 380px" required>
                                          </div>
                                        </div>
                                        <div class="col-md-12" style="padding: 6px 0 0 6px">
                                          <div class="form-group">
                                            <label for="inputMaxLength" class="control-label">Categoria do Visitante:</label>
                                            <select name="tipo" id="select2-example-basic" class="form-control" style="width: 380px" required>
                                                <optgroup label='Categoria'>
                                                    <option value='Militar da Ativa' <?=($reg['tipo'] == 'Militar da Ativa' ? 'selected' : '')?>>Militar da Ativa</option>
                                                    <option value='Militar Inativo' <?=($reg['tipo'] == 'Militar Inativo' ? 'selected' : '')?>>Militar Inativo</option>
                                                    <option value='Pensionista Militar' <?=($reg['tipo'] == 'Pensionista Militar' ? 'selected' : '')?>>Pensionista Militar</option>
                                                    <option value='Dependente de Militar' <?=($reg['tipo'] == 'Dependente de Militar' ? 'selected' : '')?>>Dependente de Militar</option>
                                                    <option value='Civil' <?=($reg['tipo'] == 'Civil' ? 'selected' : '')?>>Civil</option>
                                                </optgroup>
                                            </select>
                                          </div>
                                        </div>
                                      </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="submit" name="action_edit" value="Editou cadastro de Visitantes" class="btn btn-warning" id="action_edit">EDITAR CADASTRO</button>
                                  </div>
                                </div>
                              </form>
                            </div>
                          </div>
                        </td>
                        <td align='center' valign='middle'><?= $reg['id'] ?></td>
                        <td align='center' valign='middle'><?= $reg['nomecompleto'] ?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php } ?>
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