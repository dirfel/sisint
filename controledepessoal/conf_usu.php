<?php
date_default_timezone_set("America/Cuiaba");
require "../recursos/models/versession.php";
require "../recursos/models/conexao.php";

if (!isset($_POST['btn_novo_cadastro'])) {
  header('Location: index.php');
  exit();
}

$pdo = conectar("membros");

$nome = filter_input(INPUT_POST, "nome", FILTER_SANITIZE_STRING);
$nome =  mb_strtoupper($nome, 'UTF-8');
$guerra = filter_input(INPUT_POST, "guerra", FILTER_SANITIZE_STRING);
$guerra =  mb_strtoupper($guerra, 'UTF-8');
$pgrad = filter_input(INPUT_POST, "pgrad", FILTER_SANITIZE_NUMBER_INT);
$endereco = filter_input(INPUT_POST, "endereco", FILTER_SANITIZE_STRING);
$bairro = filter_input(INPUT_POST, "bairro", FILTER_SANITIZE_NUMBER_INT);
$cidade = filter_input(INPUT_POST, "cidade", FILTER_SANITIZE_STRING);
$estado = filter_input(INPUT_POST, "estado", FILTER_SANITIZE_STRING);
$subunidade = filter_input(INPUT_POST, "subunidade", FILTER_SANITIZE_NUMBER_INT);
$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
$fonefixo = filter_input(INPUT_POST, "fonefixo", FILTER_SANITIZE_STRING);
$fonecelular = filter_input(INPUT_POST, "fonecelular", FILTER_SANITIZE_STRING);
$identidade = filter_input(INPUT_POST, "identidade", FILTER_SANITIZE_NUMBER_INT);
$cpf = filter_input(INPUT_POST, "cpf", FILTER_SANITIZE_STRING);
$datanascimento = filter_input(INPUT_POST, "datanascimento", FILTER_SANITIZE_STRING);
$datanascimento2 = date_converter($datanascimento);
$acessorancho = "S";
$contarancho = "1";
$acessopchamada = "N";
$contapchamada = "1";
$acessoguarda = "S";
$contaguarda = "1";
$acessohd = "N";
$contahd = "1";
$acessoservico = "N";
$contaservico = "1";
$userativo = "S";
$idtcripto = base64_encode($identidade);
$cpfcripto = base64_encode(str_replace(array("-", "."), "", $cpf)); // elimina - e . do cpf

$tabela = "usuarios";
try {
  $gravddos = $pdo->prepare("INSERT INTO $tabela (identidade, cpf, senha, idsubunidade, "
    . "idpgrad, nomeguerra, acessorancho, contarancho, acessoguarda, contaguarda, "
    . "acessopchamada, contapchamada, acessoservico, contaservico, userativo, nomecompleto, endereco, bairro, cidade, "
    . "estado, celular, fixo, email, datanascimento, acessohd, contahd, datanascimento2) "
    . "VALUES (:identidade, :cpf, :senha, :idsubunidade, "
    . ":idpgrad, :nomeguerra, :acessorancho, :contarancho, :acessoguarda, :contaguarda, "
    . ":acessopchamada, :contapchamada, :acessoservico, :contaservico, :userativo, :nomecompleto, :endereco, :bairro, :cidade, "
    . ":estado, :celular, :fixo, :email, :datanascimento, :acessohd, :contahd, :datanascimento2)");
  $gravddos->bindParam(":identidade", $idtcripto, PDO::PARAM_STR);
  $gravddos->bindParam(":cpf", $cpfcripto, PDO::PARAM_STR);
  $gravddos->bindParam(":senha", $cpfcripto, PDO::PARAM_STR); // REGISTRA A SENHA IGUAL O CPF
  $gravddos->bindParam(":idsubunidade", $subunidade, PDO::PARAM_INT);
  $gravddos->bindParam(":idpgrad", $pgrad, PDO::PARAM_INT);
  $gravddos->bindParam(":nomeguerra", $guerra, PDO::PARAM_STR);
  $gravddos->bindParam(":acessorancho", $acessorancho, PDO::PARAM_STR);
  $gravddos->bindParam(":contarancho", $contarancho, PDO::PARAM_STR);
  $gravddos->bindParam(":acessoguarda", $acessoguarda, PDO::PARAM_STR);
  $gravddos->bindParam(":contaguarda", $contaguarda, PDO::PARAM_STR);
  $gravddos->bindParam(":acessopchamada", $acessopchamada, PDO::PARAM_STR);
  $gravddos->bindParam(":contapchamada", $contapchamada, PDO::PARAM_STR);
  $gravddos->bindParam(":acessoservico", $acessoservico, PDO::PARAM_STR);
  $gravddos->bindParam(":contaservico", $contaservico, PDO::PARAM_STR);
  $gravddos->bindParam(":userativo", $userativo, PDO::PARAM_STR);
  $gravddos->bindParam(":nomecompleto", $nome, PDO::PARAM_STR);
  $gravddos->bindParam(":endereco", $endereco, PDO::PARAM_STR);
  $gravddos->bindParam(":bairro", $bairro, PDO::PARAM_STR);
  $gravddos->bindParam(":cidade", $cidade, PDO::PARAM_STR);
  $gravddos->bindParam(":estado", $estado, PDO::PARAM_STR);
  $gravddos->bindParam(":celular", $fonecelular, PDO::PARAM_STR);
  $gravddos->bindParam(":fixo", $fonefixo, PDO::PARAM_STR);
  $gravddos->bindParam(":email", $email, PDO::PARAM_STR);
  $gravddos->bindParam(":datanascimento", $datanascimento, PDO::PARAM_STR);
  $gravddos->bindParam(":acessohd", $acessohd, PDO::PARAM_STR);
  $gravddos->bindParam(":contahd", $contahd, PDO::PARAM_STR);
  $gravddos->bindParam(":datanascimento2", $datanascimento2, PDO::PARAM_STR);
  $executa = $gravddos->execute();
  if ($executa) {
    $sistema = base64_encode("CONTROLE DE PESSOAL");
    $obs = "Novo usuário criado nome: " . $nome;
    $executa2 = gerar_log_usuario($sistema, $obs);
  } else {
    $msgerro = base64_encode('Erro ao inserir dados!');
    header("Location: cad_usu_supervisor.php?token=" . $msgerro);
    exit();
  }
} catch (PDOException $e) {
  echo $e->getMessage();
}
$msgsuccess = base64_encode('Usuário criado com sucesso!');
header("Location: cad_usu_supervisor.php?token2=" . $msgsuccess);
exit();
