<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if (!($_SESSION['nivel_guarda'] == "Cabo Gda" || $_SESSION['nivel_guarda'] == "Oficial e Sargento" || $_SESSION['nivel_guarda'] == "Supervisor" || $_SESSION['nivel_guarda'] == "Administrador")) {
  $msgerro = base64_encode('Usuário não possui acesso! Somente usuário: Cabo Gda, Oficial e Sargento!');
  header('Location: index.php?token=' . $msgerro);
  exit();
}

$pdo = conectar("membros");

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
      <?php render_content_header('Roteiro da Guarda e dos Postos', 'fa fa-list'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-6">
            <form id="validation" action="rot_gda2.php" method="post">
              <div class="panel"><?php render_cabecalho_painel('CADASTRAR MILITAR NO ROTEIRO DA GUARDA:', 'fas fa-search-plus', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-sm-12 form-group">
                      <label style="font-size: 15px;" for="form-group" class="control-label">O Cabo da Guarda deve cadastrar TODOS os militares da guarnição de serviço, incluindo os Plantões.</label>
                    </div>
                    <div class="col-md-12"><?php render_militar_ativo_select('tkusr', 'select2-example-basic', true, true) ?></div>
                    <div class="col-md-6 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Armamento:</label>
                        <div id="campoPai" class="input-group">
                          <span class="input-group-addon"><i class="fa fa-crosshairs"></i></span>
                          <input type="button" value="Adicionar Arma" onclick="addCampos()">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Função:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-star"></i></span>
                          <select name="idfuncao" class="form-control select" style="width: 100%" required>
                            <?php
                            echo ("<option></option>");
                            echo ("<optgroup label='Funções'>");
                            echo ("<option value='12'>Anotador</option>");
                            echo ("<option value='11'>Reforço da Guarda</option>");
                            echo ("<option value='10'>Sentinela</option>");
                            echo ("<option value='9'>Plantão</option>");
                            echo ("<option value='8'>Cabo de Dia</option>");
                            echo ("<option value='7'>Motorista de Dia</option>");
                            echo ("<option value='6'>Cabo da Guarda</option>");
                            echo ("<option value='5'>Comandante da Guarda</option>");
                            echo ("<option value='4'>Sargento de Dia</option>");
                            echo ("<option value='3'>Adjunto</option>");
                            echo ("<option value='2'>Oficial Representante do Comando</option>");
                            echo ("<option value='1'>Oficial de Dia</option>");
                            echo ("</optgroup>");
                            ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="form-group" class="control-label">Quarto de hora:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="far fa-hourglass"></i></span>
                          <select name="idquarto" class="form-control select" style="width: 100%" required>
                            <?php
                            echo ("<option></option>");
                            echo ("<optgroup label='Quarto de hora'>");
                            echo ("<option value='0'>Nenhum</option>");
                            echo ("<option value='1'>Primeiro Quarto</option>");
                            echo ("<option value='2'>Segundo Quarto</option>");
                            echo ("<option value='3'>Terceiro Quarto</option>");
                            echo ("</optgroup>");
                            ?>
                          </select>
                        </div>
                      </div>
                      <?php render_data_field('data', true, 'Data assumiu o Serviço:', 'now'); ?>
                      
                    </div>
                    <div class="col-md-12">
                      <hr>
                      <button type="submit" name="action" value='Cadastrado no Roteiro da Guarda' class="btn btn-primary" style="width: 140px;">CADASTRAR</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="col-sm-12 col-md-6">
            <form id="validation" action="rot_gda3.php" method="post">
              <div class="panel"><?php render_cabecalho_painel('LANÇAR ROTEIRO DOS POSTOS:', 'fas fa-fire-alt', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-sm-12 form-group">
                      <label style="font-size: 15px;" for="form-group" class="control-label">O militar deve ser cadastrado antes para que apareça no Roteiro dos Postos.</label>
                    </div>
                    <div class="col-md-6 mb-sm"><?php render_data_field('data', true, 'Data assumiu o Serviço:', 'now'); ?></div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Início do Quarto de hora:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fas fa-hourglass-start"></i></span>
                          <select name="quartohora_tipo" class="form-control select" style="width: 100%" required>
                            <?php
                            echo ("<option></option>");
                            echo ("<optgroup label='Início do Quarto de hora'>");
                            echo ("<option value='P'>Hora PAR</option>");
                            echo ("<option value='I'>Hora ÍMPAR</option>");
                            echo ("</optgroup>");
                            ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <hr>
                      <button type="submit" name="action" value='Larçar no Roteiro dos Postos' class="btn btn-darker-1" style="width: 140px;">
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
  <script type="text/javascript">
    var qtdeCampos = 0;

    function addCampos() {
      if (qtdeCampos < 2) {
        var objPai = document.getElementById("campoPai");
        //Criando o elemento DIV;
        var objFilho = document.createElement("div");
        //Definindo atributos ao objFilho:
        objFilho.setAttribute("id", "form-group" + qtdeCampos);
        //Inserindo o elemento no pai:
        objPai.appendChild(objFilho);
        //Escrevendo algo no filho recém-criado:
        document.getElementById("form-group" + qtdeCampos).innerHTML =
          "<select name='arma" + qtdeCampos + "' id='arma" + qtdeCampos +
          "' class='form-control' style='width: 100%' required><optgroup label='Armas'><option value='Pst Beretta 9mm'>Pst Beretta 9mm</option><option value='FAL 7.62mm'>FAL 7,62mm</option><input type='number' class='form-control' name='arma_num" +
          qtdeCampos + "' id='arma_num" + qtdeCampos +
          "' min='1000' max='999999' placeholder='Número da arma' required><input type='button' onClick='removerCampo(" +
          qtdeCampos + ")' value='Remover'>";
        qtdeCampos++;
      } else {
        document.getElementById("campoPai").disabled = true;
      }
    }

    function removerCampo(id) {
      var objPai = document.getElementById("campoPai");
      var objFilho = document.getElementById("form-group" + id);
      //Removendo o DIV com id específico do nó-pai:
      var removido = objPai.removeChild(objFilho);
      qtdeCampos--;
    }
  </script>
</body>

</html>