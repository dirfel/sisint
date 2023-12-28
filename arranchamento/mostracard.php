<?php
include "../recursos/models/conexao.php";
$cardia = filter_input(INPUT_GET, "out");
$p2 = conectar("arranchamento");
$psqcardapio = $p2->prepare("SELECT * FROM cardapio WHERE data = :data");
$psqcardapio->bindParam(':data', $cardia);
$psqcardapio->execute();
$mcardapio = $psqcardapio->fetchAll(PDO::FETCH_ASSOC);
$m_cardapio = $mcardapio[0];
$cafedia = $m_cardapio['cafe'];
$almocodia = $m_cardapio['almoco'];
$jantardia = $m_cardapio['jantar'];
?>
<!-- Modal -->
<div class="modal-content">
    <div class="modal-header state modal-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modal-large-label">Cardápio do dia <?php echo($cardia);?></h4>
    </div>
    <div class="modal-body">
        <p>Café: <?php echo($cafedia)?></p>
        <p>Almoço: <?php echo($almocodia)?></p>
        <p>Jantar: <?php echo($jantardia)?></p>
    </div>
    <div class="modal-footer">
    </div>
</div>
