<?php
require "../recursos/models/versession.php";
require "../recursos/models/conexao.php";
if (!isset($_POST['action'])) {
  header('Location: index.php');
  exit();
}

$idusuario = $_SESSION['auth_data']['id'];

$editar = filter_input(INPUT_POST, "action", FILTER_SANITIZE_STRING);
$dataLivro = base64_decode(filter_input(INPUT_GET, "dataLivro", FILTER_SANITIZE_STRING));
$idOfDia = base64_decode(filter_input(INPUT_POST, "idOfDia", FILTER_SANITIZE_SPECIAL_CHARS));
$idOfDiaAnterior = base64_decode(filter_input(INPUT_POST, "idOfDiaAnterior", FILTER_SANITIZE_SPECIAL_CHARS));
$idOfDiaProximo = base64_decode(filter_input(INPUT_POST, "idOfDiaProximo", FILTER_SANITIZE_SPECIAL_CHARS));
$bi = filter_input(INPUT_POST, "bi", FILTER_SANITIZE_NUMBER_INT);
$biData = filter_input(INPUT_POST, "biData", FILTER_SANITIZE_STRING);
$biData = date_converter($biData);
$parada = filter_input(INPUT_POST, "parada", FILTER_SANITIZE_STRING);
$paradaObs = filter_input(INPUT_POST, "paradaObs", FILTER_SANITIZE_STRING);
$punidos = filter_input(INPUT_POST, "punidos", FILTER_SANITIZE_STRING);
$instalacoes = filter_input(INPUT_POST, "instalacoes", FILTER_SANITIZE_STRING);
$instalacoesObs = filter_input(INPUT_POST, "instalacoesObs", FILTER_SANITIZE_STRING);
$carga = filter_input(INPUT_POST, "carga", FILTER_SANITIZE_STRING);
$cargaObs = filter_input(INPUT_POST, "cargaObs", FILTER_SANITIZE_STRING);
$energiaAtual = filter_input(INPUT_POST, "energiaAtual", FILTER_SANITIZE_NUMBER_INT);
$energia2 = filter_input(INPUT_POST, "energia2", FILTER_SANITIZE_NUMBER_INT);
$energia1 = filter_input(INPUT_POST, "energia1", FILTER_SANITIZE_NUMBER_INT);
$energiaAnterior = filter_input(INPUT_POST, "energiaAnterior", FILTER_SANITIZE_NUMBER_INT);
$energiaConsumo = filter_input(INPUT_POST, "energiaConsumo", FILTER_SANITIZE_NUMBER_INT);
$aguaInternoAtual = filter_input(INPUT_POST, "aguaInternoAtual", FILTER_SANITIZE_NUMBER_INT);
$aguaInternoAnterior = filter_input(INPUT_POST, "aguaInternoAnterior", FILTER_SANITIZE_NUMBER_INT);
$aguaInternoConsumo = filter_input(INPUT_POST, "aguaInternoConsumo", FILTER_SANITIZE_NUMBER_INT);
$aguaExternoAtual = filter_input(INPUT_POST, "aguaExternoAtual", FILTER_SANITIZE_NUMBER_INT);
$aguaExternoAnterior = filter_input(INPUT_POST, "aguaExternoAnterior", FILTER_SANITIZE_NUMBER_INT);
$aguaExternoConsumo = filter_input(INPUT_POST, "aguaExternoConsumo", FILTER_SANITIZE_NUMBER_INT);
$temp1 = filter_input(INPUT_POST, "temp1", FILTER_SANITIZE_NUMBER_INT);
$temp2 = filter_input(INPUT_POST, "temp2", FILTER_SANITIZE_NUMBER_INT);
$temp3 = filter_input(INPUT_POST, "temp3", FILTER_SANITIZE_NUMBER_INT);
$temp5 = filter_input(INPUT_POST, "temp5", FILTER_SANITIZE_NUMBER_INT);
$temp6 = filter_input(INPUT_POST, "temp6", FILTER_SANITIZE_NUMBER_INT);
$temp7 = filter_input(INPUT_POST, "temp7", FILTER_SANITIZE_NUMBER_INT);
$temp1 = $temp1 > 80 ? floor($temp1/10) : $temp1;
$temp2 = $temp2 > 80 ? floor($temp2/10) : $temp2;
$temp3 = $temp3 > 80 ? floor($temp3/10) : $temp3;
$temp5 = $temp5 > 80 ? floor($temp5/10) : $temp5;
$temp6 = $temp6 > 80 ? floor($temp6/10) : $temp6;
$temp7 = $temp7 > 80 ? floor($temp7/10) : $temp7;
$umid1 = filter_input(INPUT_POST, "umid1", FILTER_SANITIZE_NUMBER_INT);
$umid2 = filter_input(INPUT_POST, "umid2", FILTER_SANITIZE_NUMBER_INT);
$umid3 = filter_input(INPUT_POST, "umid3", FILTER_SANITIZE_NUMBER_INT);
$umid5 = filter_input(INPUT_POST, "umid5", FILTER_SANITIZE_NUMBER_INT);
$umid6 = filter_input(INPUT_POST, "umid6", FILTER_SANITIZE_NUMBER_INT);
$umid7 = filter_input(INPUT_POST, "umid7", FILTER_SANITIZE_NUMBER_INT);
$umid1 = $umid1 > 100 ? floor($umid1/10) : $umid1;
$umid2 = $umid2 > 100 ? floor($umid2/10) : $umid2;
$umid3 = $umid3 > 100 ? floor($umid3/10) : $umid3;
$umid5 = $umid5 > 100 ? floor($umid5/10) : $umid5;
$umid6 = $umid6 > 100 ? floor($umid6/10) : $umid6;
$umid7 = $umid7 > 100 ? floor($umid7/10) : $umid7;
$rancho = filter_input(INPUT_POST, "rancho", FILTER_SANITIZE_STRING);
$ranchoFiscDia = filter_input(INPUT_POST, "ranchoFiscDia", FILTER_SANITIZE_STRING);
$ranchoCozDia = filter_input(INPUT_POST, "ranchoCozDia", FILTER_SANITIZE_STRING);
$ranchoObs = filter_input(INPUT_POST, "ranchoObs", FILTER_SANITIZE_STRING);
$ranchoSobras = filter_input(INPUT_POST, "ranchoSobras", FILTER_SANITIZE_NUMBER_INT);
$ranchoResiduos = filter_input(INPUT_POST, "ranchoResiduos", FILTER_SANITIZE_NUMBER_INT);
$abastecimento = filter_input(INPUT_POST, "abastecimento", FILTER_SANITIZE_STRING);
$abastecimentoObs = filter_input(INPUT_POST, "abastecimentoObs", FILTER_SANITIZE_STRING);
$apresentacaoMil = filter_input(INPUT_POST, "apresentacaoMil", FILTER_SANITIZE_STRING);
$apresentacaoMilObs = filter_input(INPUT_POST, "apresentacaoMilObs", FILTER_SANITIZE_STRING);
$ocorrencias = filter_input(INPUT_POST, "ocorrencias", FILTER_SANITIZE_STRING);
$ocorrenciasObs = filter_input(INPUT_POST, "ocorrenciasObs", FILTER_SANITIZE_STRING);
$anexos = filter_input(INPUT_POST, "anexos", FILTER_SANITIZE_STRING);

for ($i = 0; $i < 10; $i++) {
  if (filter_input(INPUT_POST, "idPunido_" . $i, FILTER_SANITIZE_STRING) <> "") {
    $idPunido[$i] = base64_decode(filter_input(INPUT_POST, "idPunido_" . $i, FILTER_SANITIZE_STRING));
    $idPunicao[$i] = filter_input(INPUT_POST, "idPunicao_" . $i, FILTER_SANITIZE_STRING);
    $data_inicio[$i] = date_converter(filter_input(INPUT_POST, "data_inicio_" . $i, FILTER_SANITIZE_STRING));
    $data_termino[$i] = date_converter(filter_input(INPUT_POST, "data_termino_" . $i, FILTER_SANITIZE_STRING));
    $p_bi[$i] = filter_input(INPUT_POST, "p_bi_" . $i, FILTER_SANITIZE_NUMBER_INT);
    $p_bi_data[$i] = date_converter(filter_input(INPUT_POST, "p_bi_data_" . $i, FILTER_SANITIZE_STRING));
  }
}


$pdo = conectar("guarda");
$pdo2 = conectar("membros");

$selectIdEditar = $pdo->prepare("SELECT id, editar, idleituras, idsobrasresiduos FROM liv_partes_ofdia WHERE data = :data");
$selectIdEditar->bindParam(":data", $dataLivro, PDO::PARAM_STR);
$selectIdEditar->execute();
while ($reg = $selectIdEditar->fetch(PDO::FETCH_ASSOC)) {
  $consultaId = $reg['id'];
  $consultaEditar = $reg['editar'];
  $consultaLeituras = $reg['idleituras'];
  $consultaSobrasResiduos = $reg['idsobrasresiduos'];
}

if ($consultaEditar == NULL) {
    try {
        
        $livPartesOfdiaLeituras = $pdo->prepare("INSERT INTO liv_partes_ofdia_leituras (energia_anterior, energia1, energia2, energia_atual, agua_int_anterior, agua_int_atual, 
      agua_ext_anterior, agua_ext_atual, temp1, temp2, temp3, temp5, temp6, temp7, umid1, umid2, umid3, umid5, umid6, umid7) 
      VALUES (:energia_anterior, :energia1, :energia2, :energia_atual, :agua_int_anterior, :agua_int_atual, 
      :agua_ext_anterior, :agua_ext_atual, :temp1, :temp2, :temp3, :temp5, :temp6, :temp7, :umid1, :umid2, :umid3, :umid5, :umid6, :umid7)");
    $livPartesOfdiaLeituras->bindParam(":energia_anterior", $energiaAnterior, PDO::PARAM_INT);
    $livPartesOfdiaLeituras->bindParam(":energia1", $energia1, PDO::PARAM_INT);
    $livPartesOfdiaLeituras->bindParam(":energia2", $energia2, PDO::PARAM_INT);
    $livPartesOfdiaLeituras->bindParam(":energia_atual", $energiaAtual, PDO::PARAM_INT);
    $livPartesOfdiaLeituras->bindParam(":agua_int_anterior", $aguaInternoAnterior, PDO::PARAM_INT);
    $livPartesOfdiaLeituras->bindParam(":agua_int_atual", $aguaInternoAtual, PDO::PARAM_INT);
    $livPartesOfdiaLeituras->bindParam(":agua_ext_anterior", $aguaExternoAnterior, PDO::PARAM_INT);
    $livPartesOfdiaLeituras->bindParam(":agua_ext_atual", $aguaExternoAtual, PDO::PARAM_INT);
    $livPartesOfdiaLeituras->bindParam(":temp1", $temp1, PDO::PARAM_INT);
    $livPartesOfdiaLeituras->bindParam(":temp2", $temp2, PDO::PARAM_INT);
    $livPartesOfdiaLeituras->bindParam(":temp3", $temp3, PDO::PARAM_INT);
    $livPartesOfdiaLeituras->bindParam(":temp5", $temp5, PDO::PARAM_INT);
    $livPartesOfdiaLeituras->bindParam(":temp6", $temp6, PDO::PARAM_INT);
    $livPartesOfdiaLeituras->bindParam(":temp7", $temp7, PDO::PARAM_INT);
    $livPartesOfdiaLeituras->bindParam(":umid1", $umid1, PDO::PARAM_INT);
    $livPartesOfdiaLeituras->bindParam(":umid2", $umid2, PDO::PARAM_INT);
    $livPartesOfdiaLeituras->bindParam(":umid3", $umid3, PDO::PARAM_INT);
    $livPartesOfdiaLeituras->bindParam(":umid5", $umid5, PDO::PARAM_INT);
    $livPartesOfdiaLeituras->bindParam(":umid6", $umid6, PDO::PARAM_INT);
    $livPartesOfdiaLeituras->bindParam(":umid7", $umid7, PDO::PARAM_INT);
    $livPartesOfdiaLeituras->execute();
        
    $livPartesOfdiaSobrasResiduos = $pdo->prepare("INSERT INTO liv_partes_ofdia_sobrasresiduos (sobras, residuos) 
      VALUES (:sobras, :residuos)");
    $livPartesOfdiaSobrasResiduos->bindParam(":sobras", $ranchoSobras, PDO::PARAM_INT);
    $livPartesOfdiaSobrasResiduos->bindParam(":residuos", $ranchoResiduos, PDO::PARAM_INT);
    $livPartesOfdiaSobrasResiduos->execute();

    $consultaLivPartesOfdiaLeituras = $pdo->prepare("SELECT id_leituras FROM liv_partes_ofdia_leituras ORDER BY id_leituras DESC LIMIT 1");
    $consultaLivPartesOfdiaLeituras->execute();
    while ($reg = $consultaLivPartesOfdiaLeituras->fetch(PDO::FETCH_ASSOC)) {
      $idleituras = $reg['id_leituras'];
    }

    $consultaLivPartesOfdiaSobrasResiduos = $pdo->prepare("SELECT id_sobrasresiduos FROM liv_partes_ofdia_sobrasresiduos ORDER BY id_sobrasresiduos DESC LIMIT 1");
    $consultaLivPartesOfdiaSobrasResiduos->execute();
    while ($reg = $consultaLivPartesOfdiaSobrasResiduos->fetch(PDO::FETCH_ASSOC)) {
      $idsobrasresiduos = $reg['id_sobrasresiduos'];
    }

    $livPartesOfdia = $pdo->prepare("INSERT INTO liv_partes_ofdia (idusuario, data, idofdia, idofdia_anterior, idofdia_proximo, bi, bi_data, parada, 
      parada_obs, punidos, instalacoes, instalacoes_obs, carga, carga_obs, rancho, rancho_fiscdia, rancho_cozdia, rancho_obs, abastecimento, 
      abastecimento_obs, apresentacaomil, apresentacaomil_obs, ocorrencias, ocorrencias_obs, anexos, editar, idleituras, idsobrasresiduos) 
      VALUES (:idusuario, :data, :idofdia, :idofdia_anterior, :idofdia_proximo, :bi, :bi_data, :parada, 
      :parada_obs, :punidos, :instalacoes, :instalacoes_obs, :carga, :carga_obs, :rancho, :rancho_fiscdia, :rancho_cozdia, :rancho_obs, :abastecimento, 
      :abastecimento_obs, :apresentacaomil, :apresentacaomil_obs, :ocorrencias, :ocorrencias_obs, :anexos, :editar, :idleituras, :idsobrasresiduos)");
    $livPartesOfdia->bindParam(":idusuario", $idusuario, PDO::PARAM_INT);
    $livPartesOfdia->bindParam(":data", $dataLivro, PDO::PARAM_STR);
    $livPartesOfdia->bindParam(":idofdia", $idOfDia, PDO::PARAM_INT);
    $livPartesOfdia->bindParam(":idofdia_anterior", $idOfDiaAnterior, PDO::PARAM_INT);
    $livPartesOfdia->bindParam(":idofdia_proximo", $idOfDiaProximo, PDO::PARAM_INT);
    $livPartesOfdia->bindParam(":bi", $bi, PDO::PARAM_INT);
    $livPartesOfdia->bindParam(":bi_data", $biData, PDO::PARAM_STR);
    $livPartesOfdia->bindParam(":parada", $parada, PDO::PARAM_STR);
    $livPartesOfdia->bindParam(":parada_obs", $paradaObs, PDO::PARAM_STR);
    $livPartesOfdia->bindParam(":punidos", $punidos, PDO::PARAM_STR);
    $livPartesOfdia->bindParam(":instalacoes", $instalacoes, PDO::PARAM_STR);
    $livPartesOfdia->bindParam(":instalacoes_obs", $instalacoesObs, PDO::PARAM_STR);
    $livPartesOfdia->bindParam(":carga", $carga, PDO::PARAM_STR);
    $livPartesOfdia->bindParam(":carga_obs", $cargaObs, PDO::PARAM_STR);
    $livPartesOfdia->bindParam(":rancho", $rancho, PDO::PARAM_STR);
    $livPartesOfdia->bindParam(":rancho_fiscdia", $ranchoFiscDia, PDO::PARAM_STR);
    $livPartesOfdia->bindParam(":rancho_cozdia", $ranchoCozDia, PDO::PARAM_STR);
    $livPartesOfdia->bindParam(":rancho_obs", $ranchoObs, PDO::PARAM_STR);
    $livPartesOfdia->bindParam(":abastecimento", $abastecimento, PDO::PARAM_STR);
    $livPartesOfdia->bindParam(":abastecimento_obs", $abastecimentoObs, PDO::PARAM_STR);
    $livPartesOfdia->bindParam(":apresentacaomil", $apresentacaoMil, PDO::PARAM_STR);
    $livPartesOfdia->bindParam(":apresentacaomil_obs", $apresentacaoMilObs, PDO::PARAM_STR);
    $livPartesOfdia->bindParam(":ocorrencias", $ocorrencias, PDO::PARAM_STR);
    $livPartesOfdia->bindParam(":ocorrencias_obs", $ocorrenciasObs, PDO::PARAM_STR);
    $livPartesOfdia->bindParam(":anexos", $anexos, PDO::PARAM_STR);
    $livPartesOfdia->bindParam(":editar", $editar, PDO::PARAM_INT);
    $livPartesOfdia->bindParam(":idleituras", $idleituras, PDO::PARAM_INT);
    $livPartesOfdia->bindParam(":idsobrasresiduos", $idsobrasresiduos, PDO::PARAM_INT);
    $livPartesOfdia->execute();

    $consultaLivPartesOfdia = $pdo->prepare("SELECT id FROM liv_partes_ofdia ORDER BY id DESC LIMIT 1");
    $consultaLivPartesOfdia->execute();
    while ($reg = $consultaLivPartesOfdia->fetch(PDO::FETCH_ASSOC)) {
      $idlivro = $reg['id'];
    }

    for ($i = 0; $i < 10; $i++) {
      if ($idPunido[$i] <> "") {
        $livPartesOfdiaPunidos[$i] = $pdo->prepare("INSERT INTO liv_partes_ofdia_punidos (idlivro, idpunido, punicao, data_inicio, 
          data_termino, p_bi, p_bi_data)
          VALUES (:idlivro, :idpunido, :punicao, :data_inicio, 
          :data_termino, :p_bi, :p_bi_data)");
        $livPartesOfdiaPunidos[$i]->bindParam(":idlivro", $idlivro, PDO::PARAM_INT);
        $livPartesOfdiaPunidos[$i]->bindParam(":idpunido", $idPunido[$i], PDO::PARAM_INT);
        $livPartesOfdiaPunidos[$i]->bindParam(":punicao", $idPunicao[$i], PDO::PARAM_STR);
        $livPartesOfdiaPunidos[$i]->bindParam(":data_inicio", $data_inicio[$i], PDO::PARAM_STR);
        $livPartesOfdiaPunidos[$i]->bindParam(":data_termino", $data_termino[$i], PDO::PARAM_STR);
        $livPartesOfdiaPunidos[$i]->bindParam(":p_bi", $p_bi[$i], PDO::PARAM_INT);
        $livPartesOfdiaPunidos[$i]->bindParam(":p_bi_data", $p_bi_data[$i], PDO::PARAM_STR);
        $livPartesOfdiaPunidos[$i]->execute();
      }
    }

    if ($livPartesOfdia and $livPartesOfdiaLeituras and $livPartesOfdiaSobrasResiduos) {
      $obs = "Lançou o Livro de Partes de " . date_converter2($dataLivro);
      gerar_log_usuario($sistema, $obs);
      $msgsuccess = base64_encode('Livro de Partes lançado com sucesso!');
    } else {
      $msgerro = base64_encode('Erro desconhecido!');
      header("Location: livroPartes.php?token=" . $msgerro);
      exit();
    }
  } catch (PDOException $e) {
    echo $e->getMessage();
    $msgerro = base64_encode('Erro na base de dados!');
    header("Location: livroPartes.php?token=" . $msgerro);
    exit();
  }
} else if ($consultaEditar == 1) {
    
    try {
    $updateLivPartesOfdia = $pdo->prepare("UPDATE liv_partes_ofdia SET idusuario = :idusuario, data = :data, idofdia = :idofdia, 
      idofdia_anterior = :idofdia_anterior, idofdia_proximo = :idofdia_proximo, bi = :bi, bi_data = :bi_data, parada = :parada, 
      parada_obs = :parada_obs, punidos = :punidos, instalacoes = :instalacoes, instalacoes_obs = :instalacoes_obs, 
      carga = :carga, carga_obs = :carga_obs, rancho = :rancho, rancho_fiscdia = :rancho_fiscdia, rancho_cozdia = :rancho_cozdia, 
      rancho_obs = :rancho_obs, abastecimento = :abastecimento, abastecimento_obs = :abastecimento_obs, apresentacaomil = :apresentacaomil, 
      apresentacaomil_obs = :apresentacaomil_obs, ocorrencias = :ocorrencias, ocorrencias_obs = :ocorrencias_obs, anexos = :anexos, editar = :editar 
      WHERE id = :id");

    $updateLivPartesOfdia->bindParam(":id", $consultaId, PDO::PARAM_INT);
    $updateLivPartesOfdia->bindParam(":idusuario", $idusuario, PDO::PARAM_INT);
    $updateLivPartesOfdia->bindParam(":data", $dataLivro, PDO::PARAM_STR);
    $updateLivPartesOfdia->bindParam(":idofdia", $idOfDia, PDO::PARAM_INT);
    $updateLivPartesOfdia->bindParam(":idofdia_anterior", $idOfDiaAnterior, PDO::PARAM_INT);
    $updateLivPartesOfdia->bindParam(":idofdia_proximo", $idOfDiaProximo, PDO::PARAM_INT);
    $updateLivPartesOfdia->bindParam(":bi", $bi, PDO::PARAM_INT);
    $updateLivPartesOfdia->bindParam(":bi_data", $biData, PDO::PARAM_STR);
    $updateLivPartesOfdia->bindParam(":parada", $parada, PDO::PARAM_STR);
    $updateLivPartesOfdia->bindParam(":parada_obs", $paradaObs, PDO::PARAM_STR);
    $updateLivPartesOfdia->bindParam(":punidos", $punidos, PDO::PARAM_STR);
    $updateLivPartesOfdia->bindParam(":instalacoes", $instalacoes, PDO::PARAM_STR);
    $updateLivPartesOfdia->bindParam(":instalacoes_obs", $instalacoesObs, PDO::PARAM_STR);
    $updateLivPartesOfdia->bindParam(":carga", $carga, PDO::PARAM_STR);
    $updateLivPartesOfdia->bindParam(":carga_obs", $cargaObs, PDO::PARAM_STR);
    $updateLivPartesOfdia->bindParam(":rancho", $rancho, PDO::PARAM_STR);
    $updateLivPartesOfdia->bindParam(":rancho_fiscdia", $ranchoFiscDia, PDO::PARAM_STR);
    $updateLivPartesOfdia->bindParam(":rancho_cozdia", $ranchoCozDia, PDO::PARAM_STR);
    $updateLivPartesOfdia->bindParam(":rancho_obs", $ranchoObs, PDO::PARAM_STR);
    $updateLivPartesOfdia->bindParam(":abastecimento", $abastecimento, PDO::PARAM_STR);
    $updateLivPartesOfdia->bindParam(":abastecimento_obs", $abastecimentoObs, PDO::PARAM_STR);
    $updateLivPartesOfdia->bindParam(":apresentacaomil", $apresentacaoMil, PDO::PARAM_STR);
    $updateLivPartesOfdia->bindParam(":apresentacaomil_obs", $apresentacaoMilObs, PDO::PARAM_STR);
    $updateLivPartesOfdia->bindParam(":ocorrencias", $ocorrencias, PDO::PARAM_STR);
    $updateLivPartesOfdia->bindParam(":ocorrencias_obs", $ocorrenciasObs, PDO::PARAM_STR);
    $updateLivPartesOfdia->bindParam(":anexos", $anexos, PDO::PARAM_STR);
    $updateLivPartesOfdia->bindParam(":editar", $editar, PDO::PARAM_INT);
    $updateLivPartesOfdia->execute();

    $updateLivPartesOfdiaLeituras = $pdo->prepare("
        UPDATE liv_partes_ofdia_leituras 
        SET 
            energia_anterior = ".$energiaAnterior.", energia1 = ".$energia1.", 
            energia2 = ".$energia2.", energia_atual = ".$energiaAtual.", 
            agua_int_anterior = ".$aguaInternoAnterior.", agua_int_atual = ".$aguaInternoAtual.", 
            agua_ext_anterior = ".$aguaExternoAnterior.", agua_ext_atual = ".$aguaExternoAtual.", 
            temp1 = ".$temp1.", temp2 = ".$temp2.", temp3 = ".$temp3.", temp5 = ".$temp5.", temp6 = ".$temp6.", temp7 = ".$temp7.", 
            umid1 = ".$umid1.", umid2 = ".$umid2.", umid3 = ".$umid3.", umid5 = ".$umid5.", umid6 = ".$umid6.", umid7 = ".$umid7."
        WHERE id_leituras = ".$consultaLeituras
    );
    $updateLivPartesOfdiaLeituras->execute();
    $count = $updateLivPartesOfdiaLeituras->rowCount();

    $updateLivPartesOfdiaSobrasResiduos = $pdo->prepare("UPDATE liv_partes_ofdia_sobrasresiduos SET sobras = :sobras, residuos = :residuos 
      WHERE id_sobrasresiduos = :id_sobrasresiduos");
    $updateLivPartesOfdiaSobrasResiduos->bindParam(":id_sobrasresiduos", $consultaSobrasResiduos, PDO::PARAM_INT);
    $updateLivPartesOfdiaSobrasResiduos->bindParam(":sobras", $ranchoSobras, PDO::PARAM_INT);
    $updateLivPartesOfdiaSobrasResiduos->bindParam(":residuos", $ranchoResiduos, PDO::PARAM_INT);
    $updateLivPartesOfdiaSobrasResiduos->execute();

    $consulta_Punidos = $pdo->prepare("SELECT * FROM liv_partes_ofdia_punidos WHERE idlivro = :idlivro ORDER BY id_punidos ASC");
    $consulta_Punidos->bindParam(":idlivro", $consultaId, PDO::PARAM_INT); 
    $consulta_Punidos->execute();
    $consulta_Punidos_reg = $consulta_Punidos->fetchAll();
    for ($i = 0; $i < 10; $i++) {
      $consulta_idPunido[$i] = $consulta_Punidos_reg[$i]['idpunido'];
      $consulta_idPunicao[$i] = $consulta_Punidos_reg[$i]['punicao'];
      $consulta_data_inicio[$i] = date_converter($consulta_Punidos_reg[$i]['data_inicio']);
      $consulta_data_termino[$i] = date_converter($consulta_Punidos_reg[$i]['data_termino']);
      $consulta_p_bi[$i] = $consulta_Punidos_reg[$i]['p_bi'];
      $consulta_p_bi_data[$i] = date_converter($consulta_Punidos_reg[$i]['p_bi_data']);
    }

    for ($i = 0; $i < 10; $i++) {
      if (($idPunido[$i] <> "") and ($consulta_idPunido[$i] == "")) {
        $insertLivPartesOfdiaPunidos[$i] = $pdo->prepare("INSERT INTO liv_partes_ofdia_punidos (idlivro, idpunido, punicao, data_inicio, 
        data_termino, p_bi, p_bi_data)
        VALUES (:idlivro, :idpunido, :punicao, :data_inicio, 
        :data_termino, :p_bi, :p_bi_data)");
        $insertLivPartesOfdiaPunidos[$i]->bindParam(":idlivro", $consultaId, PDO::PARAM_INT);
        $insertLivPartesOfdiaPunidos[$i]->bindParam(":idpunido", $idPunido[$i], PDO::PARAM_INT);
        $insertLivPartesOfdiaPunidos[$i]->bindParam(":punicao", $idPunicao[$i], PDO::PARAM_STR);
        $insertLivPartesOfdiaPunidos[$i]->bindParam(":data_inicio", $data_inicio[$i], PDO::PARAM_STR);
        $insertLivPartesOfdiaPunidos[$i]->bindParam(":data_termino", $data_termino[$i], PDO::PARAM_STR);
        $insertLivPartesOfdiaPunidos[$i]->bindParam(":p_bi", $p_bi[$i], PDO::PARAM_INT);
        $insertLivPartesOfdiaPunidos[$i]->bindParam(":p_bi_data", $p_bi_data[$i], PDO::PARAM_STR);
        $insertLivPartesOfdiaPunidos[$i]->execute();
      } else if (($idPunido[$i] == "") and ($consulta_idPunido[$i] <> "")) {
        $deleteLivPartesOfdiaPunidos[$i] = $pdo->prepare("DELETE FROM liv_partes_ofdia_punidos WHERE (idlivro = :idlivro AND idpunido = :idpunido)");
        $deleteLivPartesOfdiaPunidos[$i]->bindParam(":idlivro", $consultaId, PDO::PARAM_INT);
        $deleteLivPartesOfdiaPunidos[$i]->bindParam(":idpunido", $consulta_idPunido[$i], PDO::PARAM_INT);
        $deleteLivPartesOfdiaPunidos[$i]->execute();
      } else if (
        ($idPunido[$i] <> $consulta_idPunido[$i]) or
        ($idPunicao[$i] <> $consulta_idPunicao[$i]) or
        ($data_inicio[$i] <> $consulta_data_inicio[$i]) or
        ($data_termino[$i] <> $consulta_data_termino[$i]) or
        ($p_bi[$i] <> $consulta_p_bi[$i]) or
        ($p_bi_data[$i] <> $consulta_p_bi_data[$i])
      ) {
        $updateLivPartesOfdiaPunidos[$i] = $pdo->prepare("UPDATE liv_partes_ofdia_punidos SET punicao = :punicao, data_inicio = :data_inicio, 
        data_termino = :data_termino, p_bi = :p_bi, p_bi_data = :p_bi_data WHERE (idlivro = :idlivro AND idpunido = :idpunido)");
        $updateLivPartesOfdiaPunidos[$i]->bindParam(":idlivro", $consultaId, PDO::PARAM_INT);
        $updateLivPartesOfdiaPunidos[$i]->bindParam(":idpunido", $idPunido[$i], PDO::PARAM_INT);
        $updateLivPartesOfdiaPunidos[$i]->bindParam(":punicao", $idPunicao[$i], PDO::PARAM_STR);
        $updateLivPartesOfdiaPunidos[$i]->bindParam(":data_inicio", $data_inicio[$i], PDO::PARAM_STR);
        $updateLivPartesOfdiaPunidos[$i]->bindParam(":data_termino", $data_termino[$i], PDO::PARAM_STR);
        $updateLivPartesOfdiaPunidos[$i]->bindParam(":p_bi", $p_bi[$i], PDO::PARAM_INT);
        $updateLivPartesOfdiaPunidos[$i]->bindParam(":p_bi_data", $p_bi_data[$i], PDO::PARAM_STR);
        $updateLivPartesOfdiaPunidos[$i]->execute();
      }
    }

    if ($updateLivPartesOfdia and $updateLivPartesOfdiaLeituras and $updateLivPartesOfdiaSobrasResiduos) {
      $obs = "Atualizou o Livro de Partes de " . date_converter2($dataLivro);
      gerar_log_usuario($sistema, $obs);
      $msgsuccess = base64_encode('Livro de Partes atualizado com sucesso!');
    } else {
      $msgerro = base64_encode('Erro na desconhecido! (2)');
      header("Location: livroPartes.php?token=" . $msgerro);
      exit();
    }
  } catch (PDOException $e) {
    echo $e->getMessage();
    $msgerro = base64_encode('Erro na base de dados!');
    header("Location: livroPartes.php?token=" . $msgerro);
    exit();
  }
} else if ($consultaEditar == 0) {
  $msgerro = base64_encode('Já possui Livro de Partes cadastrado e Finalizado para Impressão, não podendo ser editado');
  header("Location: livroPartes.php?token=" . $msgerro);
  exit();
}
header("Location: livroPartes.php?token2=" . $msgsuccess);
exit();
