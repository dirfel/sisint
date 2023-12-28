<?php


/**
 * Essa função cria log somente quando a variável $isTest for true
 */
function debugLog($stringLog) {
    if($isTest) {
      echo '<script>console.log("'.$stringLog.'")</script>';
    }
  }
  
/**
 * Essa função apoia auditorias do sistema ao duplicar lançamentos diversos.
 * TODO: função precisa ser otimizada para não gerar logs demasiadamente pesados
 */
function gerar_log_usuario($sistema, $obs) {
    $sistema = base64_encode($sistema);
    $data_agora = date("d/m/Y");
    $hora_agora = date("H:i:s");
    $quemgrava = ImprimeConsultaMilitar2($_SESSION['auth_data']);
    $gravddos = conectar('membros')->prepare("INSERT INTO relatorios (data, hora, ip, responsavel, sistema, obs) "
            . "VALUES (:data, :hora, :ip, :responsavel, :sistema, :obs)");
    $gravddos->bindParam(":data", $data_agora, PDO::PARAM_STR);
    $gravddos->bindParam(":hora", $hora_agora, PDO::PARAM_STR);
    $gravddos->bindParam(":ip", $_SESSION['user_ip'], PDO::PARAM_STR);
    $gravddos->bindParam(":responsavel", $quemgrava, PDO::PARAM_STR);
    $gravddos->bindParam(":sistema", $sistema, PDO::PARAM_STR);
    $gravddos->bindParam(":obs", $obs, PDO::PARAM_STR);
    return $gravddos->execute();
} 

/**
 * função criada para criar log quando o usuario tentar fazer login.
 * Caso o usuário tenha sucesso no login, $sucesso será "S", caso contrário será "N".
 * TODO: Preciso renomear a coluna sistema para o nome sucesso.
 */
function gerar_log_login($id_usuario, $sucesso, $ip) {
    $data = date("d/m/Y");
    $hora = date("H:i:s");
    $stmtez = conectar('membros')->prepare("INSERT INTO logins (idmembro, data, hora, sistema, ip) "
        . "VALUES (:idmembro, :data, :hora, :sistema, :ip)");
    $stmtez->bindParam(":idmembro", $id_usuario, PDO::PARAM_INT);
    $stmtez->bindParam(":data", $data, PDO::PARAM_STR);
    $stmtez->bindParam(":hora", $hora, PDO::PARAM_STR);
    $stmtez->bindParam(":sistema", $sucesso, PDO::PARAM_STR);
    $stmtez->bindParam(":ip", $ip, PDO::PARAM_STR);
    $executa = $stmtez->execute();
}

/**
 * Retorna o número de tentativas de login frustradas, 
 * isso será usado para bloquear o usuário quando o número 
 * de tentativas de login for superior a 10.
 */
function checar_tentativas_login_frustradas($id_usuario) {
  $data_hoje = date('d/m/Y');
  $stmtez = conectar('membros')->prepare("SELECT * FROM logins WHERE idmembro = :idmembro and data = :data_hoje ORDER BY id DESC LIMIT 10");
  $stmtez->bindParam(":idmembro", $id_usuario, PDO::PARAM_INT);
  $stmtez->bindParam(":data_hoje", $data_hoje, PDO::PARAM_STR);
  $stmtez->execute();
  $consulta = $stmtez->fetchAll(PDO::FETCH_ASSOC);
  $falses = 0;
  foreach($consulta as $login_log) {
    if($login_log['sistema'] == 'N') {
      $falses++;
    }
}
  return $falses;
}

?>