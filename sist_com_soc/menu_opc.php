<?php render_opc_header(); ?>
<div id="left-nav" class="nano">
  <div class="nano-content">
    <nav>
      <ul class="nav" id="main-nav">
        <?php 
          render_opc_item(true, 'fa fa-home', 'Página Principal', 'Página inicial do Sistema Helpdesk', 'index.php', 'white', false);
          render_opc_item(true, 'fa fa-birthday-cake', 'Aniversariantes', 'Aniversariantes', 'militares_aniversariantes.php', 'white', true);
          render_opc_item(true, 'fa fa-user', 'Gerenciar Visitantes', 'Gerencie os Visitantes cadastrados no sistema', 'gerenciar_visitantes.php?token3='.base64_encode('Aguarde, essa lista pode demorar para carregar.'), 'white', false);
          render_opc_item(true, 'fa fa-paperclip', 'Pronto HT/Conformidade', 'Gerar Pronto HT/Conformidade', 'pronto_ht_conf.php', 'white', true);
          render_opc_item(true, 'fa fa-calendar', 'Calendário de hóspedes', 'Calendário de hóspedes', 'agenda_mensal_hospedes.php', 'white', true);
        ?>
      </ul>
    </nav>
  </div>
</div>