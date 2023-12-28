<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
if (!($_SESSION['nivel_guarda'] == "Anotador Gda" || $_SESSION['nivel_guarda'] == "Anotador Aloj" || $_SESSION['nivel_guarda'] == "Oficial e Sargento" || $_SESSION['nivel_guarda'] == "Supervisor" || $_SESSION['nivel_guarda'] == "Administrador")) {
    $msgerro = base64_encode('Usuário não possui acesso! Somente usuário: Anotador Aloj!');
    header('Location: index.php?token=' . $msgerro);
    exit();
  }
if (!isset($_POST['action'])) {
  header('Location: alojamento1.php');
  exit();
}

$idmembro = base64_decode(filter_input(INPUT_POST, "tkusr", FILTER_SANITIZE_SPECIAL_CHARS));
//$convertdata = filter_input(INPUT_POST, "data", FILTER_SANITIZE_STRING);
//$data = implode('/', array_reverse(explode('-', $convertdata)));
$data = filter_input(INPUT_POST, "data", FILTER_SANITIZE_STRING);
$data2 = date_converter($data);
$converthora = filter_input(INPUT_POST, "hora", FILTER_SANITIZE_STRING);
if (strlen($converthora) == 4) {
  $converthora = ("0" . $converthora);
}
$hora = ($converthora . ':00');
$situacao = filter_input(INPUT_POST, "action", FILTER_SANITIZE_STRING);

$pdo1 = conectar("guarda");
$pdo2 = conectar("membros");

$select_usuarios = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE id = :idmembro");
$select_usuarios->bindParam(":idmembro", $idmembro, PDO::PARAM_INT);
$select_usuarios->execute();
while ($reg = $select_usuarios->fetch(PDO::FETCH_ASSOC)) {
  $nome_usuarios = $reg['nomeguerra'];
  $pg_usuarios = getPGrad($reg['idpgrad']);
}

$sistema = base64_encode('GUARDA');
$obs = "Lançou Militar ID" . $idmembro . " " . $pg_usuarios . " " . $nome_usuarios . ": " . $situacao;

$dataatual = date("d/m/Y");
$dataatual2 = date("Y-m-d");
$horaatual = date("H:i:s");

$stmtez = $pdo1->prepare("INSERT INTO rel_alojamento(idmembro, data, hora, idusuario, situacao) "
  . "VALUES (:idmembro, :data, :hora, :idusuario, :situacao)");
$stmtez->bindParam(":idmembro", $idmembro, PDO::PARAM_INT);
$stmtez->bindParam(":data", $data, PDO::PARAM_STR);
$stmtez->bindParam(":hora", $hora, PDO::PARAM_STR);
$stmtez->bindParam(":idusuario", $_SESSION['auth_data']['id'], PDO::PARAM_STR);
$stmtez->bindParam(":situacao", $situacao, PDO::PARAM_STR);

if (
  $idmembro == '' or $idmembro == ' ' or $idmembro == '0' or
  $situacao == '' or $situacao == ' ' or $situacao == '0' or
  $converthora == '' or $converthora == ' ' or $converthora == '0' or
  $data == '' or $data == ' ' or $data == '0'
) {
  header('Location: alojamento1.php');
  exit();
} else if (
    (strtotime($dataatual2) == strtotime($data2) and strtotime($horaatual) >= strtotime($hora)) or 
    $_SESSION['nivel_guarda'] == 'Administrador' or
    strtotime($dataatual) > strtotime($data2)
    ) {
  $executa = $stmtez->execute();
  $executa2 = gerar_log_usuario($sistema, $obs);
  $msgsuccess = base64_encode('Militar lançado com sucesso!');
  header('Location: alojamento1.php?token2=' . $msgsuccess);
  exit();

} else {
  $msgerro = base64_encode('Erro na tentativa de registrar o acesso, data e hora inválida!');
  header('Location: alojamento1.php?token=' . $msgerro);
  exit();
}
