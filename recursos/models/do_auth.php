<?php

// defino variaveis importantes
$pdo1 = conectar("membros");
$login = base64_encode($_POST["login"]);
$senha = base64_encode($_POST["senha"]);

function get_client_ip2() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}
$ip = get_client_ip2();
$_SESSION['user_ip'] = get_client_ip2();

// tentar realizar o login
$sql = "SELECT * FROM usuarios WHERE cpf = :login"; 
$stmt = $pdo1->prepare($sql);
$stmt->bindParam(':login', $login);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);


if (count($users) < 1) { // se não encontrar usuarios, significa que falhou no login
    gerar_log_login($login, "N", $ip);
    session_destroy();
    $msgerro = base64_encode('Usuário ou senha inválido!');
    header('Location: signin.php?token=' . $msgerro);
    exit();
} else if(checar_tentativas_login_frustradas($users[0]['id']) >= 10) { // se o usuario errou a senha 10 vezes, desativa a conta do usuario
    gerar_log_login($users[0]['id'], "N", $ip);
    session_destroy();
    $msgerro = base64_encode('Senha bloqueada! Contate o administrador ou volte novamente amanhã.');
    header('Location: signin.php?token=' . $msgerro);
    exit();
} else if (password_verify($senha, $users[0]['hashsenha']) == 0) { // se a hash não corresponder com a senha informada, significa que falhou no login
    gerar_log_login($users[0]['id'], "N", $ip);
    session_destroy();
    $msgerro = base64_encode('Usuário ou senha inválido!');
    header('Location: signin.php?token=' . $msgerro);
    exit();
} else if($users[0]['userativo'] != "S"){ // se o usuario não estiver ativo, significa que falhou no login
    gerar_log_login($users[0]['id'], "N", $ip);
    session_destroy();
    $msgerro = base64_encode('Usuário não está ativo. Contate o administrador do sistema!');
    header('Location: signin.php?token=' . $msgerro);
    exit();
} else { // caso contrário, login realizado
    $_SESSION['auth_data'] = $users[0];



    // checa a força da senha para dar feedback ao usuario
    $_SESSION['auth_data']['str'] = 'FORTE';
    $pass = base64_decode($senha);

    $upper = preg_match('@[A-Z]@', $pass);
    $lower = preg_match('@[a-z]@', $pass);
    $numbr = preg_match('@[0-9]@', $pass);
    $spec = preg_match('@[^\W]@', $pass);

    if(!$upper || !$lower || !$numbr || !$spec || strlen($pass) < 8) {
        $_SESSION['auth_data']['str'] = 'FRACA';
    }

    // Aqui adicionarei as permissoes para cada sistema

    //arranchamento
    if ($users[0]['acessorancho'] == "S") {
      if ($users[0]['contarancho'] == "1") {
        $_SESSION['nivel_arranchamento'] = "Usuário comum";
      } else if ($users[0]['contarancho'] == "2") {
        $_SESSION['nivel_arranchamento'] = "Furriel";
      } else if ($users[0]['contarancho'] == "3") {
        $_SESSION['nivel_arranchamento'] = "Aprovisionador";
      } else if ($users[0]['contarancho'] == "4") {
        $_SESSION['nivel_arranchamento'] = "Administrador";
      } else {
        $_SESSION['nivel_arranchamento'] = "Sem Acesso";
      }
    } else {
      $_SESSION['nivel_arranchamento'] = "Sem Acesso";

    }


    //fatos observados
    if ($users[0]['idpgrad'] <= 12) {
      $_SESSION['nivel_fatos_observados'] = "Usuário comum";
    } else {
      $_SESSION['nivel_fatos_observados'] = "Sem Acesso";
    }

    //guarda

    // alguns computadores devem ser configurados com ip fixo, assim permite acesso ao utilizador mesmo que ele não possua privilégios
    $ipGuarda = '10.57.101.177'; // computador do anotador da guarda
    // $ipNotebook = '10.57.101.178'; // retirado da RP
    $ipTotem = '10.57.101.73'; // computador do alojamento

    if ($users[0]['acessoguarda'] == "S") {
      if ($users[0]['contaguarda'] == "1" and ($ip == $ipGuarda /*or $ip == $ipNotebook*/)) { // IP da Gda e do Notebook da RP
        $_SESSION['nivel_guarda'] = "Anotador Gda";
      } else if (($users[0]['contaguarda'] == "1" or $users[0]['contaguarda'] == "2") and $ip == $ipTotem) { // IP do Totem
        $_SESSION['nivel_guarda'] = "Anotador Aloj";
      } else if ($users[0]['contaguarda'] == "2" and ($ip == $ipGuarda /*or $ip == $ipNotebook*/)) { // IP da Gda e do Notebook da RP
        $_SESSION['nivel_guarda'] = "Cabo Gda";
      } else  if ($users[0]['contaguarda'] == "3") {
        $_SESSION['nivel_guarda'] = "Oficial e Sargento";
      } else if ($users[0]['contaguarda'] == "4") {
        $_SESSION['nivel_guarda'] = "Supervisor";
      } else if ($users[0]['contaguarda'] == "5") {
        $_SESSION['nivel_guarda'] = "Administrador";
      } else {
        $_SESSION['nivel_guarda'] = "Sem Acesso";

      }
    } else {
      $_SESSION['nivel_guarda'] = "Sem Acesso";

    }

    // helpdesk
    if ($users[0]['acessohd'] == "S") {
      if ($users[0]['contahd'] == "1") {
        $_SESSION['nivel_helpdesk'] = "Usuário Comum";
      } else if ($users[0]['contahd'] == "2") {
        $_SESSION['nivel_helpdesk'] = "Supervisor";
      } else  if ($users[0]['contahd'] == "3") {
        $_SESSION['nivel_helpdesk'] = "Administrador";
      } else {
        $_SESSION['nivel_helpdesk'] = "Sem Acesso";
      }
    } else {
      $_SESSION['nivel_helpdesk'] = "Sem Acesso";
    }


    // plano de chamada
    if ($users[0]['acessopchamada'] == "S") {
      if ($users[0]['contapchamada'] == "1") {
        $_SESSION['nivel_plano_chamada'] = "Usuário comum"; 
      } else if ($users[0]['contapchamada'] == "2") {
        $_SESSION['nivel_plano_chamada'] = "Supervisor";
      } else if ($users[0]['contapchamada'] == "3") {
        $_SESSION['nivel_plano_chamada'] = "Administrador";
      } else {
        $_SESSION['nivel_plano_chamada'] = "Acesso Básico";
      }
    } else {
      $_SESSION['nivel_plano_chamada'] = "Acesso Básico";
    }

    //sis_cautela
    if ($users[0]['nivelacessocautela'] == "0") {
      $_SESSION['nivel_sis_cautela'] = "Usuário comum";
    } else if ($users[0]['nivelacessocautela'][1] == "A") {
      $_SESSION['nivel_sis_cautela'] = "Aux Enc Mat";
      $_SESSION['is_sub'] = 0;
    } else if ($users[0]['nivelacessocautela'][1] == "S") {
      $_SESSION['nivel_sis_cautela'] = "Enc Mat";
      $_SESSION['is_sub'] = 1;
    } else {
      $_SESSION['nivel_sis_cautela'] = "Sem Acesso";
    }


    // sistcomsoc
    if ($users[0]['acessosistcomsoc'] == "S") {
        if ($users[0]['contasistcomsoc'] == "0") {
          $_SESSION['nivel_com_soc'] = "Usuário Comum";
        } else if ($users[0]['contasistcomsoc'] == "1") {
          $_SESSION['nivel_com_soc'] = "Supervisor";
        } else  if ($users[0]['contasistcomsoc'] == "2") {
          $_SESSION['nivel_com_soc'] = "Administrador"; 
        } else {
          $_SESSION['nivel_com_soc'] = "Sem Acesso";
        }
    } else {
        $_SESSION['nivel_com_soc'] = "Sem Acesso";
    }

    //agora adiciono o registro do login
    gerar_log_login($users[0]['id'], "S", $ip);

}


?>
