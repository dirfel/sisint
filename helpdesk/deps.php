<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
if ($_SESSION['nivel_helpdesk'] != "Administrador") {
    header('Location: index.php');
    exit();
  }
$p1 = conectar("membros");
$p2 = conectar("siscautela");

$dep_data = $p2->prepare("SELECT * FROM depositos");
$dep_data->execute();
$dep_data = $dep_data->fetchAll(PDO::FETCH_ASSOC);

?>
<!doctype html>
<html lang="pt-BR" class="fixed">

<head>
  <?php include '../recursos/views/cabecalho.php'; ?>
</head>

<body>
  <div class="wrap">
    <div class="page-header">
    <?php render_painel_usu('HELPDESK', $_SESSION['nivel_helpdesk']); ?>
    </div>
    <div class="page-body">
      <div class="left-sidebar">
        <?php include 'menu_opc.php'; ?>
      </div>
      <div class="content">
      <?php render_content_header('Reservas de material cadastradas', 'fa fa-home'); ?>
        <div class="row">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-12">
            <div class="col-sm-12 col-lg-4 animated fadeInRightBig">
             
             <?php
             foreach($dep_data as $dep) {
             ?>
              <div class="panel widgetbox wbox-2 bg-scale-0">
                <div class="panel-content">
                <a href="#" data-toggle="modal" data-target="#editDepModal<?= $dep["id"] ?>" data-placement="top" title="Editar informações do depósito">
                    <div class="row">
                      <div class="col-xs-2">
                        <span class="icon fa fa-box"></span>
                      </div>
                      <div class="col-xs-10">
                        <h4 class="subtitle"><?= $dep["responsavel"] ?></h4>
                        <h1 class="title"><?= $dep["nome_dep"] ?></h1>
                      </div>
                    </div>
                  </a>
                </div>
              </div>
              <!-- modal -->
              <div class="modal fade" id="editDepModal<?= $dep["id"] ?>" tabindex="-1" role="dialog" aria-labelledby="editDepModalLabel<?= $dep["id"] ?>" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editDepModalLabel<?= $dep["id"] ?>">Editar informações do depósito</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="atualizar_deposito.php">
                        <input type="hidden" name="dep_id" value="<?= $dep["id"] ?>">
                        <div class="form-group">
                            <label for="responsavel">Nome do responsável</label>
                            <input type="text" class="form-control" id="responsavel" name="responsavel" value="<?= $dep["responsavel"] ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label for="inputResponsavel">Função do Responsável</label>
                            <input type="text" class="form-control" id="inputFuncResponsavel" name="func_responsavel"  value="<?= $dep["func_responsavel"] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="nome_dep">Nome do depósito</label>
                            <input type="text" class="form-control" id="nome_dep" name="nome_dep" value="<?= $dep["nome_dep"] ?>" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Atualizar</button>
                    </div>
                    </form>
                    </div>
                </div>
              </div>
              <?php } 
              
                $stmt = $p2->prepare('SELECT COUNT(*) AS num_deps FROM depositos');
                $stmt->execute();
                $num_deps = $stmt->fetch(PDO::FETCH_ASSOC)['num_deps'];

                // verificar se o número de depósitos é menor que 9
                if ($num_deps <= 9) {
              ?>
              
              <div class="panel widgetbox wbox-2 bg-scale-0 b-md b-<?php echo $color ?>">
                    <div class="panel-content">
                        <a href="" data-toggle="modal" data-target="#modalCriarDeposito" data-placement="top" title="Criar novo depósito">
                            <div class="row">
                                <div class="col-xs-2">
                                    <span class="icon fa fa-plus color-success"></span>
                                </div>
                                <div class="col-xs-10">
                                    <h1 class="title color-success">Criar novo Depósito</h1>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="modal fade" id="modalCriarDeposito" tabindex="-1" role="dialog" aria-labelledby="modalCriarDepositoLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalCriarDepositoLabel">Criar novo depósito</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="criar_deposito.php" method="post">
                                    <div class="form-group">
                                        <label for="inputResponsavel">Responsável</label>
                                        <input type="text" class="form-control" id="inputResponsavel" name="responsavel" placeholder="Informe o responsável pelo depósito" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputResponsavel">Função do Responsável</label>
                                        <input type="text" class="form-control" id="inputFuncResponsavel" name="func_responsavel" placeholder="Informe o qual a função do responsável pelo depósito" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputNomeDep">Nome do depósito</label>
                                        <input type="text" class="form-control" id="inputNomeDep" name="nome_dep" placeholder="Informe o nome do depósito" required>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Salvar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
      </div>
        <?php include '../recursos/views/scroll_to_top.php'; ?>
    </div>
  </div>
  <?php include '../recursos/views/footer.php'; ?>
  <script src="../recursos/vendor/toastr/toastr.min.js"></script>
  <script src="../recursos/vendor/chart-js/chart.min.js"></script>
  <script src="../recursos/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
  <script src="../recursos/vendor/javascripts/examples/dashboard.js"></script>
</body>

</html>   