<?php



function conectar($database) {

//Aqui tentaremos conectar com o PDO através de uma instância de PDO
try {
  $pdo = new PDO(DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . $database, DB_USER, DB_PWD);

  if ($pdo) {
    return $pdo;
  } else {
    session_destroy();
    $msgerro = base64_encode('Erro na tentativa de acesso ao servidor!');
    header('Location: signin.php?token=' . $msgerro);
    exit();
  }
} catch (PDOException $exc) {
  debugLog($exc->getMessage());
  session_destroy();
  $msgerro = base64_encode('Houve algum erro na comunicação com o banco de dados, contate o administrador do sistema');
  header('Location: signin.php?token=' . $msgerro);
  exit();
}
}
?>