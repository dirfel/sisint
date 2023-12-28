<?php render_opc_header(); ?>
<div id="left-nav" class="nano">
  <div class="nano-content">
    <nav>
      <ul class="nav" id="main-nav">
        <li><a href="index.php" data-toggle="tooltip" data-placement="right" title="Informações do Sistema"><i class="fa fa-home" aria-hidden="true"></i><span>Página Principal</span></a></li>
        <li><a href="filtrorelat.php" data-toggle="tooltip" data-placement="right" title="Gerar Plano de Chamada"><i class="fa fa-paper-plane" aria-hidden="true"></i><span>Relatório Plano Chamada</span></a></li>
         <li><a href="livro_afastamento.php" data-toggle="tooltip" data-placement="right" title="Livro de afastamento"><i class="fa fa-car" aria-hidden="true"></i><span>Livro de Afastamento</span></a></li>
        <?php if ($_SESSION['auth_data']['contahd'] == "3") { ?>
          <li><a href="niveis_acesso.php" data-toggle="tooltip" data-placement="right" title="Níveis de Acesso aos sistemas"><i class="fa fa-gear" aria-hidden="true"></i><span>Níveis de Acesso</span></a></li>
        <?php }
        if ($_SESSION['nivel_plano_chamada'] == "Administrador" || $_SESSION['nivel_plano_chamada'] == "Supervisor") { ?>
          <li><a href="cad_usu_supervisor.php" data-toggle="tooltip" data-placement="right" title="Cadastrar Novo Usuário no Sistema"><i class=" fa fa-user-plus" aria-hidden="true"></i><span>Cadastrar Usuário</span></a></li>
          <li class="has-child-item close-item color-warning">
            <a data-toggle="tooltip" data-placement="right" title="Configurar Informações do Sistema"><i class="fas fa-tools" aria-hidden="true"></i><span>Sistema</span></a>
            <ul class="nav child-nav level-1">
              <li><a href="cad_bairro.php"><i class="fa fa-map-marker-alt" aria-hidden="true"></i><span>Bairros</span></a></li>
              <li><a href="cad_setor.php"><i class="fa fa-map-marked-alt" aria-hidden="true"></i><span>Setores</span></a></li>
              <?php if ($_SESSION['nivel_plano_chamada'] == "Administrador") { ?>
                <li><a href="cad_subunid.php"><i class="fa fa-sitemap" aria-hidden="true"></i><span>Subunidade</span></a></li>
              <?php } ?>
            </ul>
          </li>
        <?php } ?>
      </ul>
    </nav>
  </div>
</div>