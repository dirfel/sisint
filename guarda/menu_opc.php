<?php render_opc_header(); ?>
<div id="left-nav" class="nano">
  <div class="nano-content">
    <nav>
      <ul class="nav" id="main-nav">
        <?php 
        render_opc_item(true, 'fa fa-home', 'Página Principal', 'Página inicial do Sistema da Guarda', 'index.php', 'white', false);
        $pode_lancar = ($_SESSION['nivel_guarda'] == "Anotador Gda" || $_SESSION['nivel_guarda'] == "Cabo Gda" || $_SESSION['nivel_guarda'] == "Oficial e Sargento" || $_SESSION['nivel_guarda'] == "Supervisor" || $_SESSION['nivel_guarda'] == "Administrador");
        render_opc_item($pode_lancar, 'fa fa-group', 'Militares', 'Registrar Entrada e Saída de Militares', 'militares1.php', 'white', false); 
        render_opc_item($pode_lancar, 'fa fa-street-view', 'Visitantes e Veículos', 'Entrada, Saída e cadastro de Visitantes e Veículos', 'visitantes1.php', 'white', false); 
        render_opc_item($pode_lancar, 'fas fa-bus-alt', 'Viaturas Militares', 'Registrar Entrada e Saída de Viaturas Militares', 'viaturas1.php', 'white', false); 
        $pode_lancar = ($_SESSION['nivel_guarda'] == "Anotador Gda" || $_SESSION['nivel_guarda'] == "Anotador Aloj" || $_SESSION['nivel_guarda'] == "Supervisor" || $_SESSION['nivel_guarda'] == "Administrador");
        render_opc_item($pode_lancar, 'fas fa-door-open', 'Alojamento de Cb / Sd', 'Entrada e Saída no Alojamento de Cabo e Soldado', 'alojamento1.php', 'white', false); 
        $pode_lancar = ($_SESSION['nivel_guarda'] == "Anotador Aloj" || $_SESSION['nivel_guarda'] == "Cabo Gda" || $_SESSION['nivel_guarda'] == "Oficial e Sargento" || $_SESSION['nivel_guarda'] == "Supervisor" || $_SESSION['nivel_guarda'] == "Administrador");
        render_opc_item($pode_lancar, 'fa fa-bed', 'Pernoite de Mil / Visit', 'Militares e Visitantes que Pernoitaram na OM', 'pernoite1.php', 'white', false); 
        $pode_lancar = ($_SESSION['nivel_guarda'] == "Cabo Gda" || $_SESSION['nivel_guarda'] == "Oficial e Sargento" || $_SESSION['nivel_guarda'] == "Supervisor" || $_SESSION['nivel_guarda'] == "Administrador");
        render_opc_item($pode_lancar, 'fas fa-list', 'Roteiro da Guarda e Postos', 'Roteiro da Guarda ao Quartel', 'rot_gda1.php', 'warning', false); 
        render_opc_item(($_SESSION['nivel_guarda'] != "Anotador Aloj"), 'fas fa-list-alt', 'Ronda e Permanência', 'Roteiro de Ronda e Permanência', 'rot_ronda3.php', 'warning', false); 
        $pode_lancar = ($_SESSION['nivel_guarda'] == "Oficial e Sargento" || $_SESSION['nivel_guarda'] == "Supervisor" || $_SESSION['nivel_guarda'] == "Administrador");
        render_opc_item($pode_lancar, 'fas fa-book', 'Livro de Partes Of Dia', 'Livro de Partes do Oficial de Dia', 'livroPartes.php', 'warning', false); 
        $pode_lancar = ($_SESSION['nivel_guarda'] == "Cabo Gda" || $_SESSION['nivel_guarda'] == "Oficial e Sargento" || $_SESSION['nivel_guarda'] == "Supervisor" || $_SESSION['nivel_guarda'] == "Administrador");
        render_opc_item($pode_lancar, 'fas fa-print', 'Gerar Documentos', 'Gerar Documentos do Serviço', 'documentos.php', 'warning', false);
        $msgLancamentos = base64_encode('AGUARDE O CARREGAMENTO! Nesta página é possível verificar lançamento realizados e excluí-los.');
        render_opc_item($pode_lancar, 'fa fa-check', 'Lançamentos', 'Lançamentos de todos os Documentos do Serviço', 'lancamentos1.php?token3='.$msgLancamentos, 'warning', false);
        $msgCadastros = base64_encode('AGUARDE O CARREGAMENTO! Nesta página é possível verificar os cadastros realizados, edita-los e excluí-los.');
        render_opc_item($pode_lancar, 'fa fa-database', 'Veículos Cadastrados', 'Cadastros de Veículos', 'cadastros_veiculos.php?token3=' . $msgCadastros, 'danger', true);
        render_opc_item($pode_lancar, 'fa fa-database', 'Visitantes Cadastrados', 'Cadastros de Visitantes', 'cadastros_visitantes.php?token3=' . $msgCadastros, 'danger', true);
        ?>
      </ul>
    </nav>
  </div>
</div>