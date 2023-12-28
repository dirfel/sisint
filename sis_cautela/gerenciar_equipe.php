<?php
//definiremos aqui algumas configurações no php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

//será conectado ao database membros
$p1 = conectar("membros");

?>
<!doctype html>
<html lang="pt-BR" class="fixed">
    
    <head>
        <?php include '../recursos/views/cabecalho.php'; ?>
        <style>
            table, th, td {
                border: 1px solid black;
                border-collapse: collapse;
                text-align: center;
      
            }
            table {
                width: 100%;
                page-break-inside:auto;
            }
        </style>
    </head>
    
    <body>
        <div class="wrap">
            <div class="page-header">
            <?php render_painel_usu('SIS CAUTELA', $_SESSION['nivel_sis_cautela']); ?>
            </div>
            <div class="page-body">
                <div class="left-sidebar">
                    <?php include 'menu_opc.php'; ?>
                </div>
                <div class="content">
                <?php render_content_header('Gerenciar Equipe', 'fa fa-home'); ?>
                    <div class="row animated zoomInDown">
                        <?php include '../recursos/views/token.php'; ?>
        <!-- INICIO RELATÓRIO Fitrar Militar -->
        <?php
        //Esse script permite adicionar um militar à lista de auxiliares da reserva de material

        //Passo 1: Obter uma lista de militares -> Filtrar apenas por militares ativos que não tenham função em reservas
        $pdo = conectar("membros");

        $consulta = $pdo->prepare("SELECT id, nomecompleto, nomeguerra, idpgrad FROM usuarios WHERE userativo = 'S' AND nivelacessocautela = '0'");
        $consulta->execute();
        $reg = $consulta->fetchAll(PDO::FETCH_BOTH);
        
        
        //Passo 2: Obter a lista de auxiliares -> Filtrar apenas por militares ativos que tenham função nessa reserva
        $consulta = $pdo->prepare("SELECT id, nomecompleto, nomeguerra, idpgrad FROM usuarios WHERE userativo = 'S' AND nivelacessocautela = '{$_SESSION['auth_data']['nivelacessocautela'][0]}A'");
        $consulta->execute();
        $reg1 = $consulta->fetchAll(PDO::FETCH_BOTH);
        
        //Passo 2:
        //Obter a lista de depositos disponiveis
        
        $pdo = conectar("siscautela");
        $consulta = $pdo->prepare("SELECT * FROM depositos");
        $consulta->execute();
        $deps = $consulta->fetchAll(PDO::FETCH_BOTH);

        //passo 4: obter lista de materiais do deposito:
        $consulta = $pdo->prepare("SELECT * FROM listamat WHERE dep_id = 1 AND quant > 0");
        $consulta->execute();
        $listamat = $consulta->fetchAll(PDO::FETCH_BOTH);
        
        //Passo 6: filtrar elementos para usuarios sem nivel de acesso:

        if($_SESSION['auth_data']['nivelacessocautela'][0] != 0) {
        ?>
        <!-- painel add aux -->
        <form target="" id="validation2" action="add_aux.php"  method="post">
            <div class="col-sm-12 col-md-4">
              <div class="panel"><?php render_cabecalho_painel('CADASTRAR AUXILIAR NA RESERVA:', 'fas fa-plus', true); ?>
              <div class="panel-content">
                <div class="row">
                  <div class="col-md-12 mb-sm">
                    <div class="form-group">
                      <label for="form-group" class="control-label">Selecione o militar desejado:</label>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fas fa-plus"></i></span>
                        <select name="relatorio_pessoal" id="select2-example-basic3" class="form-control select2-hidden-accessible" style="width: 100%" required="" tabindex="-1" aria-hidden="true">
                          <optgroup label="Selecione um militar">
                            <option></option>
                                <?php foreach($reg as $chave) { ?>
                                  <option value="<?= $chave["id"]?>"><?= getPGrad($chave["idpgrad"])?> <?=$chave["nomecompleto"]?> (<?=$chave["nomeguerra"]?>)</option>
                                <?php } ?>
                          </optgroup>                          
                        </select>
                      </div>
                    </div>
                    <p>Obs: Aqui é exibido apenas militares que não possuam relação com alguma reserva.<p>
                  </div>
                    <div class="col-md-12">
                      <hr>
                      <button type="submit" name="dep" value="<?=$_SESSION['auth_data']['nivelacessocautela'][0]?>" class="btn btn-primary">Cadastrar Auxiliar</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
          <!-- FIM PAINEL ADD -->
          <!-- PAINEL LISTAR -->
          <div class="col-sm-12 col-md-4">
              <div class="panel"><?php render_cabecalho_painel('AUXILIARES CADASTRADOS:', 'fas fa-list', true); ?>
              <div class="panel-content">
                <div class="row">
                  <div class="col-md-12 mb-sm">
                    <div class="table">
                    <table>
                      <tr><th>Militar</th><th>Ações</th></tr>
                      <?php foreach($reg1 as $aux) { ?><tr>
        <td><?= getPGrad($aux["idpgrad"]) ?> <?= $aux['nomeguerra']?></td>
        <td>
            <form action="./rem_aux.php" method="post" >
                <button type="submit" name="militar" value="<?= $aux['id']?>" class="btn btn-primary">Excluir</button>
            </form>
        </td>
    </tr>
    <?php } ?>
    </div>
     </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <?php include '../recursos/views/scroll_to_top.php'; ?>
    </div>
</div>
<?php } ?>
  <?php include '../recursos/views/footer.php'; ?>
</body>
</html>