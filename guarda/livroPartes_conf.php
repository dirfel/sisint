<?php
$idusuario = $_SESSION['auth_data']['id'];

$dataLivro = filter_input(INPUT_POST, "dataLivro", FILTER_SANITIZE_STRING);
$dataLivro = date_converter($dataLivro);
$dataLivroEncode = base64_encode($dataLivro);

$dataAtual = date('Y-m-d');

$pdo = conectar("guarda");
$pdo2 = conectar("membros");

$consulta = $pdo->prepare("SELECT * FROM liv_partes_ofdia 
  LEFT JOIN liv_partes_ofdia_leituras ON (liv_partes_ofdia.idleituras = liv_partes_ofdia_leituras.id_leituras)  
  LEFT JOIN liv_partes_ofdia_sobrasresiduos ON (liv_partes_ofdia.idsobrasresiduos = liv_partes_ofdia_sobrasresiduos.id_sobrasresiduos) 
  LEFT JOIN liv_partes_ofdia_punidos ON (liv_partes_ofdia.id = liv_partes_ofdia_punidos.idlivro) 
  WHERE liv_partes_ofdia.data = :data");
$consulta->bindParam(":data", $dataLivro, PDO::PARAM_STR);
$consulta->execute();
$consulta_reg = $consulta->fetchAll();

if (count($consulta_reg) > 0 and $consulta_reg[0]['idusuario'] <> $idusuario) {
  $msgerro = base64_encode('Já possui Livro de Partes cadastrado nessa data!');
  header("Location: livroPartes.php?token=" . $msgerro);
  exit();
} else if ($consulta_reg[0]['editar'] == "0") {
  $msgerro = base64_encode('Já possui Livro de Partes cadastrado e Finalizado para Impressão, não podendo ser editado. Caso precise alterar, solicite suporte ao encarregado da Seç TI.');
  header("Location: livroPartes.php?token=" . $msgerro);
  exit();
} else if ($dataLivro > $dataAtual) {
  $msgerro = base64_encode('Data inválida');
  header("Location: livroPartes.php?token=" . $msgerro);
  exit();
}

$idLivroPartes = $consulta_reg[0]['id'];
$idLeituras = $consulta_reg[0]['id_leituras'];
$idSobrasresiduos = $consulta_reg[0]['id'];
$idOfDia = $consulta_reg[0]['idofdia'];
$idOfDiaAnterior = $consulta_reg[0]['idofdia_anterior'];
$idOfDiaProximo = $consulta_reg[0]['idofdia_proximo'];
$bi = $consulta_reg[0]['bi'];
$biData = $consulta_reg[0]['bi_data'];
$parada = $consulta_reg[0]['parada'];
$paradaObs = $consulta_reg[0]['parada_obs'];
$punidos = $consulta_reg[0]['punidos'];
$instalacoes = $consulta_reg[0]['instalacoes'];
$instalacoesObs = $consulta_reg[0]['instalacoes_obs'];
$carga = $consulta_reg[0]['carga'];
$cargaObs = $consulta_reg[0]['carga_obs'];
$energiaAtual = $consulta_reg[0]['energia_atual'];
$energia2 = $consulta_reg[0]['energia2'];
$energia1 = $consulta_reg[0]['energia1'];
$energiaAnterior = $consulta_reg[0]['energia_anterior'];
$aguaInternoAtual = $consulta_reg[0]['agua_int_atual'];
$aguaInternoAnterior = $consulta_reg[0]['agua_int_anterior'];
$aguaExternoAtual = $consulta_reg[0]['agua_ext_atual'];
$aguaExternoAnterior = $consulta_reg[0]['agua_ext_anterior'];
$rancho = $consulta_reg[0]['rancho'];
$ranchoFiscDia = $consulta_reg[0]['rancho_fiscdia'];
$ranchoCozDia = $consulta_reg[0]['rancho_cozdia'];
$ranchoObs = $consulta_reg[0]['rancho_obs'];
$ranchoSobras = $consulta_reg[0]['sobras'];
$ranchoResiduos = $consulta_reg[0]['residuos'];
$abastecimento = $consulta_reg[0]['abastecimento'];
$abastecimentoObs = $consulta_reg[0]['abastecimento_obs'];
$apresentacaoMil = $consulta_reg[0]['apresentacaomil'];
$apresentacaoMilObs = $consulta_reg[0]['apresentacaomil_obs'];
$ocorrencias = $consulta_reg[0]['ocorrencias'];
$ocorrenciasObs = $consulta_reg[0]['ocorrencias_obs'];
$anexos = $consulta_reg[0]['anexos'];

for ($i = 0; ($i < 10 and $consulta_reg[$i]['idpunido'] <> ""); $i++) {
  $idPunido[$i] = $consulta_reg[$i]['idpunido'];
  $idPunicao[$i] = $consulta_reg[$i]['punicao'];
  $data_inicio[$i] = $consulta_reg[$i]['data_inicio'];
  $data_termino[$i] = $consulta_reg[$i]['data_termino'];
  $p_bi[$i] = $consulta_reg[$i]['p_bi'];
  $p_bi_data[$i] = $consulta_reg[$i]['p_bi_data'];
}

if ($idLivroPartes <> "") {
  $consultaPunidos = $pdo->prepare("SELECT * FROM liv_partes_ofdia_punidos 
    WHERE (data_inicio <= :data AND data_termino > :data AND idlivro <> :idlivro)");
  $consultaPunidos->bindParam(":data", $dataLivro, PDO::PARAM_STR);
  $consultaPunidos->bindParam(":idlivro", $idLivroPartes, PDO::PARAM_STR);
  $consultaPunidos->execute();
  $consultaRegPunidos = $consultaPunidos->fetchAll();
} else {
  $consultaPunidos = $pdo->prepare("SELECT * FROM liv_partes_ofdia_punidos 
    WHERE (data_inicio <= :data AND data_termino > :data)");
  $consultaPunidos->bindParam(":data", $dataLivro, PDO::PARAM_STR);
  $consultaPunidos->execute();
  $consultaRegPunidos = $consultaPunidos->fetchAll();
}
