<?php
require "../recursos/models/versession.php";

$ip = filter_input(INPUT_POST, "ip", FILTER_SANITIZE_STRING);

header("Location: https://" . $ip . ":10000");
exit();
