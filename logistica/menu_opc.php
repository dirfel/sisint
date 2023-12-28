<?php render_opc_header() ?>
<div id="left-nav" class="nano">
  <div class="nano-content">
    <nav>
      <ul class="nav" id="main-nav">
        <?php render_opc_item(true, 'fa fa-home', 'Página Principal', 'Página inicial do Sistema da Guarda', 'index.php', 'white', false); ?>
        <?php render_opc_item(true, 'fa fa-tint', 'Livro de Abastecimento', 'Gerencie dados da bomba de combustível', '#', 'white', false); ?>
        <?php render_opc_item(true, 'fa fa-truck', 'Pedido de Viatura', 'Clique aqui para criar e gerenciar pedidos de Viatura', 'pedido_viatura.php', 'white', false); ?>
        <?php render_opc_item(true, 'fa fa-car', 'Gerenciar Viaturas', 'Adicione e altere dados das viaturas da OM', 'gestao_viaturas.php', 'white', false); ?>
      </ul>
    </nav>
  </div>
</div>