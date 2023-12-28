<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

// 1. verificar se o usuario tem permissão para isso
if (!$_SESSION['nivel_plano_chamada'] == "Supervisor" && !$_SESSION['nivel_plano_chamada'] == "Administrador") {
  $msgerro = base64_encode('Usuário não possui acesso! Somente usuários: Supervisor e administrador!');
  header('Location: index.php?token=' . $msgerro);
  exit();
}

// 2. verifico se recebi atributo via get
$id;
if(!isset($_GET['id'])) {
    $msgerro = base64_encode('Erro desconhecido!');
    header('Location: gerenciar_visitantes.php?token=' . $msgerro);
} else {
    $id = base64_decode($_GET['id']);
}

// 3. obtenho informações do visitante e verifico se foi possivel encontrar o visitante no banco de dados
$visitante = get_visitante_by_id($id);
if($visitante == 0) {
    $msgerro = base64_encode('Não foi possível encontrar esse visitante! id = ' . $id);
    header('Location: gerenciar_visitantes.php?token=' . $msgerro);
}

// 4. Obtenho a lista de todas as vezes que esse visitante teve registro de entrada ou saída na guarda
$registros = get_registros_visitante($visitante['id']);
?>
<!doctype html>
<html lang="pt-BR" class="fixed left-sidebar-collapsed">

<head>
  <?php include '../recursos/views/cabecalho.php'; ?>
</head>

<body>
  <div class="wrap">
    <div class="page-header">
    <?php render_painel_usu('SERVIÇOS COM SOC', $_SESSION['nivel_com_soc']); ?>
    </div>
    <div class="page-body">
      <div class="left-sidebar">
        <?php include 'menu_opc.php'; ?>
      </div>
      <div class="content">
        <div class="content-header">
          <div class="leftside-content-header">
            <ul class="breadcrumbs">
              <li>
                <h3 class="panel-title">
                  <i class="fa fa-street-view" aria-hidden="true"></i>
                  <i class="fa fa-car" aria-hidden="true"></i>
                  <b> Editar Visitante</b>
                </h3>
              </li>
            </ul>
          </div>
        </div>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          
          <div class="col-sm-12 col-md-6"><?php render_form_cadastro_visitante('../guarda/edit_visitante.php?id='.base64_encode($visitante['id']), $visitante) ?></div>
          <div class="col-sm-12 col-md-6">
            <?php if($_SESSION['nivel_plano_chamada'] == "Administrador") { ?>
            <form id="validation" action="mesclar_visitante.php" method="post">
                <div class="panel"><?php render_cabecalho_painel('REMOVER DUPLICIDADE', 'fa fa-street-view', true); ?>
                <div class="panel-content"> 
                    <div class="row">
                    <div class="col-md-12 mb-sm"><?php render_visitante_ativo_select('op_b', null, true); ?></div>
                    <div class="col-md-12">
                        Atenção! Ao fazer isso, todos os registros realizados a pessoa selecionada será importado mantendo somente a pessoa desta página.
                        <br>
                        Faça isso apenas se essa pessoa foi lançada com duplicidade no sistema.
                        <hr>
                        <input type="hidden" name="op_a" value="<?=$_GET['id']?>">
                        <button type="submit" name="action" value='mesclar' class="btn btn-warning">IMPORTAR E REMOVER SELECIONADO</button>
                    </div>
                    </div>
                </div>
                </div>
            </form>
            <?php } ?>
          </div> 
          <!-- tabela dos registros -->
          <div class="col-sm-12 col-md-12">
              <div class="panel"><?php render_cabecalho_painel('HISTÓRICO DESSE VISITANTE', 'fa fa-list', true); ?>
                <div class="panel-content">
                    <table class="table table-striped table-bordered text-center" data-toggle="table" id="sortTable">
                        <thead>
                          <tr>
                            <th>Ord</th><th>Data</th><th>Hora</th><th>Evento</th><th>Destino</th><th>Veículo</th><th>Anotador</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $ord = 0;
                            foreach ($registros as $registro) { $ord++; ?>
                            <tr>
                              <th><?=$ord?></th>
                              <th><?=$registro['data']?></th>
                              <th><?=$registro['hora']?></th>
                              <th><?=$registro['situacao']?></th>
                              <th><?= ($registro['situacao'] == 'Saiu do Aquartelamento') ? '-' : ($registro['destino'] ?? 'Não Informado')?></th>
                              <th><?=get_descricao_veic_by_id($registro['idveiculo'])?></th>
                              <th><?=consultaMilitar3($registro['idusuario'])?></th>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
              </a>
          </div>
        </div>
      </div>
        <?php include '../recursos/views/scroll_to_top.php'; ?>
    </div>
  </div>
  <?php include '../recursos/views/footer.php'; ?>
  <script>$('#sortTable').DataTable();</script>
</body>

</html>