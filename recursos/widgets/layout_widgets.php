<?php

function render_content_header($label, $icon) {
    print('<div class="content-header"><div class="leftside-content-header"><ul class="breadcrumbs"><li><h3 class="panel-title">');
    print('<i class="'.$icon.'" aria-hidden="true"></i><b> '.$label.'</b></h3></li></ul></div></div>');
}

function render_painel_usu($sistnome, $userlvl = '') { ?>
    <div class="leftside-header">
    <div id="menu-toggle" class="visible-xs toggle-left-sidebar" data-toggle-class="left-sidebar-open" data-target="html">
        <a data-toggle="tooltip" data-placement="left" title="Expandir / Retrair"><i class="fa fa-bars" aria-label="Toggle sidebar"></i></a>
    </div>
    </div>
    <div class="rightside-header">
    <div class="header-section" id="search-headerbox"><h2><?= $sistnome ?></h2></div> 
    <div class="header-middle"></div>
    <div class="header-section" id="user-headerbox">
        <div class="user-header-wrap">
        <a style="cursor:pointer" data-toggle="tooltip" data-placement="left" title="Dados Pessoais e Confidenciais">
            <div class="user-photo"><img src="../recursos/assets/favicon.png" alt="Usuário" /></div>
            <div class="user-info">
            <span class="user-name"><?= getPGrad($_SESSION['auth_data']['idpgrad']) . " " . $_SESSION['auth_data']['nomeguerra'] ?></span>
            <span class="user-profile"><?= $userlvl ?></span>
            </div>
        </a>
        <i class="fa fa-plus icon-open" aria-hidden="true"></i>
        <i class="fa fa-minus icon-close" aria-hidden="true"></i>
        </div>
        <div class="user-options dropdown-box">
        <div class="drop-content basic">
            <ul><li> <a href="<?= "../controledepessoal/cad_usu_indiv.php?tkusr=" . base64_encode($_SESSION['auth_data']['id']) ?>"><i class="fas fa-user-check" aria-hidden="true"></i> Atualizar Dados</a></li></ul>
            <ul><li> <a href="<?= "../controledepessoal/cad_usu_indiv_senha.php?tkusr=" . base64_encode($_SESSION['auth_data']['id']) ?>"><i class="fas fa-user-lock" aria-hidden="true"></i> Atualizar Senha</a></li></ul>
            <?php if ($_SESSION['auth_data']['contahd'] >= "3") { ?>
            <ul><li><a href="<?php echo ("../controledepessoal/cad_usu.php"); ?>"><i class="fas fa-users-cog" aria-hidden="true"></i> Administrar Usuários</a></li></ul>
            <?php } ?>
        </div>
        </div>
    </div>
    <div class="header-separator"></div>
    <div class="header-section"><a href="../recursos/models/logout.php" data-toggle="tooltip" data-placement="left" title="Sair do sistema"><i class="fa fa-sign-out-alt log-out" aria-hidden="true"></i></a></div>
    <div class="header-separator"></div>
    <div class="header-section"><a href="../sistemas" data-toggle="tooltip" data-placement="left" title="Menu Inicial"><i class="fa fa-gear log-out rotate" aria-hidden="true"></i></a></div>
    <div class="header-separator"></div>
    <?php if ($_SESSION['nivel_helpdesk'] != "Sem Acesso") { ?>
        <div class="header-section"><a href="../helpdesk/novo_cham.php" data-toggle="tooltip" data-placement="left" title="Relatar problema ou sugestão"><i class="fa fa-bug log-out" aria-hidden="true"></i></a></div>
    <?php }  ?>
    </div>
    
<?php }  

function render_opc_header(){ ?>

<div class="left-sidebar-header">
  <div class="left-sidebar-title">
    <p style="font-size: 12px" face="Verdana">MENU DE NAVEGAÇÃO</p>
  </div>
  <a data-toggle="tooltip" data-placement="right" title="Expandir / Retrair">
    <div class="left-sidebar-toggle c-hamburger c-hamburger--htla hidden-xs" data-toggle-class="left-sidebar-collapsed" data-target="html">
      <span>
      </span>
    </div>
  </a>
</div>

<?php } 

/**
 * Esse widget renderiza os list-itens sem subitem do menu opc
 * @param $condicao é boolean, coloque true para que o item sempre apareça
 * @param $open_new_tab é true quando ao clicar, abrir uma nova aba
 */
function render_opc_item($condicao, $icon, $title, $tooltip, $href, $color, $open_new_tab){ 
    if($condicao) { ?>
          <li><a <?=$open_new_tab ? 'target="_blank" ' : '' ?>href="<?=$href?>" class="color-<?=$color?>" data-toggle="tooltip" data-placement="right" title="<?=$tooltip?>"><i class=" <?=$icon?>" aria-hidden="true"></i><span><?=$title?></span></a></li>
    <?php }
} 

function render_opc_list_item($condicao, $icon, $title, $tooltip, $href, $color, $open_new_tab){ 
    if($condicao) { ?>
          <li><a <?=$open_new_tab ? 'target="_blank" ' : '' ?>href="<?=$href?>" class="color-<?=$color?>" data-toggle="tooltip" data-placement="right" title="<?=$tooltip?>"><i class=" <?=$icon?>" aria-hidden="true"></i><span><?=$title?></span></a></li>
    <?php }
} ?>