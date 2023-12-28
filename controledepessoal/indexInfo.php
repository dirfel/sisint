<?php
$p1 = conectar('membros');

$of_sup = $p1->prepare("SELECT id FROM usuarios WHERE (usuarios.idpgrad = '2' OR usuarios.idpgrad = '3' OR usuarios.idpgrad = '4') AND usuarios.userativo = 'S'");
$of_sup->execute();
$of_sup_total = $of_sup->fetchAll(PDO::FETCH_ASSOC);
$of_sup_count = count($of_sup_total);

$of_int = $p1->prepare("SELECT id FROM usuarios WHERE usuarios.idpgrad = '5' AND usuarios.userativo = 'S'");
$of_int->execute();
$of_int_total = $of_int->fetchAll(PDO::FETCH_ASSOC);
$of_int_count = count($of_int_total);

$of_sub = $p1->prepare("SELECT id FROM usuarios WHERE (usuarios.idpgrad = '6' OR usuarios.idpgrad = '7' OR usuarios.idpgrad = '8') AND usuarios.userativo = 'S'");
$of_sub->execute();
$of_sub_total = $of_sub->fetchAll(PDO::FETCH_ASSOC);
$of_sub_count = count($of_sub_total);

$st = $p1->prepare("SELECT id FROM usuarios WHERE usuarios.idpgrad = '9' AND usuarios.userativo = 'S'");
$st->execute();
$st_total = $st->fetchAll(PDO::FETCH_ASSOC);
$st_count = count($st_total);

$sgt_1e2 = $p1->prepare("SELECT id FROM usuarios WHERE (usuarios.idpgrad = '10' OR usuarios.idpgrad = '11') AND usuarios.userativo = 'S'");
$sgt_1e2->execute();
$sgt_1e2_total = $sgt_1e2->fetchAll(PDO::FETCH_ASSOC);
$sgt_1e2_count = count($sgt_1e2_total);

$sgt_3 = $p1->prepare("SELECT id FROM usuarios WHERE usuarios.idpgrad = '12' AND usuarios.userativo = 'S'");
$sgt_3->execute();
$sgt_3_total = $sgt_3->fetchAll(PDO::FETCH_ASSOC);
$sgt_3_count = count($sgt_3_total);

$cb = $p1->prepare("SELECT id FROM usuarios WHERE usuarios.idpgrad = '14' AND usuarios.userativo = 'S'");
$cb->execute();
$cb_total = $cb->fetchAll(PDO::FETCH_ASSOC);
$cb_count = count($cb_total);

$sd = $p1->prepare("SELECT id FROM usuarios WHERE usuarios.idpgrad = '15' AND usuarios.userativo = 'S'");
$sd->execute();
$sd_total = $sd->fetchAll(PDO::FETCH_ASSOC);
$sd_count = count($sd_total);

$ev = $p1->prepare("SELECT id FROM usuarios WHERE usuarios.idpgrad = '16' AND usuarios.userativo = 'S'");
$ev->execute();
$ev_total = $ev->fetchAll(PDO::FETCH_ASSOC);
$ev_count = count($ev_total);

$total = $p1->prepare("SELECT id FROM usuarios WHERE usuarios.userativo = 'S'");
$total->execute();
$total_total = $total->fetchAll(PDO::FETCH_ASSOC);
$total_count = count($total_total);

$of = $of_sup_count + $of_int_count + $of_sub_count;
$grad = $st_count + $sgt_1e2_count + $sgt_3_count + $cb_count + $sd_count + $ev_count;
?>