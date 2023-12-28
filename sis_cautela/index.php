<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
?>
<!doctype html>
<html lang="pt-BR" class="fixed">
    <head><?php include '../recursos/views/cabecalho.php'; ?></head>
    <body> 
        <div class="wrap">
            <div class="page-header"><?php render_painel_usu('SIS CAUTELA', $_SESSION['nivel_sis_cautela']); ?></div>
            <div class="page-body">
                <div class="left-sidebar"><?php include 'menu_opc.php'; ?></div>
                <div class="content">
                  <?php render_content_header('Informações do Sistema e do Usuário', 'fa fa-title'); ?>
                    <div class="row animated zoomInDown">
                        <?php include '../recursos/views/token.php'; ?>
        <?php
        //Esse script permite gerar um relatório por militar
        //Passo 1: Obter uma lista de militares -> Filtrar apenas por militares ativos
        $reg = read_usuarios_situacao('S');
        $select_mil = '';
        //passo 4: obter lista de materiais do deposito:        
        $p2 = conectar("siscautela");
        $consulta = $p2->prepare("SELECT * FROM listamat WHERE dep_id = ".$_SESSION['auth_data']['nivelacessocautela'][0]." AND quant > 0");
        $consulta->execute();
        $listamat = $consulta->fetchAll(PDO::FETCH_BOTH);
        //passo 5: obter lista de cautelas ativas:        
        $consulta = $p2->prepare("SELECT * FROM cautela WHERE extravio = 0 ORDER BY militar");
        $consulta->execute();
        $listacautela = $consulta->fetchAll(PDO::FETCH_BOTH);
        //Passo 6: filtrar elementos para usuarios sem nivel de acesso:
        if($_SESSION['auth_data']['nivelacessocautela'] != 0) { ?>
        <form target="_blank" id="validation2" action="relatorio_pessoal.php"  method="post">
            <div class="col-sm-12 col-md-4">
              <div class="panel"><?php render_cabecalho_painel('RELATÓRIO DE CAUTELA POR MILITAR:', 'fas fa-print', true); ?>
              <div class="panel-content">
                <div class="row">
                  <div class="col-md-12 mb-sm"><?php render_militar_ativo_select('relatorio_pessoal', 'relatorio_pessoal', true, false); ?></div>
                    <div class="col-md-12">
                      <hr>
                      <button type="submit" name="dep" value="<?=$_SESSION['auth_data']['nivelacessocautela'][0]?>" class="btn btn-primary">GERAR</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
          <form target="_blank" id="validation1" action="relatorio_material.php" method="post">
              <div class="col-sm-12 col-md-8">
                  <div class="panel"><?php render_cabecalho_painel('RELATÓRIO DE CAUTELA POR MATERIAL:', 'fas fa-print', true); ?>
                      <div class="panel-content">
                          <div class="row">
                              <div class="col-md-12 mb-sm">
                                  <?php $data = array();
                                  foreach($listamat as $mat) { $data[$mat['id']] = $mat['descricao']; }
                                  render_default_select('relatorio_material', '', true, 'Selecione o material desejado:', 'Selecione um material:', 'fas fa-print', $data , '') ?>
                              </div>
                              <div class="col-md-12"><hr>
                                  <button type="submit" name="dep" value="<?=$_SESSION['auth_data']['nivelacessocautela'][0]?>" class="btn btn-primary">GERAR</button>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </form>
          <script>
              var mat = null;
              function selectMat() { if(mat == null) { } else {
                      $.post('./ajax_refresh_limit.php',  {material: mat}, function(response) {
                          if(response <= 0) {
                              alert('Esse item não está disponível!');
                              document.getElementById('quantit').value = 0;
                              document.getElementById('quantit').max = 0;
                              document.getElementById('label_quant').innerText = "Quantidade: (max " + 0 + ")";
                          } else if(response > 0) {
                              document.getElementById('quantit').value = 1;
                              document.getElementById('quantit').max = parseInt(response);
                              document.getElementById('label_quant').innerText = "Quantidade: (max " + parseInt(response) + ")";
                          } else { alert('Algum erro encontrado!'); }
                      });
                  }
              };
              function delay(time) { return new Promise(resolve=>setTimeout(resolve, time)); } 
              async function atlzMax () { await delay(1000); selectMat(); }
          </script>
          <form target="_blank" id="validation3" action="do_cautela.php" method="post">
              <div class="col-sm-12 col-md-12">
                  <div class="panel"><?php render_cabecalho_painel('NOVA CAUTELA', 'fas fa-plus', true); ?>
                  <div class="panel-content">
                      <div class="row">
                          <div class="col-md-12 mb-sm">
                              <div class="col-md-4"><?php render_militar_ativo_select('militar', 'militar', true, false); ?></div>
                              <div class="col-md-8"><?php $data = array();
                                  foreach($listamat as $mat) { $data[$mat['id']] = $mat['descricao']; }
                                  render_default_select('material', '', true, 'Selecione o material desejado:', 'Selecione um material:', 'fas fa-luggage-cart', $data , 'mat = this.value; selectMat();') ?>
                              </div>
                              <div class="col-md-3">
                                  <div class="form-group">
                                      <label id="label_quant" for="quantit" class="control-label">Quantidade:</label>
                                      <div class="input-group">
                                          <span class="input-group-addon"><i class="fas fa-balance-scale"></i></span>
                                          <input type="number" value="1" min="1" max="1" id="quantit" class="form-control" name="quant" required="Preenchimento obrigatório">
                                      </div>
                                  </div>
                              </div>
                              <div class="col-md-3"><?php render_custom_input('Nr de série:', '', 'nr_serie', '', 30, '', false, false, 'fas fa-fingerprint'); ?></div>
                              <div class="col-md-6"><?php render_custom_input('Alteração:', '', 'alteracao', 'S/A', 100, '', false, false, 'fas fa-bug'); ?></div>
                              <div class="col-md-12"><hr>
                                  <button onclick="atlzMax()" type="submit" name="action" value="do_cautela" class="btn btn-primary">REGISTRAR CAUTELA</button>
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
  <?php } ?>
<?php include '../recursos/views/footer.php'; ?>
</body>
</html>