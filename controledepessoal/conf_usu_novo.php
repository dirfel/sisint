<?php
// modelo para criação de novo usuário
require "../recursos/models/versession.php";
require "../recursos/models/conexao.php";

if (!isset($_POST['btn_novo_cadastro'])) {
  header('Location: index.php');
  exit();
}
if ($_SESSION['nivel_plano_chamada'] == "Sem Acesso") {
    $msgerro = base64_encode('Usuário não possui acesso!');
    header('Location: ../sistemas/index.php?token=' . $msgerro);
    exit();
}
$pdo = conectar("membros");

$nomecompleto = filter_input(INPUT_POST, "nome", FILTER_SANITIZE_STRING);
$nomecompleto = mb_strtoupper($nomecompleto, 'UTF-8');
$nomeguerra = filter_input(INPUT_POST, "guerra", FILTER_SANITIZE_STRING);
$nomeguerra = mb_strtoupper($nomeguerra, 'UTF-8');
$idpgrad = filter_input(INPUT_POST, "pgrad", FILTER_SANITIZE_NUMBER_INT);
$endereco = filter_input(INPUT_POST, "endereco", FILTER_SANITIZE_STRING);
$bairro = filter_input(INPUT_POST, "bairro", FILTER_SANITIZE_NUMBER_INT);
$cidade = filter_input(INPUT_POST, "cidade", FILTER_SANITIZE_STRING);
$estado = filter_input(INPUT_POST, "estado", FILTER_SANITIZE_STRING);
$idsubunidade = filter_input(INPUT_POST, "subunidade", FILTER_SANITIZE_NUMBER_INT);
$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
$fixo = filter_input(INPUT_POST, "fonefixo", FILTER_SANITIZE_STRING);
$celular = filter_input(INPUT_POST, "fonecelular", FILTER_SANITIZE_STRING);
$identidade = filter_input(INPUT_POST, "identidade", FILTER_SANITIZE_NUMBER_INT);
$cpf = filter_input(INPUT_POST, "cpf", FILTER_SANITIZE_STRING);
$datanascimento = filter_input(INPUT_POST, "datanascimento", FILTER_SANITIZE_STRING);
$datanascimento2 = date_converter($datanascimento);
// 
$acessorancho = filter_input(INPUT_POST, "acessorancho", FILTER_SANITIZE_STRING);
if (!$acessorancho) {
  $acessorancho = 'N';
}
$contarancho = filter_input(INPUT_POST, "contarancho", FILTER_SANITIZE_NUMBER_INT);
// 
$acessoguarda = filter_input(INPUT_POST, "acessoguarda", FILTER_SANITIZE_STRING);
if (!$acessoguarda) {
  $acessoguarda = 'N';
}
$contaguarda = filter_input(INPUT_POST, "contaguarda", FILTER_SANITIZE_NUMBER_INT);
// 
$acessohd = filter_input(INPUT_POST, "acessohd", FILTER_SANITIZE_STRING);
if (!$acessohd) {
  $acessohd = 'N';
}
$contahd = filter_input(INPUT_POST, "contahd", FILTER_SANITIZE_NUMBER_INT);
// 
$acessopchamada = filter_input(INPUT_POST, "acessopchamada", FILTER_SANITIZE_STRING);
if (!$acessopchamada) {
  $acessopchamada = 'N';
}
$contapchamada = filter_input(INPUT_POST, "contapchamada", FILTER_SANITIZE_NUMBER_INT);
// 
$acessosistcomsoc = filter_input(INPUT_POST, "acessosistcomsoc", FILTER_SANITIZE_STRING);
if (!$acessosistcomsoc) {
  $acessosistcomsoc = 'N';
}
// 
$acessoservico = "N";
$contaservico = "1";
$userativo = "S";

$tabela = "usuarios";
$idtcripto = base64_encode($identidade);
$cpfcripto = base64_encode(str_replace(array("-", "."), "", $cpf));


$gravddos = $pdo->prepare("INSERT INTO $tabela(identidade, cpf, hashsenha, idsubunidade, idpgrad, nomeguerra, acessorancho, contarancho, acessoguarda, contaguarda, "
    . "acessoservico, contaservico, acessopchamada, contapchamada, acessosistcomsoc, userativo, nomecompleto, endereco, bairro, cidade, estado, celular, fixo, email, "
    . "datanascimento, acessohd, contahd, datanascimento2, nivelacessocautela) "
    . "VALUES (:identidade, :cpf, :hashsenha, :idsubunidade, :idpgrad, :nomeguerra, :acessorancho, :contarancho, :acessoguarda, :contaguarda, :acessoservico, "
    . ":contaservico, :acessopchamada, :contapchamada, :acessosistcomsoc, :userativo, :nomecompleto, :endereco, :bairro, :cidade, :estado, :celular, :fixo, :email, :datanascimento, "
    . ":acessohd, :contahd, :datanascimento2, :nivelacessocautela) ");
$gravddos->bindParam(":identidade", $idtcripto, PDO::PARAM_STR);
$gravddos->bindParam(":cpf", $cpfcripto, PDO::PARAM_STR);
$gravddos->bindParam(":hashsenha", password_hash($cpfcripto, PASSWORD_BCRYPT), PDO::PARAM_STR);
$gravddos->bindParam(":idsubunidade", $idsubunidade, PDO::PARAM_INT);
$gravddos->bindParam(":idpgrad", $idpgrad, PDO::PARAM_INT);
$gravddos->bindParam(":nomeguerra", $nomeguerra, PDO::PARAM_STR);
$gravddos->bindParam(":acessorancho", $acessorancho, PDO::PARAM_STR);
$gravddos->bindParam(":contarancho", $contarancho, PDO::PARAM_INT);
$gravddos->bindParam(":acessoguarda", $acessoguarda, PDO::PARAM_STR);
$gravddos->bindParam(":contaguarda", $contaguarda, PDO::PARAM_INT);
$gravddos->bindParam(":acessohd", $acessohd, PDO::PARAM_STR);
$gravddos->bindParam(":contahd", $contahd, PDO::PARAM_INT);
$gravddos->bindParam(":acessopchamada", $acessopchamada, PDO::PARAM_STR);
$gravddos->bindParam(":acessosistcomsoc", $acessosistcomsoc, PDO::PARAM_STR);
$gravddos->bindParam(":contapchamada", $contapchamada, PDO::PARAM_INT);
$gravddos->bindParam(":acessoservico", $acessoservico, PDO::PARAM_STR);
$gravddos->bindParam(":contaservico", $contaservico, PDO::PARAM_STR);
$gravddos->bindParam(":userativo", $userativo, PDO::PARAM_STR);
$gravddos->bindParam(":nomecompleto", $nomecompleto, PDO::PARAM_STR);
$gravddos->bindParam(":endereco", $endereco, PDO::PARAM_STR);
$gravddos->bindParam(":bairro", $bairro, PDO::PARAM_STR);
$gravddos->bindParam(":cidade", $cidade, PDO::PARAM_STR);
$gravddos->bindParam(":estado", $estado, PDO::PARAM_STR);
$gravddos->bindParam(":celular", $celular, PDO::PARAM_STR);
$gravddos->bindParam(":fixo", $fixo, PDO::PARAM_STR);
$gravddos->bindParam(":email", $email, PDO::PARAM_STR);
$gravddos->bindParam(":datanascimento", $datanascimento, PDO::PARAM_STR);
$gravddos->bindParam(":datanascimento2", $datanascimento2, PDO::PARAM_STR);
$gravddos->bindParam(":nivelacessocautela", $_POST['nivelacessocautela'], PDO::PARAM_STR);
$executa = $gravddos->execute();
if ($executa) {
  $sistema = base64_encode("CONTROLE DE PESSOAL");
  $obs = "Novo usuário criado nome: " . $nomecompleto;
  $executa2 = gerar_log_usuario($sistema, $obs);
} else {
  $msgerro = base64_encode('Erro na base de dados!');
  header("Location: cad_usu.php?token=" . $msgerro);
  exit();
}

$msgsuccess = base64_encode('Usuário criado com sucesso!');
header("Location: cad_usu.php?token2=" . $msgsuccess);
exit();
