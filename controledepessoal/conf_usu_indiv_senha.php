<?php
require "../recursos/models/versession.php";
require "../recursos/models/conexao.php";

if (!isset($_POST['action'])) {
  header('Location: index.php');
  exit();
} 
$pdo = conectar("membros");

$consulta = $pdo->prepare("SELECT nomecompleto, hashsenha FROM usuarios WHERE id = :id");
$consulta->bindParam(":id", $_SESSION['auth_data']['id'], PDO::PARAM_INT);
$consulta->execute();
$reg = $consulta->fetch(PDO::FETCH_ASSOC);
$nome = $reg['nomecompleto'];

if(!password_verify(base64_encode($_POST['senha-atual']), $reg['hashsenha'])) {
  die($_POST['senha-atual']);
  $msgerro = base64_encode('Não foi possível atualizar sua senha! Motivo: senha atual inválida.');
  header("Location: cad_usu_indiv_senha.php?tkusr=" . base64_encode($_SESSION['auth_data']['id']) . "&token=" . $msgerro);
  exit();
}

try {
    $senha_atual = base64_encode($_POST['senha-nova']);
    $newpass = password_hash($senha_atual, PASSWORD_BCRYPT);
    $query = "UPDATE usuarios SET hashsenha = '".$newpass."' WHERE id = ".base64_decode($_GET['tkusr']);
    $stmte = $pdo->prepare($query);
  $executa = $stmte->execute();
  if(!$executa) {
    $msgerro = base64_encode('Erro na base de dados!');
    header("Location: cad_usu_indiv_senha.php?tkusr=" . base64_encode($_SESSION['auth_data']['id']) . "&token=" . $msgerro);
    exit();
  }
  
  
  if ($executa) {
    $_SESSION['auth_data']['str'] = 'FORTE';
    $obs = "Dados confidenciais atualizados referente ao usuário ID" . $idusuario . " " . $nome;
    gerar_log_usuario("Controle de Pessoal", $obs);

  } else {
    $msgerro = base64_encode('Erro na base de dados!');
    header("Location: cad_usu_indiv_senha.php?tkusr=" . base64_encode($_SESSION['auth_data']['id']) . "&token=" . $msgerro);
    exit();
  }
  
} catch (PDOException $e) {
  $msgerro = $e->getMessage();
  exit();
}
$msgsuccess = base64_encode('Senha alterada com sucesso!');

// atualiza a data da ultima atualização
$sql = 'UPDATE usuarios SET ult_troca_senha = "'. date('Y-m-d') .'" WHERE id = '.$_SESSION['auth_data']['id'];
$stmt = $pdo->prepare($sql);
$stmt->execute();

// muda também na seção atual essa atualização:
$_SESSION['auth_data']['ult_troca_senha'] = date('Y-m-d');

header("Location: cad_usu_indiv_senha.php?tkusr=" . base64_encode($_SESSION['auth_data']['id']) . "&token2=" . $msgsuccess);

exit();
