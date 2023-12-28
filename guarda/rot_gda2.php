<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if (!isset($_POST['action'])) {
  header('Location: rot_gda1.php');
  exit();
}

$idmembro = base64_decode(filter_input(INPUT_POST, "tkusr", FILTER_SANITIZE_SPECIAL_CHARS));
$armamento1 = filter_input(INPUT_POST, "arma0", FILTER_SANITIZE_STRING);
$armamento2 = filter_input(INPUT_POST, "arma1", FILTER_SANITIZE_STRING);
$num_armamento1 = filter_input(INPUT_POST, "arma_num0", FILTER_SANITIZE_NUMBER_INT);
$num_armamento2 = filter_input(INPUT_POST, "arma_num1", FILTER_SANITIZE_NUMBER_INT);
$idfuncao = filter_input(INPUT_POST, "idfuncao", FILTER_SANITIZE_NUMBER_INT);
$idquarto = filter_input(INPUT_POST, "idquarto", FILTER_SANITIZE_NUMBER_INT);
//$convertdata = filter_input(INPUT_POST, "data", FILTER_SANITIZE_STRING);
//$data = implode('/', array_reverse(explode('-', $convertdata)));
$data = filter_input(INPUT_POST, "data", FILTER_SANITIZE_STRING);
$data2 = date_converter($data);
$situacao = filter_input(INPUT_POST, "action", FILTER_SANITIZE_STRING);

if ($armamento1 == '') {
  $armamento1 = ' ';
  $num_armamento1 = '0';
}
if ($armamento2 == '') {
  $armamento2 = ' ';
  $num_armamento2 = '0';
}
if ($idfuncao < 9) {
  $idquarto = 0;
}

$pdo1 = conectar("guarda");
$pdo2 = conectar("membros");

$select_funcao = $pdo1->prepare("SELECT * FROM rot_guarda_funcao WHERE idfuncao = :idfuncao");
$select_funcao->bindParam(":idfuncao", $idfuncao, PDO::PARAM_INT);
$select_funcao->execute();
while ($reg = $select_funcao->fetch(PDO::FETCH_ASSOC)) {
  $nome_funcao = $reg['nomefuncaosimples'];
}

$select_rel = $pdo1->prepare("SELECT id FROM rel_rot_guarda WHERE data = :data AND idfuncao = :idfuncao");
$select_rel->bindParam(":data", $data, PDO::PARAM_STR);
$select_rel->bindParam(":idfuncao", $idfuncao, PDO::PARAM_INT);
$select_rel->execute();
$select_rel_total = $select_rel->fetchAll(PDO::FETCH_ASSOC);
$select_rel_total_registro = count($select_rel_total);

$select_usuarios = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE usuarios.id = :idmembro");
$select_usuarios->bindParam(":idmembro", $idmembro, PDO::PARAM_INT);
$select_usuarios->execute();
while ($reg3 = $select_usuarios->fetch(PDO::FETCH_ASSOC)) {
  $nome_usuarios = $reg3['nomeguerra'];
  $pg_usuarios = getPGrad($reg3['idpgrad']);
}

$sistema = base64_encode('GUARDA');
$obs = "Lançou Militar ID" . $idmembro . " " . $pg_usuarios . " " . $nome_usuarios . ": " . $situacao . " como " . $nome_funcao;

$dataatual = date("d/m/Y");
$dataatual2 = date("Y-m-d");
$horaatual = date("H:i:s");


$stmtez = $pdo1->prepare("INSERT INTO rel_rot_guarda(idmembro, data, idusuario, idfuncao, armamento1, armamento2, num_armamento1, num_armamento2, idquarto) "
  . "VALUES (:idmembro, :data, :idusuario, :idfuncao, :armamento1, :armamento2, :num_armamento1, :num_armamento2, :idquarto)");
$stmtez->bindParam(":idmembro", $idmembro, PDO::PARAM_INT);
$stmtez->bindParam(":data", $data, PDO::PARAM_STR);
$stmtez->bindParam(":idusuario", $_SESSION['auth_data']['id'], PDO::PARAM_STR);
$stmtez->bindParam(":idfuncao", $idfuncao, PDO::PARAM_INT);
$stmtez->bindParam(":armamento1", $armamento1, PDO::PARAM_STR);
$stmtez->bindParam(":armamento2", $armamento2, PDO::PARAM_STR);
$stmtez->bindParam(":num_armamento1", $num_armamento1, PDO::PARAM_INT);
$stmtez->bindParam(":num_armamento2", $num_armamento2, PDO::PARAM_INT);
$stmtez->bindParam(":idquarto", $idquarto, PDO::PARAM_STR);
$msgerr = base64_encode('Erro na tentativa de registrar o acesso, função encontra-se preenchida até seu limite!');
if (($idfuncao == 1 and $select_rel_total_registro >= 1) || 
    ($idfuncao == 2 and $select_rel_total_registro >= 1) || 
    ($idfuncao == 3 and $select_rel_total_registro >= 1) || 
    ($idfuncao == 4 and $select_rel_total_registro >= 1) || 
    ($idfuncao == 5 and $select_rel_total_registro >= 1) || 
    ($idfuncao == 6 and $select_rel_total_registro >= 1) || 
    ($idfuncao == 7 and $select_rel_total_registro >= 1) || 
    ($idfuncao == 8 and $select_rel_total_registro >= 1) || 
    ($idfuncao == 9 and $select_rel_total_registro >= 6) || 
    ($idfuncao == 10 and $select_rel_total_registro >= 15) || 
    ($idfuncao == 11 and $select_rel_total_registro >= 6) || 
    ($idfuncao == 12 and $select_rel_total_registro >= 3)) {
  header('Location: rot_gda1.php?token=' . $msgerr);
  exit();
} else {
  if (
    $idmembro == '' or $idmembro == ' ' or $idmembro == '0' or
    $idfuncao == '' or $idfuncao == ' ' or $idfuncao == '0' or
    $idquarto == '' or $idquarto == ' ' or $idquarto > '3' or
    $data == '' or $data == ' ' or $data == '0'
  ) {
    header('Location: rot_gda1.php');
    exit();
  } else if (strtotime($data2) <= strtotime($dataatual2)) {
    $executa = $stmtez->execute();
    $executa2 = gerar_log_usuario($sistema, $obs);
    $msgsuccess = base64_encode('Militar lançado com sucesso!');
    header('Location: rot_gda1.php?token2=' . $msgsuccess);
    exit();
  } else if (strtotime($data2) > strtotime($dataatual2)) {
    $msgerro = base64_encode('Erro na tentativa de registrar o acesso, data posteriores não são aceitas!');
    header('Location: rot_gda1.php?token=' . $msgerro);
    exit();
  } else {
    $msgerro = base64_encode('Erro na tentativa de registrar o acesso!');
    header('Location: rot_gda1.php?token=' . $msgerro);
    exit();
  }
}
