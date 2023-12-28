<?php
require "../recursos/models/versession.php";
require "../recursos/models/conexao.php";

// 1. obtenho a url que originou o acesso à essa página
$_SERVER['HTTP_REFERER'] = explode('?', $_SERVER['HTTP_REFERER'])[0];

// 2. primeira verificação de segurança: previne acesso sem $_POST['action']
if (!isset($_POST['action'])) {
  header('Location: '.$_SERVER['HTTP_REFERER'].'?token2='. base64_encode('Erro inesperado'));
  exit();
}

// 3. Sanitização de strings recebidas no formulário e definição de variáveis
$identidade = filter_input(INPUT_POST, "identidade", FILTER_SANITIZE_NUMBER_INT);
$cpf = filter_input(INPUT_POST, "cpf", FILTER_SANITIZE_NUMBER_INT);
$cpf = removerSeparadoresCpf($cpf);
$idpgrad = filter_input(INPUT_POST, "idpgrad", FILTER_SANITIZE_NUMBER_INT);
$nomecompleto = filter_input(INPUT_POST, "nomecompleto", FILTER_SANITIZE_STRING);
$nomecompleto = mb_strtoupper($nomecompleto, 'UTF-8');
$tipo = filter_input(INPUT_POST, "tipo", FILTER_SANITIZE_STRING);
$pdo = conectar("guarda");

// 4. segunda verificação de segurança: verificar se o cpf é válido
if (!validaCpf($cpf)) {
  $msgerro = base64_encode('CPF inválido!');
  header('Location: '.$_SERVER['HTTP_REFERER'].'?token=' . $msgerro);
  exit();
}

// 6. Se tudo der certo até aqui, executa a inserção no banco de dados
$gravddos = $pdo->prepare("INSERT INTO visitante(identidade, idpgrad, cpf, nomecompleto, tipo) VALUES (:identidade, :idpgrad, :cpf, :nomecompleto, :tipo)");
$gravddos->bindParam(":identidade", $identidade, PDO::PARAM_STR);
$gravddos->bindParam(":idpgrad", $idpgrad, PDO::PARAM_INT);
$gravddos->bindParam(":cpf", $cpf, PDO::PARAM_STR);
$gravddos->bindParam(":nomecompleto", $nomecompleto, PDO::PARAM_STR);
$gravddos->bindParam(":tipo", $tipo, PDO::PARAM_STR);
$executa = $gravddos->execute();

// 7. gera log se executado com sucesso e redireciona usuário
if($executa) {
  gerar_log_usuario('Guarda', "Cadastrou Visitante: " . $nomecompleto);
  $msgsuccess = base64_encode('Visitante cadastro realizado com sucesso!');
  header('Location: '.$_SERVER['HTTP_REFERER'].'?token2=' . $msgsuccess);
  exit();
} else {
  $msgerror = base64_encode('Erro ao cadastrar visitante!');
  header('Location: '.$_SERVER['HTTP_REFERER'].'?token=' . $msgerror);
  exit();
}

