<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if (!$_SESSION['nivel_plano_chamada'] == "Supervisor" && !$_SESSION['nivel_plano_chamada'] == "Administrador") {
  $msgerro = base64_encode('Usuário não possui acesso! Somente usuários: Supervisor e administrador!');
  header('Location: index.php?token=' . $msgerro);
  exit();
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
    <?php render_painel_usu('SERVIÇOS COM SOC', $_SESSION['nivel_com_soc']); ?>
    </div>
    <div class="page-body">
      <div class="left-sidebar">
        <?php include 'menu_opc.php'; ?>
      </div>
      <div class="content">
      <?php render_content_header('Gerenciar Visitantes e Veículos', 'fa fa-street-view'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          
          <div class="col-sm-12 col-md-6">
            <?php render_form_cadastro_visitante('../guarda/cad_visitantes.php'); ?>
          </div>
          <div class="col-sm-12 col-md-6">
            <form id="validation" action="cad_veiculos.php" method="post">
              <div class="panel"><?php render_cabecalho_painel('CADASTRAR VEÍCULO DE VISITANTE OU DE MILITAR DE OUTRA OM', 'fa fa-car', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-md-6 mb-sm"><?php render_tipo_veic_select('tipo'); ?></div>
                    <div class="col-md-6 mb-sm"><?php render_marca_veic_select('marca'); ?></div>
                    <div class="col-md-6 mb-sm"><?php render_cor_veic_select('cor'); ?></div>
                    <div class="col-md-6 mb-sm"><?php render_placa_veic_field('placa'); ?></div>
                    <div class="col-md-6 mb-sm"><?php render_modelo_veic_field('modelo'); ?></div>
                    
                    <div class="col-md-12">
                      <hr>
                      <button type="submit" name="action" value='Saiu APÓS o expediente' class="btn btn-warning" style="width: 140px;">CADASTRAR</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <!-- tabela dos registros -->
          <div class="col-sm-12 col-md-12">
              <div class="panel"><?php render_cabecalho_painel('GERENCIAR VISITANTES E MILITARES DE OUTRA OM', 'fa fa-edit', true); ?>
                <div class="panel-content">
                    <table class="table table-striped table-bordered" data-toggle="table" id="sortTable">
                        <thead>
                          <tr class="text-center">
                              <th>ID</th><th>P/Grad</th><th>Nome</th><th>CPF</th><th>Veículo</th><th>Tipo</th><th>Quant registros</th><th>Ações</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php $visitantes = read_visitante_por_situacao_com_veic(2);
                        foreach ($visitantes as $visitante) {
                          $getVeic = ucwords($visitante['modelo']) ?? '-'; 
                          $getVeic = $getVeic == '-' ? $getVeic : $getVeic . ' (' . strtoupper($visitante['placa']) . ')';

                          $registros = get_registros_visitante($visitante['id']);
                        ?>
                          <tr class="text-center">
                            <td><?=$visitante['id']?></td>
                            <td><?=getPGrad($visitante['idpgrad'])?></td>
                            <td><?=$visitante['nomecompleto']?></td>
                            <td><?=$visitante['cpf'] . (validaCpf($visitante['cpf']) == 1 ? '' : '<a data-toggle="tooltip" data-placement="top" title="CPF inválido"><i class="fa fa-warning text-warning></i></a>"')?></td>
                            <td><?= str_replace('()', '-', $getVeic) ?></td>
                            <td><?=$visitante['tipo']?></td>
                            <td><?= count($registros)?></td>
                            <td><a href="visualizar_visitante.php?id=<?=base64_encode($visitante['id'])?>"><i class="fa fa-edit text-danger"></i></td>
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