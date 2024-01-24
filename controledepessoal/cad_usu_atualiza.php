<?php
//essa é a tela de atualização de usuário
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

$idusuario = base64_decode(filter_input(INPUT_GET, "tkusr", FILTER_SANITIZE_SPECIAL_CHARS));

if ($_SESSION['nivel_helpdesk'] != "Administrador" && $_SESSION['auth_data']['id'] != $idusuario) {
    header('Location: index.php?token2='.base64_encode('Você não possui permissão para isso'));
    exit();
}

$reg_usu = read_usuario_by_id($idusuario);

?>

<!doctype html>
<html lang="pt-BR" class="fixed left-sidebar-collapsed">

<head>
  <?php include '../recursos/views/cabecalho.php'; ?>
</head>

<body>
  <div class="wrap">
    <div class="page-header">
    <?php render_painel_usu('CONTROLE DE PESSOAL', $_SESSION['nivel_plano_chamada']); ?>
    </div>
    <div class="page-body">
      <div class="left-sidebar">
        <?php include 'menu_opc.php'; ?>
      </div>
      <div class="content">
      <?php render_content_header('Atualizar Dados de Usuários', 'fa fa-pencil-alt'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <form id="inline_validation" action="<?='conf_usu_atualiza.php?tkusr=' . base64_encode($idusuario) ?>" method="post">
            <div class="col-sm-12 col-md-12">
              <div class="panel"><?php render_cabecalho_painel('DADOS DO USUÁRIO (ID: '.$idusuario.')', 'fa fa-pencil-alt', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <?php render_painel_dados_usuario($reg_usu);?> 
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <?php render_checkbox('Usuário ativo? (Desmarque para militares transferidos ou licenciados)', 'checkboxuserativo', 'userativo', 'S', ($reg_usu["userativo"] == 'S'));?>
                        <?php render_checkbox('Resetar senha? (a nova senha passará a ser o CPF)', 'resertarsenha', 'resertarsenha', 'S', false);?>
                      </div>
                    </div>
                    <div class="col-md-12"><hr></div>
                    <div class="col-sm-6 col-md-3"> <!--niveis acesso-->
                      <div class="form-group mb-sm">
                        <label>Sistema Arranchamento:</label>
                        <?php render_checkbox('Acesso ao Sistema?', 'checkboxrancho', 'acessorancho', 'S', ($reg_usu["acessorancho"] == 'S'));?>
                        <?php render_radio_button('Usuário comum', 'contarancho1', 'contarancho', 1, ($reg_usu["contarancho"] == 1));?>
                        <?php render_radio_button('Furriel', 'contarancho2', 'contarancho', 2, ($reg_usu["contarancho"] == 2));?>
                        <?php render_radio_button('Aprovisionador', 'contarancho3', 'contarancho', 3, ($reg_usu["contarancho"] == 3));?>
                        <?php render_radio_button('Administrador', 'contarancho4', 'contarancho', 4, ($reg_usu["contarancho"] == 4));?>
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-3"><!--niveis acesso-->
                      <div class="form-group mb-sm">
                        <label>Sistema Guarda:</label>
                        <?php render_checkbox('Acesso ao Sistema?', 'checkboxguarda', 'acessoguarda', 'S', ($reg_usu["acessoguarda"] == 'S'));?>
                        <?php render_radio_button('Anotador', 'contaguarda1', 'contaguarda', 1, ($reg_usu["contaguarda"] == 1));?>
                        <?php render_radio_button('Cabo Gda', 'contaguarda2', 'contaguarda', 2, ($reg_usu["contaguarda"] == 2));?>
                        <?php render_radio_button('Oficial e Sargento', 'contaguarda3', 'contaguarda', 3, ($reg_usu["contaguarda"] == 3));?>
                        <?php render_radio_button('Supervisor', 'contaguarda4', 'contaguarda', 4, ($reg_usu["contaguarda"] == 4));?>
                        <?php render_radio_button('Administrador', 'contaguarda5', 'contaguarda', 5, ($reg_usu["contaguarda"] == 5));?>
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-3"><!--niveis acesso-->
                      <div class="form-group mb-sm">
                        <label>Sistema Help Desk:</label>
                        <?php render_checkbox('Acesso ao Sistema?', 'checkboxhd', 'acessohd', 'S', ($reg_usu["acessohd"] == 'S'));?>
                        <?php render_radio_button('Usuário comum', 'contahd1', 'contahd', 1, ($reg_usu["contahd"] == 1));?>
                        <?php render_radio_button('Supervisor', 'contahd2', 'contahd', 2, ($reg_usu["contahd"] == 2));?>
                        <?php render_radio_button('Administrador', 'contahd3', 'contahd', 3, ($reg_usu["contahd"] == 3));?>
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-3"><!--niveis acesso-->
                      <div class="form-group mb-sm">
                        <label>Sistema: Plano de Chamada:</label>
                        <?php render_checkbox('Acesso ao Sistema?', 'checkboxpchamada', 'acessopchamada', 'S', ($reg_usu["acessopchamada"] == 'S'));?>
                        <?php render_radio_button('Usuário comum (Of-Dia)', 'contapchamada1', 'contapchamada', 1, ($reg_usu["contapchamada"] == 1));?>
                        <?php render_radio_button('Supervisor (Cmt fração, Sgte, S1, Cmt e Scmt)', 'contapchamada2', 'contapchamada', 2, ($reg_usu["contapchamada"] == 2));?>
                        <?php render_radio_button('Administrador (Seç TI)', 'contapchamada3', 'contapchamada', 3, ($reg_usu["contapchamada"] == 3));?>
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <hr>
                    </div>
                    <div class="col-sm-6 col-md-3"><!--niveis acesso-->
                      <div class="form-group mb-sm">
                        <label>Fatos Observados:</label>
                        Somente terá acesso ao sistema de Fatos Observados, o usuário ativo que for Oficial ou Sargento.
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-3"><!--niveis acesso-->
                      <div class="form-group mb-sm">
                        <label>Sis Cautela:</label>
                        <?php render_radio_button('Usuário comum', 'cautcomum', 'nivelacessocautela', 0, ($reg_usu["nivelacessocautela"] == 0));?>
                        <?php
                        $depositos = conectar("siscautela")->prepare('SELECT * FROM depositos');
                        $depositos->execute();
                        $depositos = $depositos->fetchAll(PDO::FETCH_ASSOC);
                        foreach($depositos as $deposito) { 
                          render_radio_button($deposito['func_responsavel'], 'chefe'.$deposito['id'], 'nivelacessocautela', $deposito['id'].'S', ($reg_usu["nivelacessocautela"] == $deposito['id'] . 'S'));
                          render_radio_button('Aux '.$deposito['func_responsavel'], 'aux'.$deposito['id'], 'nivelacessocautela', $deposito['id'].'A', ($reg_usu["nivelacessocautela"] == $deposito['id'] . 'A'));
                        } ?>
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-3"></div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group mb-sm">
                            <label>Com Soc, HT e Conformidade:</label>
                            <?php render_checkbox('Acesso ao Sistema?', 'checkboxsistcomsoc', 'acessosistcomsoc', 'S', ($reg_usu["acessosistcomsoc"] == 'S'));?>
                        </div>
                    </div>
                    <!-- <div class="col-sm-6 col-md-3"><div class="form-group mb-sm"><label>Outros sistemas:</label> A criar e Implementar.</div></div> -->
                    <div class="col-sm-12"><hr>
                      <button type="submit" name="btn_atualiza_cadastro" value='Editou cadastro de usuário' class="btn btn-warning">ALTERAR</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
      <?php include '../recursos/views/scroll_to_top.php'; ?>
    </div>
  </div>
  <?php include '../recursos/views/footer.php'; ?>
</body>

</html>