<?php 
/*
 * Essa função renderiza um card com um icone e um conteúdo, tudo é clicável e que ao passar o mouse, exibe um tooltip
*/
function render_card1($card_linkUrl, $card_tooltip, $card_icon, $card_stringContent) { ?>
<div class="panel widgetbox wbox-2 bg-scale-0">
    <div class="panel-content">
        <a href="<?=$card_linkUrl?>" data-toggle="tooltip" data-placement="top" title="<?=$card_tooltip?>">
            <div class="row">
                <div class="col-xs-2">
                    <span class="<?=$card_icon?>"></span>
                </div>
                <div class="col-xs-10">
                    <?=$card_stringContent?>
                </div>
            </div>
        </a>
    </div>
</div>
<?php } ?>


<?php 
/**
 * Essa função renderiza um card igual a card1, porém o <a> contém atributos target e id.
 * Caso $card_linkUrl == "" não gera o link
 */
function render_card2($card_linkUrl, $card_tooltip, $card_icon, $card_stringContent, $card_linkTarget, $card_linkId) { ?>

<div class="panel widgetbox wbox-2 bg-scale-0">
    <div class="panel-content">
        <?php echo (empty($card_linkUrl) ? "" : '<a target="'.$card_linkTarget.'" id="'.$card_linkId.'" href="'.$card_linkUrl.'" data-toggle="tooltip" data-placement="top" title="'.$card_tooltip.'">'); ?>
            <div class="row">
                <div class="col-xs-2">
                    <span class="<?=$card_icon?>"></span>
                </div>
                <div class="col-xs-10">
                    <?=$card_stringContent?>
                </div>
            </div>
            <?php echo (empty($card_linkUrl) ? "" : '</a>'); ?>
    </div>
</div>

<?php } ?>

<?php 
// Essa função renderiza um card apenas com o conteúdo
function render_card3($card_stringContent) { ?>
    
<div class="panel widgetbox wbox-2 bg-scale-0">
    <div class="panel-content">
        <?=$card_stringContent?>
    </div>
</div>

<?php } ?>

<?php 
// Essa função renderiza um card apenas com o conteúdo
function render_cabecalho_painel($label, $icon, $togglable) { ?>
  <a style="cursor:pointer" data-toggle="tooltip" data-placement="top" title="<?= $togglable ? 'Expandir / Retrair' : 'Não é possível retrair esse Card'?>">
    <div class="panel-header panel-primary action <?= $togglable ? 'toggle-panel ' : ''?>panel-expand">
      <h3 class="panel-title"><i class="<?=$icon?>" aria-hidden="true"></i><span> <?=$label?></span></h3>
    </div>
  </a>

<?php } ?>