<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

$idusuario = base64_decode(filter_input(INPUT_GET, "tkusr", FILTER_SANITIZE_SPECIAL_CHARS));

if ($_SESSION['auth_data']['id'] != $idusuario) {
    // die('asas');
    header('Location: index.php?token2='.base64_encode('Você não possui permissão para isso.'));
    exit();
  }

?>
<!doctype html>
<html lang="pt-BR" class="fixed left-sidebar-collapsed">

<head><?php include '../recursos/views/cabecalho.php'; ?></head>

<body>
  <div class="wrap">
    <div class="page-header">
    <?php render_painel_usu('CONTROLE DE PESSOAL', $_SESSION['nivel_plano_chamada']); ?>
    </div>
    <div class="page-body">
      <div class="left-sidebar"><?php include 'menu_opc.php'; ?></div>
      <div class="content">
      <?php render_content_header('Atualizar Senha', 'fa fa-user-lock'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-6">
            <div class="panel"><?php render_cabecalho_painel('MINHA SENHA:', 'fas fa-unlock-alt', true); ?>
              <div class="panel-content">
                <div class="row">
                  <div class="col-md-12">
                      No Sistemas Integrados, sua senha é criptografada de modo que nem o administrador do banco de dados pode ter acesso a ela.
                    <hr>
                    Lembrete: esse sistema só aceita senhas fortes, ou seja, deve conter ao menos uma letra maiúscula, uma minúscula, um caractere numérico, um caractere especial, sendo no mínimo oito digitos totais.
                    <hr>
                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#myModal"> ALTERAR SENHA</button>
                  </div>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                  <div class="modal-dialog" role="document">
                    <form id="inline-validation-senha" action="<?php echo ('conf_usu_indiv_senha.php?tkusr=' . base64_encode($idusuario)); ?>" method="post">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title" id="myModalLabel">Cadastrar nova senha</h4>
                        </div>
                        <div class="modal-body">
                          <div class="form-group">
                          <div id="caps-lock-message" class="text-center bg-warning" style="display: none;">Caps Lock está ativado.</div>
                            <div id="num-lock-message" class="text-center bg-warning" style="display: none;">Num Lock está desativado.</div>
                            <div class="input-group mb-sm mt-sm">
                              <span class="input-group-addon"><i class="fa fa-key color-success"></i></span>
                              <input type="password" class="form-control" name="senha-atual" id="senha-atual" onkeydown="checkLocks(event)" placeholder="Digite sua senha atual">
                            </div>
                            <div class="input-group mb-sm">
                              <span class="input-group-addon"><i class="fa fa-key color-warning"></i></span>
                              <input type="password" class="form-control" name="senha-nova" id="senha-nova" onkeydown="checkLocks(event)" onkeyup="checkPasswordMatch()" placeholder="Digite a senha nova">
                            </div>
                            <div class="input-group mb-sm">
                              <span class="input-group-addon"><i class="fa fa-key color-warning"></i></span>
                              <input type="password" class="form-control" name="senha-nova-confirma" id="senha-nova-confirma" onkeydown="checkLocks(event)" onkeyup="checkPasswordMatch()" placeholder="Confirme a senha nova">
                            </div>
                            <h5>Senhas fortes possuem obrigatoriamente:</h5>
                            <ul>
                                <li>8 caracteres ou mais <i id="chl" class="fa fa-close text-danger"></i></li>
                                <li>Uma ou mais letras maiúsculas <i id="chM" class="fa fa-close text-danger"></i></li>
                                <li>Uma ou mais letras minúsculas <i id="chm" class="fa fa-close text-danger"></i></li>
                                <li>Um ou mais número <i id="chn" class="fa fa-close text-danger"></i></li>
                                <li>Um ou mais caracteres especiais <i id="chs" class="fa fa-close text-danger"></i></li>
                            </ul>
                            <div class="input-group mb-sm">
                              <div id="divcheck"></div>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                          <button type="submit" name="action" value="Alterou senha" class="btn btn-danger" id="enviarsenha" disabled>CADASTRAR NOVA SENHA</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php include '../recursos/views/scroll_to_top.php'; ?>
    </div>
  </div>
  <?php include '../recursos/views/footer.php'; ?>
  <script type="text/javascript">
    $(document).ready(function() {
      $("#senha-nova").keyup(checkPasswordMatch);
      $("#senha-nova-confirma").keyup(checkPasswordMatch);
    });

    function checkPasswordMatch() {
      var password = $("#senha-nova").val();
      var confirmPassword = $("#senha-nova-confirma").val();

      //esses são os requisitos para trocar senha, se tudo der 1, será possível alterar a senha
      var length = 0;
      var maiuscula = 0;
      var minuscula = 0;
      var numero = 0;
      var especiais = 0;
      var iguais = 0;

      //checa se length >= 8
      if(password.length >= 8) {
        length = 1;
        document.getElementById("chl").className = 'fa fa-check text-success';
      } else {
          length = 0;
          document.getElementById("chl").className = 'fa fa-close text-danger';
      }
      //checa se contem maiúscula
      if(/[A-Z]/.test(password)) {
        maiuscula = 1;
        document.getElementById("chM").className = 'fa fa-check text-success';
      } else {
          maiuscula = 0;
          document.getElementById("chM").className = 'fa fa-close text-danger';
      }
      //checa se contem minuscula
      if(/[a-z]/.test(password)) {
        minuscula = 1;
        document.getElementById("chm").className = 'fa fa-check text-success';
      } else {
          minuscula = 0;
          document.getElementById("chm").className = 'fa fa-close text-danger';
      }
      //checa se contem número
      if(/[0-9]/.test(password)) {
        numero = 1;
        document.getElementById("chn").className = 'fa fa-check text-success';
      } else {
          numero = 0;
          document.getElementById("chn").className = 'fa fa-close text-danger';
      }
      //checa se contem caractere especial
      if(/[ `!@#$%^&*()_+\[[\]{};':"\\|,.<>\/?~]/.test(password)) {
        especiais = 1;
        document.getElementById("chs").className = 'fa fa-check text-success';
      } else {
          especiais = 0;
          document.getElementById("chs").className = 'fa fa-close text-danger';
      }
      //checa se as senhas conferem
      if (password == '' || '' == confirmPassword) {
        $("#divcheck").html("<span style='color: red'>Campo de senha vazio!</span>");
        iguais = 0;
      } else if (password != confirmPassword) {
        $("#divcheck").html("<span style='color: red'>Senhas não conferem!</span>");
        iguais = 0;
      } else {
        $("#divcheck").html("<span style='color: green'>Senha conferem!</span>");
        iguais = 1;
      }

        //Ativa ou desativa o botão seguindo as validações acima:
        if(length * maiuscula * minuscula * numero * especiais * iguais === 1) {
            document.getElementById("enviarsenha").disabled = false;
        } else {
            document.getElementById("enviarsenha").disabled = true;
        }
    }

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