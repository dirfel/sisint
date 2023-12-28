<?php
require "../recursos/models/versession.php";
require "../recursos/models/conexao.php";
if ($_SESSION['nivel_com_soc'] == "Sem Acesso") {
    $msgerro = base64_encode('Usuário não possui acesso!');
    header('Location: ../sistemas/index.php?token=' . $msgerro);
    exit();
}
if (!isset($_POST['action'])) {
  header('Location: hospedes_ht.php');
  exit();
}

$identidade = filter_input(INPUT_POST, "identidade", FILTER_SANITIZE_NUMBER_INT);
$cpf = filter_input(INPUT_POST, "cpf", FILTER_SANITIZE_NUMBER_INT);
$cpf = preg_replace('/\D/', '', $cpf);
$nomecompleto = (getPGrad($_POST['idpgrad']) ?? '') . ' ' .filter_input(INPUT_POST, "nomecompleto", FILTER_SANITIZE_STRING);
$nomecompleto = mb_strtoupper($nomecompleto, 'UTF-8');
$datanascimento = filter_input(INPUT_POST, 'datanascimento', FILTER_SANITIZE_STRING);
$datanascimento2 = date_converter($datanascimento);
$celular = filter_input(INPUT_POST, "fonecelular", FILTER_SANITIZE_NUMBER_INT);
$tipo = filter_input(INPUT_POST, "tipo", FILTER_SANITIZE_STRING);
$userativo = "S";
$situacao = "0";
$tabela = "visitante";

$pdo = conectar("guarda");
$pdo2 = conectar("membros");

$sistema = base64_encode('SERVIÇOS COM SOC');
$obs = "Cadastrou Visitante: " . $nomecompleto;
$data = date("d/m/Y");
$data2 = date("Y-m-d");
$hora = date("H:i:s");

$consulta = $pdo->prepare("SELECT visitante.identidade, visitante.nomecompleto FROM visitante WHERE identidade = :identidade");
$consulta->bindParam(":identidade", $identidade, PDO::PARAM_STR);
$consulta->execute();
while ($reg = $consulta->fetch(PDO::FETCH_ASSOC)) {
  $consulta_identidade = $reg["identidade"];
  $consulta_nomecompleto = $reg["nomecompleto"];
}

$gravddos = $pdo->prepare("INSERT INTO $tabela(identidade, cpf, nomecompleto, datanascimento, datanascimento2, celular, tipo, userativo, situacao)"
  . "VALUES (:identidade, :cpf, :nomecompleto, :datanascimento, :datanascimento2, :celular, :tipo, :userativo, :situacao)");
$gravddos->bindParam(":identidade", $identidade, PDO::PARAM_STR);
$gravddos->bindParam(":cpf", $cpf, PDO::PARAM_STR);
$gravddos->bindParam(":nomecompleto", $nomecompleto, PDO::PARAM_STR);
$gravddos->bindParam(":datanascimento", $datanascimento, PDO::PARAM_STR);
$gravddos->bindParam(":datanascimento2", $datanascimento2, PDO::PARAM_STR);
$gravddos->bindParam(":celular", $celular, PDO::PARAM_STR);
$gravddos->bindParam(":tipo", $tipo, PDO::PARAM_STR);
$gravddos->bindParam(":userativo", $userativo, PDO::PARAM_STR);
$gravddos->bindParam(":situacao", $situacao, PDO::PARAM_INT);

if (strtotime($datanascimento2) >= strtotime($data2)) {
  $msgerro = base64_encode('Data de nascimento inválida!');
  header('Location: hospedes_ht.php?token=' . $msgerro);
  exit();
} else if ($identidade == $consulta_identidade and $nomecompleto == $consulta_nomecompleto) {
  $msgerro = base64_encode('Visitante já cadastrado!');
  header('Location: hospedes_ht.php?token=' . $msgerro);
  exit();
} else {
  $executa = $gravddos->execute();
  $executa2 = gerar_log_usuario($sistema, $obs);
  $msgsuccess = base64_encode('Visitante cadastro realizado com sucesso!');
  header('Location: hospedes_ht.php?token2=' . $msgsuccess);
  exit();
}
