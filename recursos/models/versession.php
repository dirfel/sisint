<?php
session_start();

if (!isset($_SESSION["auth_data"]['id'])) {
  session_destroy();
  header("Location: ../sistemas/signin.php");
  exit();
}

date_default_timezone_set("America/Cuiaba");

$token = base64_decode(filter_input(INPUT_GET, "token"));
$token2 = base64_decode(filter_input(INPUT_GET, "token2"));
$token3 = base64_decode(filter_input(INPUT_GET, "token3"));
