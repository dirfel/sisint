<?php render_opc_header(); ?>
<div id="left-nav" class="nano">
  <div class="nano-content">
    <nav>
      <ul class="nav" id="main-nav">
        <?php 
        render_opc_item(true, 'fa fa-home', 'Página Principal', 'Página inicial do Sistema Helpdesk', 'index.php', 'white', false);
        render_opc_item(true, 'fas fa-info-circle', 'Meus Chamados', 'Meus Chamados', 'meu_cham.php', 'white', false);
        render_opc_item(true, 'fas fa-plus-circle', 'Novo Chamado', 'Criar um novo Chamado', 'novo_cham.php', 'white', false);
        $pode_lancar = ($_SESSION['nivel_helpdesk'] == "Supervisor" || $_SESSION['nivel_helpdesk'] == "Administrador");
        render_opc_item($pode_lancar, 'fas fa-toolbox', 'Administrar Chamados', 'Administrar Chamados Abertos', 'adm_cham.php', 'warning', false); 
        render_opc_item($pode_lancar, 'fas fa-toolbox', 'Administrar Chamados', 'Administrar Chamados Abertos', 'adm_cham.php', 'warning', false); 

        if ($_SESSION['nivel_helpdesk'] == "Administrador") { ?>
          <li class="has-child-item close-item color-warning">
            <a data-toggle="tooltip" data-placement="right" title="Configurar Informações do Sistema"><i class="fas fa-tools" aria-hidden="true"></i><span>Sistema</span></a>
            <ul class="nav child-nav level-1">
              <li><a href="cad_sistema_op.php"><i class="fab fa-ubuntu" aria-hidden="true"></i><span>Sistemas Operacionais</span></a></li>
              <li><a href="cad_servicos.php"><i class="fas fa-wrench" aria-hidden="true"></i><span>Serviços</span></a></li>
              <li><a href="cad_secoes.php"><i class="fas fa-boxes" aria-hidden="true"></i><span>Seções</span></a></li>
            </ul>
          </li>
        <?php }
        render_opc_item(($_SESSION['nivel_helpdesk'] == "Administrador"), 'fas fa-box', 'Gerenciar Depósitos', 'Gerenciar Depósitos do Sis Cautela', 'deps.php', 'success', false); 
        render_opc_item(($_SESSION['nivel_guarda'] == "Administrador"), 'fas fa-unlock', 'Desbloquear Livro de Partes', 'Desbloquear edição do Livro de partes do Of Dia', 'desbloq.php', 'success', false); ?>
        </ul>
    </nav>
  </div>
</div>