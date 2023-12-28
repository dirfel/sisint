<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

$numchamado = base64_decode(filter_input(INPUT_GET, "out"));
$criptolink = base64_encode($numchamado);

$pdo = conectar("helpdesk");
$pdo2 = conectar("membros");

$cons_chamado = $pdo->prepare("SELECT * FROM chamado WHERE numchamado = :numchamado");
$cons_chamado->bindParam(":numchamado", $numchamado, PDO::PARAM_INT);
$cons_chamado->execute();
$chamado = $cons_chamado->fetchAll(PDO::FETCH_ASSOC);
$chamado = $chamado[0];
$tempo_chamado = calculaData($chamado['dataabertura'], $chamado['horaabertura'], $chamado['datafechamento'], $chamado['horafechamento']);

$cons_solicitante = $pdo2->prepare("SELECT usuarios.nomeguerra, idpgrad FROM usuarios WHERE usuarios.id = :id");
$cons_solicitante->bindParam(":id", $chamado['idsolicitante'], PDO::PARAM_INT);
$cons_solicitante->execute();
while ($reg = $cons_solicitante->fetch(PDO::FETCH_ASSOC)) {
  $nome_solicitante = $reg['nomeguerra'];
  $pg_solicitante = getPGrad($reg['idpgrad']);
}

$cons_servico = $pdo->prepare("SELECT servico FROM servico WHERE id = :id");
$cons_servico->bindParam(":id", $chamado['idservico'], PDO::PARAM_INT);
$cons_servico->execute();
while ($reg = $cons_servico->fetch(PDO::FETCH_ASSOC)) {
  $servico = $reg['servico'];
}

$cons_secao = $pdo->prepare("SELECT secao FROM secao WHERE id = :id");
$cons_secao->bindParam(":id", $chamado['idsecao'], PDO::PARAM_INT);
$cons_secao->execute();
while ($reg = $cons_secao->fetch(PDO::FETCH_ASSOC)) {
  $secao = $reg['secao'];
}

$cons_historico = $pdo->prepare("SELECT * FROM historico WHERE numchamado = :numchamado ORDER BY id ASC");
$cons_historico->bindParam(":numchamado", $chamado['numchamado'], PDO::PARAM_INT);
$cons_historico->execute();

if ($chamado['situacao'] == "1") {
  $situacao_chamado = "Em espera";
} else if ($chamado['situacao'] == "2") {
  $situacao_chamado = "Em atendimento";
} else {
  $situacao_chamado = "Finalizado";
}

if ($chamado['idetiqueta'] == '0') {
  $chamado['idetiqueta'] = 'Não é o caso';
}

?>
<!doctype html>
<html lang="pt-BR" class="fixed left-sidebar-collapsed">

<head>
  <?php include '../recursos/views/cabecalho.php'; ?>
</head>

<body>
  <div class="wrap">
    <div class="page-header">
    <?php render_painel_usu('HELPDESK', $_SESSION['nivel_helpdesk']); ?>
    </div>
    <div class="page-body">
      <div class="left-sidebar">
        <?php include 'menu_opc.php'; ?>
      </div>
      <div class="content">
      <?php render_content_header('Chamado: '.$numchamado.' - Solicitante: '. $pg_solicitante. ' '. $nome_solicitante. ' (ID: '.$chamado['idsolicitante'].')', 'fa fa-mail-reply-all'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <form role="form" method="post" action="conf_chamado.php?out=<?= $criptolink; ?>" enctype="multipart/form-data">
            <div class="col-sm-12 col-md-12">
              <div class="panel"><?php render_cabecalho_painel($numchamado, 'fas fa-mail-reply-all', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-sm-12 col-md-12">
                      <div class="form-group">
                        <p for="inputMaxLength" class="control-label"><i class="fas fa-clock color-darker-1"></i><b> Tempo do Chamado:</b> <?= ($tempo_chamado); ?>.</p>
                        <p for="inputMaxLength" class="control-label"><i class="fas fa-spinner color-darker-1 rotate"></i><b> Situação do Chamado:</b> <?= ($situacao_chamado); ?>.</p>
                        <p for="inputMaxLength" class="control-label"><i class="fas fa-wrench color-darker-1"></i><b> Tipo do Serviço:</b> <?= ($servico); ?>.</p>
                        <p for="inputMaxLength" class="control-label"><i class="fa fa-desktop color-darker-1"></i><b> Etiqueta da Máquina:</b> <?= ($chamado['idetiqueta']); ?>.</p>
                        <p for="inputMaxLength" class="control-label"><i class="fas fa-boxes color-darker-1"></i><b> Seção:</b> <?= ($secao); ?>.</p>
                        <p for="inputMaxLength" class="control-label"><b>Assunto:</b> <?= ($chamado['assunto']); ?>.</p>
                        <?php  while ($reg = $cons_historico->fetch(PDO::FETCH_ASSOC)) { ?>
                          <div class="col-sm-12 col-md-12"><hr></div>
                          <div class="col-sm-12 col-md-12 b-sm b-warning">
                            <p for="inputMaxLength" class="control-label"><b>Histórico:</b> <?= ($reg['texto']); ?>.</p>
                            <?php if ($reg['anexo'] <> '') { ?>
                              <a href='<?= ($reg['anexo']); ?>' target="blank" title='Arquivo anexo'>
                                <p for="inputMaxLength" class="control-label"><b>Anexo:</b> <span class='glyphicon glyphicon-paperclip'></span></p>
                              </a>
                            <?php } ?>
                          </div>
                        <?php } ?>
                      </div>
                    </div>
                    <?php if ($_SESSION['nivel_helpdesk'] == "Administrador" or $_SESSION['auth_data']['id'] == $chamado['idsolicitante']) { ?>
                      <div class="col-sm-12 col-md-12"><hr></div>
                      <div class="col-sm-12 col-md-12">
                        <div class="form-group">
                          <p for="inputMaxLength" class="control-label"><i class="fas fa-external-link-square-alt color-darker-1"></i><b> Enviar Nova Informação:</b></p>
                          <textarea name="chamado" id="autosize" class="form-control" rows="6" placeholder="Nova Mensagem" maxlength="500" required></textarea>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <br>
                      </div>
                      <div class="col-sm-12 col-md-12">
                        <div class="form-group">
                          <label for="inputMaxLength" class="control-label">Enviar Novo Arquivo:</label>
                          <input class="btn" name="arquivo" type="file" />
                        </div>
                      </div>
                      <div class="col-sm-12 col-md-12">
                        <hr>
                        <?php if ($chamado['situacao'] == "1" or $chamado['situacao'] == "2") { ?>
                          <button type="submit" name="action" value='Histórico de Chamado' class="btn btn-warning" style="width:220px;">ENVIAR NOVA INFORMAÇÃO</button>
                          <hr>
                          <button type="submit" name="action" value='Finalizar Chamado' class="btn btn-danger" style="width: 220px;">FINALIZAR CHAMADO</button>
                        <?php } ?>
                        <?php if ($chamado['situacao'] == "3") { ?>
                          <button type="submit" name="action" value='Reabrir Chamado' class="btn btn-danger">REABRIR CHAMADO</button>
                        <?php } ?>
                      </div>
                    <?php } ?>
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