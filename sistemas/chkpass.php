<?php
if (!isset($_POST['action'])) {
  header('Location: signin.php?token2='.base64_encode('Algum erro inesperado, contate o administrador'));
  exit();
}
session_start();
require "../recursos/models/conexao.php";

require '../recursos/models/do_auth.php';

require '../recursos/models/client_ip.php';

header('Location: index.php?token2='. base64_encode('<div class="service-desc">Bem vindo '. ImprimeConsultaMilitar2($_SESSION['auth_data']).'</div>'));
exit();
