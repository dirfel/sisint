<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

// 1. defino as variáveis que serão utilizadas
$id = base64_decode(filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING));
$identidade = filter_input(INPUT_POST, "identidade", FILTER_SANITIZE_NUMBER_INT);
$cpf = filter_input(INPUT_POST, "cpf", FILTER_SANITIZE_NUMBER_INT);
$cpf = removerSeparadoresCpf($cpf);
$idpgrad = filter_input(INPUT_POST, "idpgrad", FILTER_SANITIZE_NUMBER_INT);
$nomecompleto = filter_input(INPUT_POST, "nomecompleto", FILTER_SANITIZE_STRING);
$nomecompleto = mb_strtoupper($nomecompleto, 'UTF-8');
$tipo = filter_input(INPUT_POST, "tipo", FILTER_SANITIZE_STRING);
$pdo = conectar('guarda');
// PARA DEPURAÇÃO, EXCLUIR
// echo '<pre>';
// print_r($_POST);
// print_r($_GET);
// echo '</pre>';
// // die('');

// 2. obtenho a url que originou o acesso à essa requisição
$retorno = explode('?', $_SERVER['HTTP_REFERER'])[0] . '?id=' . $_GET['id'];

// 3. verifico se o usuário tem permissão para acessar essa página
if (!$_SESSION['nivel_plano_chamada'] == "Supervisor" && !$_SESSION['nivel_plano_chamada'] == "Administrador") {
  header('Location: '. $retorno .'&token2='. base64_encode('Usuário não possui acesso! Somente usuários: Supervisor e administrador!'));
  exit();
}

// 4. segunda verificação de segurança: previne acesso sem $_POST['action']
if (!isset($_POST['action'])) {
  header('Location: '. $retorno .'&token2='. base64_encode('Erro inesperado'));
  exit();
}

// 5. terceira verificação de segurança: verificar se o cpf é válido
if (!validaCpf($cpf)) {
    $msgerro = base64_encode('CPF inválido!');
    header('Location: '.$retorno.'&token=' . $msgerro);
    exit();
}

// 6. verifica se o novo CPF já está cadastrado na base de dados para outro usuario
$consulta = $pdo->prepare("SELECT id, cpf, nomecompleto FROM visitante WHERE cpf = :cpf AND (id <> :id)");
$consulta->bindParam(":id", $id, PDO::PARAM_INT);
$consulta->bindParam(":cpf", $cpf, PDO::PARAM_STR);
// print_r($reg);
// die('foi aqui');
$consulta->execute();
$reg = $consulta->fetchAll(PDO::FETCH_ASSOC);
if(count($reg) != 0) {
  $msgerro = base64_encode('CPF já cadastrado para outro visitante! ' . $reg[0]['nomecompleto'] );
  header('Location: '.$retorno .'&token=' . $msgerro);
  exit();
}

// 7. Se tudo der certo até aqui, executa a atualização no banco de dados
$gravddos = $pdo->prepare("UPDATE visitante SET identidade = :identidade, idpgrad = :idpgrad, cpf = :cpf, nomecompleto = :nomecompleto, tipo = :tipo WHERE id = :id");
$gravddos->bindParam(":id", $id, PDO::PARAM_INT);
$gravddos->bindParam(":identidade", $identidade, PDO::PARAM_STR);
$gravddos->bindParam(":idpgrad", $idpgrad, PDO::PARAM_INT);
$gravddos->bindParam(":cpf", $cpf, PDO::PARAM_STR);
$gravddos->bindParam(":nomecompleto", $nomecompleto, PDO::PARAM_STR);
$gravddos->bindParam(":tipo", $tipo, PDO::PARAM_STR);

$executa = $gravddos->execute();
if($executa) {
    gerar_log_usuario('Guarda', "Editou o Visitante: " . $nomecompleto);
    $msgsuccess = base64_encode('Visitante atualizado com sucesso!');
    header('Location: '.$retorno.'&token2=' . $msgsuccess);
    exit();
} else {
    $msgerror = base64_encode('Erro ao atualizar visitante!');
    header('Location: '.$retorno.'&token=' . $msgerror);
    exit();
}


?>