<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if ($_SESSION['nivel_helpdesk'] == "Sem Acesso") {
    header('Location: ../sistemas/index.php');
    exit();
  }
$pdo = conectar("helpdesk");
?>
<!doctype html>
<html lang="pt-BR" class="fixed left-sidebar-collapsed">
<head><?php include '../recursos/views/cabecalho.php'; ?></head>

<body>
  <div class="wrap">
    <div class="page-header"><?php render_painel_usu('HELPDESK', $_SESSION['nivel_helpdesk']); ?></div>
    <div class="page-body">
      <div class="left-sidebar"><?php include 'menu_opc.php'; ?></div>
      <div class="content">
      <?php render_content_header('Criar um Novo Chamado', 'fa fa-plus-circle'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <form role="form" method="post" action="conf_novo_cham.php" enctype="multipart/form-data">
            <div class="col-sm-12 col-md-12">
              <div class="panel"><?php render_cabecalho_painel('ABRIR CHAMADO:', 'fa fa-plus', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-sm-12 col-md-12">
                      <div class="form-group">
                        <label style="font-size: 15px;" for="inputMaxLength" class="control-label">Preencha o formulário abaixo da forma mais clara possível para que possamos atendê-lo da maneira mais eficiente.</label>
                      </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                      <div class="form-group">
                        <label for="inputMaxLength" class="control-label">Selecione o Tipo de Serviço:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fas fa-wrench"></i></span>
                          <select name="servico" id="select2-example-basic" class="form-control" style="width: 100%" required>
                            <option></option>
                            <?php
                            $consulta = $pdo->prepare("SELECT id, servico FROM servico ORDER BY servico ASC");
                            $consulta->execute();
                            echo ("<optgroup label='Tipo de Serviço'>");
                            while ($reg = $consulta->fetch(PDO::FETCH_ASSOC)) {
                              echo ("<option value=" . $reg['id'] . ">" . $reg['servico'] . "</option>");
                            }
                            echo ("</optgroup>");
                            ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12 col-md-6"><?php render_custom_input('Insira o Assunto:', 'assunto', 'assunto', '', '30', 'Assunto', true, false, 'fa fa-list-check') ?></div>
                    <div class="col-md-12"><br></div>
                    <div class="col-sm-12 col-md-6">
                      <div class="form-group">
                        <label for="inputMaxLength" class="control-label">Selecione a Seção:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fas fa-boxes"></i></span>
                          <select name="secao" id="select2-example-basic2" class="form-control" style="width: 100%" required>
                            <option></option>
                            <?php
                            $consulta2 = $pdo->prepare("SELECT id, secao FROM secao ORDER BY secao ASC");
                            $consulta2->execute();
                            echo ("<optgroup label='Seções'>");
                            while ($reg = $consulta2->fetch(PDO::FETCH_ASSOC)) {
                              echo ("<option value=" . $reg['id'] . ">" . $reg['secao'] . "</option>");
                            }
                            echo ("</optgroup>");
                            ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                      <div class="form-group">
                        <label for="inputMaxLength" class="control-label">Selecione a Etiqueta da Máquina:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-desktop"></i></span>
                          <select name="etiqueta" id="select2-example-basic3" class="form-control" style="width: 100%" required>
                            <option value='0'>Não é o caso</option>
                            <?php
                            $consulta3 = $pdo->prepare("SELECT id, numero FROM etiqueta ORDER BY numero ASC");
                            $consulta3->execute();
                            echo ("<optgroup label='Etiquetas das Máquinas'>");
                            while ($reg = $consulta3->fetch(PDO::FETCH_ASSOC)) {
                              echo ("<option value=" . $reg['id'] . ">" . $reg['numero'] . "</option>");
                            }
                            echo ("</optgroup>"); ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12"><br></div>
                    <div class="col-sm-12 col-md-12">
                      <div class="form-group">
                        <label for="autosize" class="control-label">Insira o Motivo do Chamado:</label>
                        <textarea name="chamado" id="autosize" class="form-control" rows="6" placeholder="Motivo do chamado" maxlength="500" required></textarea>
                      </div>
                    </div>
                    <div class="col-md-12"><br></div>
                    <div class="col-sm-12 col-md-6"><?php render_file_upload_button('arquivo') ?></div>
                    <div class="col-sm-12 col-md-12"><hr>
                      <button type="submit" name="action" value='Abriu Chamado' class="btn btn-primary">ENVIAR</button>
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