<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
if (!isset($_POST['action'])) {
  header('Location: rot_ronda3.php');
  exit(); 
}
$tipo;
if($_POST['data_r'] == null){
    $tipo = 'perma';
} else if($_POST['data_p'] == null) {
    $tipo = 'ronda';
} else {
    $tipo = 'ambos';
}

$idusuario = $_SESSION['auth_data']['id'];
//$convertdata1 = filter_input(INPUT_POST, "data_p", FILTER_SANITIZE_STRING);
//$data_p = implode('/', array_reverse(explode('-', $convertdata1)));
$data_p = filter_input(INPUT_POST, "data_p", FILTER_SANITIZE_STRING);
$data_p2 = date_converter($data_p);
$converthora1 = filter_input(INPUT_POST, "hora_p", FILTER_SANITIZE_STRING);
if (strlen($converthora1) == 4) {
  $converthora1 = ("0" . $converthora1);
}
$hora_p = ($converthora1 . ':00');
//$convertdata2 = filter_input(INPUT_POST, "data_r", FILTER_SANITIZE_STRING);
//$data_r = implode('/', array_reverse(explode('-', $convertdata2)));
$data_r = filter_input(INPUT_POST, "data_r", FILTER_SANITIZE_STRING);
$data_r2 = date_converter($data_r);
$converthora2 = filter_input(INPUT_POST, "hora_r", FILTER_SANITIZE_STRING);
if (strlen($converthora2) == 4) {
  $converthora2 = ("0" . $converthora2);
}
$hora_r = ($converthora2 . ':00');
$idfuncao = base64_decode(filter_input(INPUT_POST, "idfuncao", FILTER_SANITIZE_SPECIAL_CHARS));
$idmembro = base64_decode(filter_input(INPUT_POST, "tkusr", FILTER_SANITIZE_SPECIAL_CHARS));
$alteracao_p = filter_input(INPUT_POST, "alteracao_p", FILTER_SANITIZE_STRING); 
$alteracao_r = filter_input(INPUT_POST, "alteracao_r", FILTER_SANITIZE_STRING);
if ($alteracao_p == '1' or $alteracao_r == '1') {
  $alteracao = '1';
} else {
  $alteracao = '0';
}
$p1 = base64_decode(filter_input(INPUT_POST, "p1", FILTER_SANITIZE_SPECIAL_CHARS));
$p2 = base64_decode(filter_input(INPUT_POST, "p2", FILTER_SANITIZE_SPECIAL_CHARS));
$p3 = base64_decode(filter_input(INPUT_POST, "p3", FILTER_SANITIZE_SPECIAL_CHARS));
$p4 = base64_decode(filter_input(INPUT_POST, "p4", FILTER_SANITIZE_SPECIAL_CHARS));
$p5 = base64_decode(filter_input(INPUT_POST, "p5", FILTER_SANITIZE_SPECIAL_CHARS));
$p6 = base64_decode(filter_input(INPUT_POST, "p6", FILTER_SANITIZE_SPECIAL_CHARS));
$aloj1 = base64_decode(filter_input(INPUT_POST, "aloj1", FILTER_SANITIZE_SPECIAL_CHARS));
$aloj2 = base64_decode(filter_input(INPUT_POST, "aloj2", FILTER_SANITIZE_SPECIAL_CHARS));
$obs2 = filter_input(INPUT_POST, "obs", FILTER_SANITIZE_STRING);
$situacao = $_POST['action'];

$pdo1 = conectar("guarda");
$pdo2 = conectar("membros");

$select_funcao = $pdo1->prepare("SELECT * FROM rot_guarda_funcao WHERE idfuncao = :idfuncao");
$select_funcao->bindParam(":idfuncao", $idfuncao, PDO::PARAM_INT);
$select_funcao->execute();
while ($reg = $select_funcao->fetch(PDO::FETCH_ASSOC)) {
  $nome_funcao = $reg['nomefuncaosimples'];
}

$select_rel = $pdo1->prepare("SELECT * FROM rel_rot_ronda WHERE data_p = :data_p AND idfuncao = :idfuncao");
$select_rel->bindParam(":data_p", $data_p, PDO::PARAM_STR);
$select_rel->bindParam(":idfuncao", $idfuncao, PDO::PARAM_INT);
$select_rel->execute();
$select_rel_total = $select_rel->fetchAll(PDO::FETCH_ASSOC);
$select_rel_total_registro = count($select_rel_total);

$sistema = base64_encode('GUARDA');
$obs = "Lançou Militar " . $nome_funcao . ": " . $situacao;
$tipo;

$dataatual = date("d/m/Y");
$dataatual2 = date("Y-m-d");
//parei aqui -> preciso separar ronda de permanência
$stmtez;
if($tipo == 'perma') {
  $stmtez = $pdo1->prepare("INSERT INTO rel_rot_ronda(idmembro, idusuario, tipo, data_p, hora_p, idfuncao, alteracao, obs)"
  . "VALUES (:idmembro, :idusuario, :tipo, :data_p, :hora_p, :idfuncao, :alteracao, :obs)");

$stmtez->bindParam(":idmembro", $idmembro, PDO::PARAM_INT);
$stmtez->bindParam(":idusuario", $_SESSION['auth_data']['id'], PDO::PARAM_INT);
$stmtez->bindParam(":tipo", $tipo, PDO::PARAM_STR);
$stmtez->bindParam(":data_p", $data_p, PDO::PARAM_STR);
$stmtez->bindParam(":hora_p", $hora_p, PDO::PARAM_STR);
$stmtez->bindParam(":idfuncao", $idfuncao, PDO::PARAM_INT);
$stmtez->bindParam(":alteracao", $alteracao, PDO::PARAM_INT);
$stmtez->bindParam(":obs", $obs2, PDO::PARAM_STR);

    
} else if($tipo == 'ronda') {
  $stmtez = $pdo1->prepare("INSERT INTO rel_rot_ronda(idmembro, idusuario, tipo, idfuncao, alteracao, data_r, hora_r, p1, p2, p3, p4, p5, p6, aloj1, aloj2, obs)"
  . "VALUES (:idmembro, :idusuario, :tipo, :idfuncao, :alteracao, :data_r, :hora_r, :p1, :p2, :p3, :p4, :p5, :p6, :aloj1, :aloj2, :obs)");

    $stmtez->bindParam(":idmembro", $idmembro, PDO::PARAM_INT);

$stmtez->bindParam(":idusuario", $_SESSION['auth_data']['id'], PDO::PARAM_INT);

$stmtez->bindParam(":tipo", $tipo, PDO::PARAM_STR);
$stmtez->bindParam(":data_r", $data_r, PDO::PARAM_STR);
$stmtez->bindParam(":hora_r", $hora_r, PDO::PARAM_STR);
$stmtez->bindParam(":idfuncao", $idfuncao, PDO::PARAM_INT);
$stmtez->bindParam(":alteracao", $alteracao, PDO::PARAM_INT);
$stmtez->bindParam(":p1", $p1, PDO::PARAM_INT);
$stmtez->bindParam(":p2", $p2, PDO::PARAM_INT);
$stmtez->bindParam(":p3", $p3, PDO::PARAM_INT);
$stmtez->bindParam(":p4", $p4, PDO::PARAM_INT);
$stmtez->bindParam(":p5", $p5, PDO::PARAM_INT);
$stmtez->bindParam(":p6", $p6, PDO::PARAM_INT);
$stmtez->bindParam(":aloj1", $aloj1, PDO::PARAM_INT);
$stmtez->bindParam(":aloj2", $aloj2, PDO::PARAM_INT);
$stmtez->bindParam(":obs", $obs2, PDO::PARAM_STR);

} else {
  $stmtez = $pdo1->prepare("INSERT INTO rel_rot_ronda(idmembro, idusuario, tipo, data_p, hora_p, idfuncao, alteracao, data_r, hora_r, p1, p2, p3, p4, p5, p6, aloj1, aloj2, obs)"
  . "VALUES (:idmembro, :idusuario, :tipo, :data_p, :hora_p, :idfuncao, :alteracao, :data_r, :hora_r, :p1, :p2, :p3, :p4, :p5, :p6, :aloj1, :aloj2, :obs)");

$stmtez->bindParam(":idmembro", $idmembro, PDO::PARAM_INT);
$stmtez->bindParam(":idusuario", $_SESSION['auth_data']['id'], PDO::PARAM_INT);
$stmtez->bindParam(":tipo", $tipo, PDO::PARAM_STR);
$stmtez->bindParam(":data_p", $data_p, PDO::PARAM_STR);
$stmtez->bindParam(":hora_p", $hora_p, PDO::PARAM_STR);
$stmtez->bindParam(":idfuncao", $idfuncao, PDO::PARAM_INT);
$stmtez->bindParam(":alteracao", $alteracao, PDO::PARAM_INT);
$stmtez->bindParam(":data_r", $data_r, PDO::PARAM_STR);
$stmtez->bindParam(":hora_r", $hora_r, PDO::PARAM_STR);
$stmtez->bindParam(":p1", $p1, PDO::PARAM_INT);
$stmtez->bindParam(":p2", $p2, PDO::PARAM_INT);
$stmtez->bindParam(":p3", $p3, PDO::PARAM_INT);
$stmtez->bindParam(":p4", $p4, PDO::PARAM_INT);
$stmtez->bindParam(":p5", $p5, PDO::PARAM_INT);
$stmtez->bindParam(":p6", $p6, PDO::PARAM_INT);
$stmtez->bindParam(":aloj1", $aloj1, PDO::PARAM_INT);
$stmtez->bindParam(":aloj2", $aloj2, PDO::PARAM_INT);
$stmtez->bindParam(":obs", $obs2, PDO::PARAM_STR);
}

    if ($idfuncao == '' or $idfuncao == ' ' or $idfuncao == '0') {
      header('Location: rot_ronda3.php');
      exit();
    } else if (strtotime($dataatual2) >= strtotime($data_r2) and strtotime($dataatual2) >= strtotime($data_p2)) {
      $executa = $stmtez->execute();
      $executa2 = gerar_log_usuario($sistema, $obs);
      $msgsuccess = base64_encode('Cadastro realizado com sucesso!');
      echo 'sucesso';
      header('Location: rot_ronda3.php?token2=' . $msgsuccess);
      exit();
    } else {
      $msgerro = base64_encode('Erro no lançamento, verifique se a Data e Hora estão corretos!');
      header('Location: rot_ronda3.php?token=' . $msgerro);
      exit();
    }
    // break;
// }
