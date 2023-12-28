<?php

function render_cabecalho_documento() { ?>

    <!-- <img src="../recursos/assets/brasao_armas.gif" style="width:80px;">
    <h4>MINISTÉRIO DA DEFESA</h4>
    <h4>EXÉRCITO BRASILEIRO</h4>
    <h4><?=strtoupper(NOME_OM)?></h4>
    <h4>(<?=ORIGEM?>)</h4>
    <h4><?=strtoupper(NOME_HISTORICO_OM)?></h4> -->
    <div style="align-items: center; justify-content: center; text-align: center;">
        <img src="../recursos/assets/brasao_armas.gif" style="width:80px;">
        <h4 style="margin-top: 0px;">MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br><?=strtoupper(NOME_OM)?><br>(<?=ORIGEM?>)<br><?=strtoupper(NOME_HISTORICO_OM)?></h4>
    </div>
<?php }

?>