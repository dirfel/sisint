<?php
require "../recursos/models/versession.php";
require "../recursos/models/conexao.php";

if (!isset($_POST['btn_atualiza_cadastro'])) {
  header('Location: index.php');
  exit();
}

$pdo = conectar("membros");

$idusuario = base64_decode(filter_input(INPUT_GET, "tkusr", FILTER_SANITIZE_SPECIAL_CHARS));
$nome = filter_input(INPUT_POST, "nome", FILTER_SANITIZE_STRING);
$nome = mb_strtoupper($nome, 'UTF-8');
$guerra = filter_input(INPUT_POST, "guerra", FILTER_SANITIZE_STRING);
$guerra = mb_strtoupper($guerra, 'UTF-8');
$pgrad = filter_input(INPUT_POST, "pgrad", FILTER_SANITIZE_STRING);
$endereco = filter_input(INPUT_POST, "endereco", FILTER_SANITIZE_STRING);
$bairro = filter_input(INPUT_POST, "bairro", FILTER_SANITIZE_STRING);
$cidade = filter_input(INPUT_POST, "cidade", FILTER_SANITIZE_STRING);
$estado = filter_input(INPUT_POST, "estado", FILTER_SANITIZE_STRING);
$subunidade = filter_input(INPUT_POST, "subunidade", FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
$fonefixo = filter_input(INPUT_POST, "fonefixo", FILTER_SANITIZE_STRING);
$fonecelular = filter_input(INPUT_POST, "fonecelular", FILTER_SANITIZE_STRING);
$identidade = filter_input(INPUT_POST, "identidade", FILTER_SANITIZE_NUMBER_INT);
$cpf = filter_input(INPUT_POST, "cpf", FILTER_SANITIZE_STRING);
$datanascimento = filter_input(INPUT_POST, "datanascimento", FILTER_SANITIZE_STRING);
$datanascimento2 = date_converter($datanascimento);
$acessorancho = filter_input(INPUT_POST, "acessorancho", FILTER_SANITIZE_STRING);
$nivelacessocautela = filter_input(INPUT_POST, "nivelacessocautela", FILTER_SANITIZE_STRING);

if (!$acessorancho) {
  $acessorancho = 'N';
}
$contarancho = filter_input(INPUT_POST, "contarancho", FILTER_SANITIZE_NUMBER_INT);
$acessoguarda = filter_input(INPUT_POST, "acessoguarda", FILTER_SANITIZE_STRING);
if (!$acessoguarda) {
  $acessoguarda = 'N';
}
$contaguarda = filter_input(INPUT_POST, "contaguarda", FILTER_SANITIZE_NUMBER_INT);
$acessohd = filter_input(INPUT_POST, "acessohd", FILTER_SANITIZE_STRING);
if (!$acessohd) {
  $acessohd = 'N';
}
$contahd = filter_input(INPUT_POST, "contahd", FILTER_SANITIZE_NUMBER_INT);
$acessopchamada = filter_input(INPUT_POST, "acessopchamada", FILTER_SANITIZE_STRING);
if (!$acessopchamada) {
  $acessopchamada = 'N';
}
$acessosistcomsoc = filter_input(INPUT_POST, "acessosistcomsoc", FILTER_SANITIZE_STRING);
if (!$acessosistcomsoc) {
  $acessosistcomsoc = 'N';
}
if (!$nivelacessocautela) {
  $nivelacessocautela = '0';
}
$contapchamada = filter_input(INPUT_POST, "contapchamada", FILTER_SANITIZE_NUMBER_INT);
$acessoservico = "N";
$contaservico = "1";
$userativo = filter_input(INPUT_POST, "userativo", FILTER_SANITIZE_STRING);
$resertarsenha = filter_input(INPUT_POST, "resertarsenha", FILTER_SANITIZE_STRING);

$tabela = "usuarios";
$identidade_encode = base64_encode($identidade);
$cpf_encode = base64_encode(str_replace(".", "", str_replace("-", "", $cpf))); // elimina - e . do cpf

try {
  $stmte = $pdo->prepare("UPDATE $tabela SET idsubunidade = :subunidade, "
    . "nivelacessocautela = :nivelacessocautela, nomeguerra = :guerra, idpgrad = :pgrad, nomecompleto = :nome, "
    . "endereco = :endereco, bairro = :bairro, cidade = :cidade, "
    . "estado = :estado, celular = :fonecelular, fixo = :fonefixo, email = :email, datanascimento = :datanascimento, "
    . "datanascimento2 = :datanascimento2, identidade = :identidade, cpf = :cpf, "
    . "acessorancho = :acessorancho, contarancho = :contarancho, acessoguarda = :acessoguarda, contaguarda = :contaguarda, "
    . "acessohd = :acessohd, contahd = :contahd, acessosistcomsoc = :acessosistcomsoc, "
    . "acessopchamada = :acessopchamada, contapchamada = :contapchamada, "
    . "acessoservico = :acessoservico, contaservico = :contaservico, userativo = :userativo WHERE id = :id");
  $stmte->bindParam(":id", $idusuario, PDO::PARAM_INT);
  $stmte->bindParam(":subunidade", $subunidade, PDO::PARAM_INT);
  $stmte->bindParam(":pgrad", $pgrad, PDO::PARAM_INT);
  $stmte->bindParam(":guerra", $guerra, PDO::PARAM_STR);
  $stmte->bindParam(":nome", $nome, PDO::PARAM_STR);
  $stmte->bindParam(":endereco", $endereco, PDO::PARAM_STR);
  $stmte->bindParam(":bairro", $bairro, PDO::PARAM_STR);
  $stmte->bindParam(":cidade", $cidade, PDO::PARAM_STR);
  $stmte->bindParam(":estado", $estado, PDO::PARAM_STR);
  $stmte->bindParam(":fonecelular", $fonecelular, PDO::PARAM_STR);
  $stmte->bindParam(":fonefixo", $fonefixo, PDO::PARAM_STR);
  $stmte->bindParam(":email", $email, PDO::PARAM_STR);
  $stmte->bindParam(":datanascimento", $datanascimento, PDO::PARAM_STR);
  $stmte->bindParam(":datanascimento2", $datanascimento2, PDO::PARAM_STR);
  $stmte->bindParam(":identidade", $identidade_encode, PDO::PARAM_STR);
  $stmte->bindParam(":cpf", $cpf_encode, PDO::PARAM_STR);
  $stmte->bindParam(":acessorancho", $acessorancho, PDO::PARAM_STR);
  $stmte->bindParam(":contarancho", $contarancho, PDO::PARAM_INT);
  $stmte->bindParam(":acessosistcomsoc", $acessosistcomsoc, PDO::PARAM_STR);
  $stmte->bindParam(":acessoguarda", $acessoguarda, PDO::PARAM_STR);
  $stmte->bindParam(":contaguarda", $contaguarda, PDO::PARAM_INT);
  $stmte->bindParam(":acessohd", $acessohd, PDO::PARAM_STR);
  $stmte->bindParam(":contahd", $contahd, PDO::PARAM_INT);
  $stmte->bindParam(":acessopchamada", $acessopchamada, PDO::PARAM_STR);
  $stmte->bindParam(":contapchamada", $contapchamada, PDO::PARAM_INT);
  $stmte->bindParam(":acessoservico", $acessoservico, PDO::PARAM_STR);
  $stmte->bindParam(":contaservico", $contaservico, PDO::PARAM_INT);
  $stmte->bindParam(":nivelacessocautela", $nivelacessocautela, PDO::PARAM_STR);
  $stmte->bindParam(":userativo", $userativo, PDO::PARAM_STR);
  $executa = $stmte->execute();
  if ($executa) {
    if ($resertarsenha == 'S') {
      $stmte2 = $pdo->prepare("UPDATE $tabela SET hashsenha = :hashsenha WHERE id = :id");
      $stmte2->bindParam(":hashsenha", password_hash($cpf_encode, PASSWORD_BCRYPT), PDO::PARAM_STR);
      $stmte2->bindParam(":id", $idusuario, PDO::PARAM_INT);
      $executa3 = $stmte2->execute();
    }
    $sistema = base64_encode("CONTROLE DE PESSOAL");
    $obs = "Dados atualizados referente ao usuário ID" . $idusuario . " " . $nome;
    $executa2 = gerar_log_usuario($sistema, $obs);
  } else {
    $msgerro = base64_encode('Erro na base de dados!');
    header("Location: cad_usu.php?token=" . $msgerro);
    exit();
  }
} catch (PDOException $e) {
  echo $e->getMessage();
}
$msgsuccess = base64_encode('Dados alterado com sucesso!');
header("Location: cad_usu.php?token2=" . $msgsuccess);
exit();
