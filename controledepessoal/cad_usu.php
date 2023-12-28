<?php
//essa é a tela de cadastro do novo usuário
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if ($_SESSION['auth_data']['contahd'] < "3") {
    header('Location: index.php');
    exit();
  }
$pdo = conectar("membros");
?>
<!doctype html>
<html lang="pt-BR" class="fixed left-sidebar-collapsed">
<head><?php include '../recursos/views/cabecalho.php'; ?></head>
<body>
  <div class="wrap">
    <div class="page-header"><?php render_painel_usu('CONTROLE DE PESSOAL', $_SESSION['nivel_plano_chamada']); ?></div>
    <div class="page-body">
      <div class="left-sidebar"><?php include 'menu_opc.php'; ?></div>
      <div class="content">
      <?php render_content_header('Administração de Usuários', 'fa fa-users-cog'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <form id="inline-validation" action="cad_usu_atualiza.php" method="get">
            <div class="col-sm-12 col-md-6">
              <div class="panel"><?php render_cabecalho_painel('ATUALIZAR DADOS DE USUÁRIO ATIVO:', 'fas fa-pensil-alt', true); ?>
                <div class="panel-content">
                  <div class="row">
                  <div class="col-md-12"><?php render_militar_ativo_select('tkusr', 'select-ativos', true);?></div>
                    <div class="col-md-12"><hr>
                      <button type="submit" name="btn_atualiza_cadastro" class="btn btn-darker-1">ALTERAR</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
          <form id="inline-validation2" action="cad_usu_atualiza.php" method="get">
            <div class="col-sm-12 col-md-6">
              <div class="panel"><?php render_cabecalho_painel('ATUALIZAR DADOS DE USUÁRIO INATIVO:', 'fas fa-pensil-alt', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="inputMaxLength" class="control-label">Selecione o Militar da OM na lista:</label>
                        <div class="input-group mb-sm">
                          <span class="input-group-addon"><i class="fa fa-group"></i></span>
                          <select name="tkusr" class="select form-control" style="width: 100%" required>
                            <option></option>
                            <optgroup label='Militar da OM'>
                            <?php $regs = read_usuarios_situacao("N");
                            foreach($regs as $reg1) {
                              echo ("<option value=" . base64_encode($reg1['id']) . ">" . ImprimeConsultaMilitar($reg1) . "</option>");
                            } ?>
                            </optgroup>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <hr>
                      <button type="submit" name="btn_atualiza_cadastro" class="btn btn-darker-2">ALTERAR</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
          <form id="inline-validation3" action="conf_usu_novo.php" method="post">
            <div class="col-sm-12 col-md-12">
              <div class="panel"><?php render_cabecalho_painel('CADASTRAR NOVO USUÁRIO:', 'fa fa-user-plus', true); ?>
                <div class="panel-content" >
                  <div class="row">
                    <?php render_painel_dados_usuario(); ?>
                    <div class="col-md-12">
                      <hr>
                    </div>
                    <div class="col-sm-6 col-md-3">
                      <div class="form-group mb-sm">
                        <label>Sistema Arranchamento:</label>
                        <div class="checkbox-custom checkbox-success">
                          <input type="checkbox" name="acessorancho" id="checkboxrancho" value="S">
                          <label class="check" for="checkboxrancho">Acesso ao Sistema?</label>
                        </div>
                        <?php render_radio_button('Usuário comum', 'contarancho1', 'contarancho', '1', true) ?>
                        <?php render_radio_button('Furriel', 'contarancho2', 'contarancho', '2', false) ?>
                        <?php render_radio_button('Aprovisionador', 'contarancho3', 'contarancho', '3', false) ?>
                        <?php render_radio_button('Administrador', 'contarancho4', 'contarancho', '4', false) ?>
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                      <div class="form-group mb-sm">
                        <label>Sistema Guarda:</label>
                        <div class="checkbox-custom checkbox-success">
                          <input type="checkbox" name="acessoguarda" id="checkboxguarda" value="S">
                          <label class="check" for="checkboxguarda">Acesso ao Sistema?</label>
                        </div>
                        <?php render_radio_button('Anotador', 'contaguarda1', 'contaguarda', '1', true) ?>
                        <?php render_radio_button('Cabo Gda', 'contaguarda2', 'contaguarda', '2', false) ?>
                        <?php render_radio_button('Oficial e Sargento', 'contaguarda3', 'contaguarda', '3', false) ?>
                        <?php render_radio_button('Supervisor', 'contaguarda4', 'contaguarda', '4', false) ?>
                        <?php render_radio_button('Administrador', 'contaguarda5', 'contaguarda', '5', false) ?>
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                      <div class="form-group mb-sm">
                        <label>Help Desk:</label>
                        <div class="checkbox-custom checkbox-success">
                          <input type="checkbox" name="acessohd" id="checkboxhd" value="S">
                          <label class="check" for="checkboxhd">Acesso ao Sistema?</label>
                        </div>
                        <?php render_radio_button('Usuário comum', 'contahd1', 'contahd', '1', true) ?>
                        <?php render_radio_button('Supervisor', 'contahd2', 'contahd', '2', false) ?>
                        <?php render_radio_button('Administrador', 'contahd3', 'contahd', '3', false) ?>
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                      <div class="form-group mb-sm">
                        <label>Controle de Pessoal:</label>
                        <div class="checkbox-custom checkbox-warning">
                          <input type="checkbox" name="acessopchamada" id="checkboxpchamada" value="S">
                          <label class="check" for="checkboxpchamada">Acesso ao Sistema?</label>
                        </div>
                        <?php render_radio_button('Usuário comum (Of-Dia)', 'contapchamada1', 'contapchamada', '1', true) ?>
                        <?php render_radio_button('Supervisor (Cmt fração, Sgte, S1, Cmt e Scmt)', 'contapchamada2', 'contapchamada', '2', false) ?>
                        <?php render_radio_button('Administrador (Seç TI)', 'contapchamada3', 'contapchamada', '3', false) ?>
                      </div>
                    </div>
                    <div class="col-sm-12"><hr></div>
                    <div class="col-sm-6 col-md-3">
                      <div class="form-group mb-sm">
                        <label>Fatos Observados:</label>
                        Ao sistema Caveirinha, o usuário terá acesso se for Oficial ou Sargento apenas.
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                      <div class="form-group mb-sm">
                        <label>Sis Cautela:</label>
                        <div class="radio radio-custom radio-success">
                          <input type="radio" id="cautcomum" name="nivelacessocautela" value="0" checked>
                          <label for="cautcomum">Usuário comum</label>
                        </div>
                        <?php
                            $depositos = conectar("siscautela")->prepare('SELECT * FROM depositos');
                            $depositos->execute();
                            $depositos = $depositos->fetchAll(PDO::FETCH_ASSOC);
                            foreach($depositos as $deposito) { ?>
                        <div class="radio radio-custom radio-warning">
                          <input type="radio" id="chefe<?= $deposito['id'] ?>" name="nivelacessocautela" value="<?= $deposito['id'] ?>S">
                          <label for="chefe<?= $deposito['id'] ?>"><?= $deposito['func_responsavel'] ?></label>
                        </div>
                        <div class="radio radio-custom radio-danger">
                          <input type="radio" id="aux<?= $deposito['id'] ?>" name="nivelacessocautela" value="<?= $deposito['id'] ?>A">
                          <label for="aux<?= $deposito['id'] ?>">Aux <?= $deposito['func_responsavel'] ?></label>
                        </div>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-3"><div class="form-group mb-sm"><label></label></div></div>
                    <div class="col-sm-6 col-md-3">
                    <div class="form-group mb-sm">
                        <label>Com Soc, HT e Conformidade:</label>
                        <?php render_checkbox('Acesso ao Sistema?', 'checkboxsistcomsoc', 'acessosistcomsoc', 'S', false);?>
                    </div>
                    <div class="col-sm-12">
                      <hr>
                      <button type="submit" name="btn_novo_cadastro" value='Cadastrou novo usuário' class="btn btn-warning">CADASTRAR</button>
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