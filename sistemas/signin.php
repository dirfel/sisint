<?php
session_start();

session_destroy();
$token = base64_decode(filter_input(INPUT_GET, "token"));
$token2 = base64_decode(filter_input(INPUT_GET, "token2"));
$token3 = base64_decode(filter_input(INPUT_GET, "token3"));
?>
<!doctype html>
<html lang="pt-BR" class="fixed accounts lock-screen">

<head>
  <title>SISTEMAS INTEGRADOS - Login</title>
  <?php include '../recursos/views/cabecalho.php'; ?>
</head>

<body>
  <div class="wrap">
    <div class="container">
      <div class="logo animated fadeInDown">
        <div class="avatar">
          <img alt="Sistemas Integrados" src="../recursos/assets/favicon.png" />
        </div>
      </div>
      <div class="box animated fadeInUpBig">
        <div class="panel">
          <div class="panel-content bg-scale-0">
            <div class="text-center">
              <h1 class="mt-sm color-primary text-bold">SISTEMAS INTEGRADOS</h1>
              <h3 class="panel-title text-warning">3ª Bateria de Artilharia Antiaérea</h3>
            </div>
            <?php include '../recursos/views/token.php'; ?>
            <form id="inline-validation" action="chkpass.php" method="post">
              <div class="form-group mt-md">
                <span class="input-with-icon">
                  <input type="text" name="login" onkeydown="checkLocks(event)" id="login" class="form-control" placeholder="CPF" required>
                  <i class="fa fa-user"></i>
                </span>
              </div>
              <div id="caps-lock-message" class="text-center bg-warning" style="display: none;">Caps Lock está ativado.</div>
              <div id="num-lock-message" class="text-center bg-warning" style="display: none;">Num Lock está desativado.</div>
              <br>
              <div class="form-group">
                <span class="input-with-icon">
                  <input type="password" name="senha" onkeydown="checkLocks(event)" class="form-control" placeholder="Senha" required>
                  <i class="fa fa-key"></i>
                </span>
              </div>
              <div class="form-group">
                <button type="submit" name="action" class="btn btn-primary btn-block">Acessar</button>
              </div>
              <div class="form-group text-center text-sm">
                <p> </p>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include '../recursos/views/footer.php'; ?>
  <script>
    $(function(){ $("#login").focus(); });

    function checkLocks(e) {
        const capsLockOn = e.getModifierState("CapsLock");
        const message = document.getElementById("caps-lock-message");
        const numLockOn = e.getModifierState("NumLock");
        const message2 = document.getElementById("num-lock-message");
        
        if (capsLockOn) { message.style.display = "block";
        } else { message.style.display = "none"; }
        if (numLockOn) { message2.style.display = "none";
        } else { message2.style.display = "block"; }
    }
  </script>
</body>

</html>
