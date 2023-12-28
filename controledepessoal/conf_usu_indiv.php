<?php
require "../recursos/models/versession.php";
require "../recursos/models/conexao.php";

if (!isset($_POST['action'])) {
  header('Location: index.php');
  exit();
}
// if ($_SESSION['nivel_plano_chamada'] == "Sem Acesso") {
//     $msgerro = base64_encode('Usuário não possui acesso!');
//     header('Location: ../sistemas/index.php?token=' . $msgerro);
//     exit();
// }
$pdo = conectar("membros");
$idusuario = base64_decode(filter_input(INPUT_GET, "tkusr", FILTER_SANITIZE_SPECIAL_CHARS));

if ($_SESSION['auth_data']['contahd'] < "3" && $_SESSION['auth_data']['id'] != $idusuario) {
    header('Location: index.php?token2='.base64_encode('Você não possui permissão para isso'));
    exit();
  }

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
$cpf = base64_encode(str_replace(".", "", str_replace("-", "", $cpf))); // elimina - e . do cpf
$datanascimento = filter_input(INPUT_POST, "datanascimento", FILTER_SANITIZE_STRING);
$datanascimento2 = date_converter($datanascimento);
$tabela = "usuarios";

try {
  if ($_SESSION['auth_data']['idpgrad'] == 16) {
    $stmte = $pdo->prepare("UPDATE $tabela SET "
      . "endereco = :endereco, bairro = :bairro, cidade = :cidade, "
      . "estado = :estado, celular = :fonecelular, fixo = :fonefixo, "
      . "email = :email, cpf = :cpf WHERE id = :id");
    $stmte->bindParam(":id", $idusuario, PDO::PARAM_INT);
    $stmte->bindParam(":endereco", $endereco, PDO::PARAM_STR);
    $stmte->bindParam(":bairro", $bairro, PDO::PARAM_STR);
    $stmte->bindParam(":cidade", $cidade, PDO::PARAM_STR);
    $stmte->bindParam(":estado", $estado, PDO::PARAM_STR);
    $stmte->bindParam(":fonecelular", $fonecelular, PDO::PARAM_STR);
    $stmte->bindParam(":fonefixo", $fonefixo, PDO::PARAM_STR);
    $stmte->bindParam(":email", $email, PDO::PARAM_STR);
    $stmte->bindParam(":cpf", $cpf, PDO::PARAM_STR);
  } else if ($_SESSION['auth_data']['idpgrad'] >= 14) {
    $stmte = $pdo->prepare("UPDATE $tabela SET "
      . "idsubunidade = :subunidade, datanascimento = :datanascimento, datanascimento2 = :datanascimento2, "
      . "endereco = :endereco, bairro = :bairro, cidade = :cidade, "
      . "estado = :estado, celular = :fonecelular, fixo = :fonefixo, "
      . "email = :email, cpf = :cpf WHERE id = :id");
    $stmte->bindParam(":subunidade", $subunidade, PDO::PARAM_INT);
    $stmte->bindParam(":datanascimento", $datanascimento, PDO::PARAM_STR);
    $stmte->bindParam(":datanascimento2", $datanascimento2, PDO::PARAM_STR);
    $stmte->bindParam(":id", $idusuario, PDO::PARAM_INT);
    $stmte->bindParam(":endereco", $endereco, PDO::PARAM_STR);
    $stmte->bindParam(":bairro", $bairro, PDO::PARAM_STR);
    $stmte->bindParam(":cidade", $cidade, PDO::PARAM_STR);
    $stmte->bindParam(":estado", $estado, PDO::PARAM_STR);
    $stmte->bindParam(":fonecelular", $fonecelular, PDO::PARAM_STR);
    $stmte->bindParam(":fonefixo", $fonefixo, PDO::PARAM_STR);
    $stmte->bindParam(":email", $email, PDO::PARAM_STR);
    $stmte->bindParam(":cpf", $cpf, PDO::PARAM_STR);
  } else {
    $stmte = $pdo->prepare("UPDATE $tabela SET "
      . "idsubunidade = :subunidade, "
      . "nomeguerra = :guerra, idpgrad = :pgrad, nomecompleto = :nome, "
      . "endereco = :endereco, bairro = :bairro, cidade = :cidade, "
      . "estado = :estado, celular = :fonecelular, fixo = :fonefixo, "
      . "email = :email, cpf = :cpf, datanascimento = :datanascimento, "
      . "datanascimento2 = :datanascimento2 WHERE id = :id");
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
    $stmte->bindParam(":cpf", $cpf, PDO::PARAM_STR);
    $stmte->bindParam(":datanascimento", $datanascimento, PDO::PARAM_STR);
    $stmte->bindParam(":datanascimento2", $datanascimento2, PDO::PARAM_STR);
  }

  $executa = $stmte->execute();
  if ($executa) {

    // atualiza a data da ultima atualização
    $sql = 'UPDATE usuarios SET ult_atlz_dados = "'. date('Y-m-d') .'" WHERE id = '.$_SESSION['auth_data']['id'];
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // muda também na seção atual essa atualização:
    $_SESSION['auth_data']['ult_atlz_dados'] = date('Y-m-d');

    echo 'Dados inseridos com sucesso';

    // cria um log da ação
    $sistema = base64_encode("CONTROLE DE PESSOAL");
    $obs = "Dados atualizados referente ao usuário ID" . $idusuario . " " . $nome;
    $executa2 = gerar_log_usuario($sistema, $obs);

  } else {
    $msgerro = base64_encode('Erro na base de dados!');
    header("Location: cad_usu_indiv.php?tkusr=" . base64_encode($idusuario) . "&token=" . $msgerro);
    exit();
  }
} catch (PDOException $e) {
    die("Erro inesperado");
//   echo $e->getMessage();

}

$msgsuccess = base64_encode('Dados alterado com sucesso!');
header("Location: cad_usu_indiv.php?tkusr=" . base64_encode($idusuario) . "&token2=" . $msgsuccess);
exit();
