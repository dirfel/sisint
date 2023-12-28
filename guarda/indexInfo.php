<?php
// Backend de index.php
$sistema = base64_encode('GUARDA');
$idmembro = $_SESSION['auth_data']['id'];
$data = date("d/m/Y");

$veiculo = $p2->prepare("SELECT id FROM veiculo");
$veiculo->execute();
$osveiculos = $veiculo->fetchAll(PDO::FETCH_ASSOC);
$totalveiculo = count($osveiculos);

$veiculo2 = $p2->prepare("SELECT id FROM veiculo WHERE situacao = '1'");
$veiculo2->execute();
$osveiculo2 = $veiculo2->fetchAll(PDO::FETCH_ASSOC);
$situacaoveiculo = count($osveiculo2);
$colorVeiculo = ($situacaoveiculo > 0) ? "danger" : "success";

$viatura = $p2->prepare("SELECT id FROM viatura");
$viatura->execute();
$osviaturas = $viatura->fetchAll(PDO::FETCH_ASSOC);
$totalviatura = count($osviaturas);

$viatura2 = $p2->prepare("SELECT id FROM viatura WHERE situacao = '1'");
$viatura2->execute();
$osviatura2 = $viatura2->fetchAll(PDO::FETCH_ASSOC);
$situacaoviatura = count($osviatura2);
$colorViatura = ($situacaoviatura > 0) ? "danger" : "success";

$visitante = $p2->prepare("SELECT id FROM visitante WHERE userativo = 'S'");
$visitante->execute();
$osvisitantes = $visitante->fetchAll(PDO::FETCH_ASSOC);
$totalvisitante = count($osvisitantes);

$visitante2 = $p2->prepare("SELECT id FROM visitante WHERE situacao = '1'");
$visitante2->execute();
$osvisitantes2 = $visitante2->fetchAll(PDO::FETCH_ASSOC);
$situacaovisitante = count($osvisitantes2);
$colorVisitante = ($situacaovisitante > 0) ? "danger" : "success";

$situacao_entrada_durante = 'Entrou DURANTE o expediente';
$mil_durante_entrada = $p2->prepare("SELECT id FROM rel_militares WHERE situacao = :situacao AND data = :data");
$mil_durante_entrada->bindParam(':situacao', $situacao_entrada_durante, PDO::PARAM_STR);
$mil_durante_entrada->bindParam(':data', $data, PDO::PARAM_STR);
$mil_durante_entrada->execute();
$mil_durante_entrada_total = $mil_durante_entrada->fetchAll(PDO::FETCH_ASSOC);
$mil_durante_entrada_count = count($mil_durante_entrada_total);

$situacao_saida_durante = 'Saiu DURANTE o expediente';
$mil_durante_saida = $p2->prepare("SELECT id FROM rel_militares WHERE situacao = :situacao AND data = :data");
$mil_durante_saida->bindParam(':situacao', $situacao_saida_durante, PDO::PARAM_STR);
$mil_durante_saida->bindParam(':data', $data, PDO::PARAM_STR);
$mil_durante_saida->execute();
$mil_durante_saida_total = $mil_durante_saida->fetchAll(PDO::FETCH_ASSOC);
$mil_durante_saida_count = count($mil_durante_saida_total);

$situacao_entrada_apos = 'Entrou APÓS o expediente';
$mil_apos_entrada = $p2->prepare("SELECT id FROM rel_militares WHERE situacao = :situacao AND data = :data");
$mil_apos_entrada->bindParam(':situacao', $situacao_entrada_apos, PDO::PARAM_STR);
$mil_apos_entrada->bindParam(':data', $data, PDO::PARAM_STR);
$mil_apos_entrada->execute();
$mil_apos_entrada_total = $mil_apos_entrada->fetchAll(PDO::FETCH_ASSOC);
$mil_apos_entrada_count = count($mil_apos_entrada_total);

$situacao_saida_apos = 'Saiu APÓS o expediente';
$mil_apos_saida = $p2->prepare("SELECT id FROM rel_militares WHERE situacao = :situacao AND data = :data");
$mil_apos_saida->bindParam(':situacao', $situacao_saida_apos, PDO::PARAM_STR);
$mil_apos_saida->bindParam(':data', $data, PDO::PARAM_STR);
$mil_apos_saida->execute();
$mil_apos_saida_total = $mil_apos_saida->fetchAll(PDO::FETCH_ASSOC);
$mil_apos_saida_count = count($mil_apos_saida_total);

$situacao_entrada_aloj = 'Entrou no Aloj Cb/Sd';
$mil_aloj_entrada = $p2->prepare("SELECT id FROM rel_alojamento WHERE situacao = :situacao AND data = :data");
$mil_aloj_entrada->bindParam(':situacao', $situacao_entrada_aloj, PDO::PARAM_STR);
$mil_aloj_entrada->bindParam(':data', $data, PDO::PARAM_STR);
$mil_aloj_entrada->execute();
$mil_aloj_entrada_total = $mil_aloj_entrada->fetchAll(PDO::FETCH_ASSOC);
$mil_aloj_entrada_count = count($mil_aloj_entrada_total);

$situacao_saida_aloj = 'Saiu do Aloj Cb/Sd';
$mil_aloj_saida = $p2->prepare("SELECT id FROM rel_alojamento WHERE situacao = :situacao AND data = :data");
$mil_aloj_saida->bindParam(':situacao', $situacao_saida_aloj, PDO::PARAM_STR);
$mil_aloj_saida->bindParam(':data', $data, PDO::PARAM_STR);
$mil_aloj_saida->execute();
$mil_aloj_saida_total = $mil_aloj_saida->fetchAll(PDO::FETCH_ASSOC);
$mil_aloj_saida_count = count($mil_aloj_saida_total);

$situacao_entrada = 'Entrou no Aquartelamento';
$visit_entrada = $p2->prepare("SELECT id FROM rel_visitantes WHERE situacao = :situacao AND data = :data");
$visit_entrada->bindParam(':situacao', $situacao_entrada, PDO::PARAM_STR);
$visit_entrada->bindParam(':data', $data, PDO::PARAM_STR);
$visit_entrada->execute();
$visit_entrada_total = $visit_entrada->fetchAll(PDO::FETCH_ASSOC);
$visit_entrada_count = count($visit_entrada_total);

$situacao_saida = 'Saiu do Aquartelamento';
$visit_saida = $p2->prepare("SELECT id FROM rel_visitantes WHERE situacao = :situacao AND data = :data");
$visit_saida->bindParam(':situacao', $situacao_saida, PDO::PARAM_STR);
$visit_saida->bindParam(':data', $data, PDO::PARAM_STR);
$visit_saida->execute();
$visit_saida_total = $visit_saida->fetchAll(PDO::FETCH_ASSOC);
$visit_saida_count = count($visit_saida_total);

$visit_entrada_veic = $p2->prepare("SELECT id FROM rel_visitantes WHERE situacao = :situacao AND data = :data AND idveiculo != 0");
$visit_entrada_veic->bindParam(':situacao', $situacao_entrada, PDO::PARAM_STR);
$visit_entrada_veic->bindParam(':data', $data, PDO::PARAM_STR);
$visit_entrada_veic->execute();
$visit_entrada_veic_total = $visit_entrada_veic->fetchAll(PDO::FETCH_ASSOC);
$visit_entrada_veic_count = count($visit_entrada_veic_total);

$visit_saida_veic = $p2->prepare("SELECT id FROM rel_visitantes WHERE situacao = :situacao AND data = :data AND idveiculo != 0");
$visit_saida_veic->bindParam(':situacao', $situacao_saida, PDO::PARAM_STR);
$visit_saida_veic->bindParam(':data', $data, PDO::PARAM_STR);
$visit_saida_veic->execute();
$visit_saida_veic_total = $visit_saida_veic->fetchAll(PDO::FETCH_ASSOC);
$visit_saida_veic_count = count($visit_saida_veic_total);

$vtr_entrada = $p2->prepare("SELECT id FROM rel_viaturas WHERE situacao = :situacao AND data = :data");
$vtr_entrada->bindParam(':situacao', $situacao_entrada, PDO::PARAM_STR);
$vtr_entrada->bindParam(':data', $data, PDO::PARAM_STR);
$vtr_entrada->execute();
$vtr_entrada_total = $vtr_entrada->fetchAll(PDO::FETCH_ASSOC);
$vtr_entrada_count = count($vtr_entrada_total);

$vtr_saida = $p2->prepare("SELECT id FROM rel_viaturas WHERE situacao = :situacao AND data = :data");
$vtr_saida->bindParam(':situacao', $situacao_saida, PDO::PARAM_STR);
$vtr_saida->bindParam(':data', $data, PDO::PARAM_STR);
$vtr_saida->execute();
$vtr_saida_total = $vtr_saida->fetchAll(PDO::FETCH_ASSOC);
$vtr_saida_count = count($vtr_saida_total);

$consulta_rel_viaturas = $p2->prepare("SELECT rel_viaturas.odometro, rel_viaturas.idsaida, viatura.combustivel, viatura.consumo FROM rel_viaturas 
                                            LEFT JOIN viatura ON (rel_viaturas.idvtr = viatura.id) 
                                            WHERE (rel_viaturas.data = :data AND rel_viaturas.idsaida is NOT NULL)");
$consulta_rel_viaturas->bindParam(":data", $data, PDO::PARAM_STR);
$consulta_rel_viaturas->execute();
$consulta_rel_total_viaturas = $consulta_rel_viaturas->fetchAll(PDO::FETCH_ASSOC);
$consulta_total_registro_viaturas = count($consulta_rel_total_viaturas);
for ($i = 0; $i < $consulta_total_registro_viaturas; $i++) {
  $reg_viaturas = $consulta_rel_total_viaturas[$i];
  $consulta_rel_viaturas2 = $p2->prepare("SELECT rel_viaturas.odometro FROM rel_viaturas WHERE rel_viaturas.id = :id");
  $consulta_rel_viaturas2->bindParam(":id", $reg_viaturas['idsaida'], PDO::PARAM_INT);
  $consulta_rel_viaturas2->execute();
  while ($reg = $consulta_rel_viaturas2->fetch(PDO::FETCH_ASSOC)) {
    $reg_viaturas2_odometro = $reg['odometro'];
  }
  $consumo_total_g = 0;
  $distancia_total_d = 0;
  $distancia_total_g = 0;
  $ditancia = ($reg_viaturas['odometro'] - $reg_viaturas2_odometro);
  $consumo = ($ditancia / $reg_viaturas['consumo']);
  if ($reg_viaturas['combustivel'] == 'G') {
    $consumo_total_g = $consumo_total_g + $consumo;
    $distancia_total_g = $distancia_total_g + $ditancia;
  } else {
    $consumo_total_d = $consumo_total_d + $consumo;
    $distancia_total_d = $distancia_total_d + $ditancia;
  }
}
