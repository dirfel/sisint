<?php
//Obter a lista de depositos disponiveis
$pdo = conectar("siscautela");
$consulta = $pdo->prepare("SELECT * FROM depositos");
$consulta->execute();
$deps = $consulta->fetchAll(PDO::FETCH_BOTH);
?>
<?php render_opc_header(); ?>
<div id="left-nav" class="nano">
  <div class="nano-content">
    <nav>
      <ul class="nav" id="main-nav">
        <?php  render_opc_item(true, 'fa fa-home', 'Página Principal', 'Página inicial do Sis Cautela', 'index.php', 'white', false); ?>
        <li class="has-child-item close-item color-warning">
            <a data-toggle="tooltip" data-placement="right" title="Consultar Minhas Cautelas"><i class="fas fa-tools" aria-hidden="true"></i><span>Minhas Cautelas</span></a>
            <ul class="nav child-nav level-1">
                <?php foreach($deps as $dep) {
                  render_opc_item(true, 'fa fa-box', str_replace('Reserva de Material da ', '', $dep['nome_dep']), str_replace('Reserva de Material da ', '', $dep['nome_dep']), 'relatorio_pessoal.php?relatorio_pessoal='.$_SESSION['auth_data']['id'].'&dep='.$dep['id'], 'white', true);
                } ?>
            </ul>
        </li>       
        <?php
        render_opc_item(($_SESSION['auth_data']['nivelacessocautela'] != 0), 'fas fa-monument', 'Gerenciar Reserva', 'Gerenciar Reserva', 'gerenciar_reserva.php', 'white', false);
        render_opc_item(($_SESSION['auth_data']['nivelacessocautela'] != 0), 'fas fa-eye', 'Todas as Cautelas', 'Todas as Cautelas', 'relatorio_material_global.php', 'white', true);
        render_opc_item(($_SESSION['nivel_sis_cautela'] == "Enc Mat"), 'fas fa-list', 'Gerenciar Equipe', 'Gerenciar Equipe', 'gerenciar_equipe.php', 'white', false);
        ?>
      </ul>
    </nav>
  </div>
</div>


