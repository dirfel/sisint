<?php render_opc_header(); ?>
<div id="left-nav" class="nano">
  <div class="nano-content">
    <nav>
      <ul class="nav" id="main-nav">
        <li><a href="index.php" data-toggle="tooltip" data-placement="right" title="Arranchamento e Informações do Sistema e do Usuário">
            <i class="fa fa-home" aria-hidden="true"></i><span>Página Principal</span>
          </a></li>
        <?php if ($_SESSION['auth_data']['contarancho'] >= "2") { ?>
          <li class="has-child-item close-item color-warning">
            <a data-toggle="tooltip" data-placement="right" title="Tipos de Arranchamento"><i class="fas fa-utensils" aria-hidden="true"></i><span>Arranchar:</span></a>
            <ul class="nav child-nav level-1">
              <li><a href="select.php">Por Seleção<i class="far fa-star ml-lg" aria-hidden="true"></i></a></li>
              <li><a href="porpg.php">Por Posto/Graduação</a></li>
              <?php if ($_SESSION['auth_data']['contarancho'] >= "3") { ?>
                <li><a href="individual.php" style="color: #e55039 !important;">Individualmente</a></li>
              <?php } ?>
            </ul>
          </li>
          <li class="color-warning"><a href="reladia.php" data-toggle="tooltip" data-placement="right" title="Relatório de Arranchados"><i class="fas fa-print" aria-hidden="true"></i><span>Gerar Relatório</span></a></li>
        <?php }
        if ($_SESSION['auth_data']['contarancho'] >= "3") { ?>
          <li class="color-warning"><a href="cadcardapio.php" data-toggle="tooltip" data-placement="right" title="Lançar Cardápio"><i class="fas fa-hamburger" aria-hidden="true"></i><span>Cardápio</span></a></li>
          <li><a href="dadosmembros.php" style="color: #e55039 !important;" data-toggle="tooltip" data-placement="right" title="Histórico de Arranchamento"><i class="fas fa-history" aria-hidden="true"></i><span>Histórico de Arranchamento</span></a></li>
        <?php } ?>
      </ul>
    </nav>
  </div>
</div>