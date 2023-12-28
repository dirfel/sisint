<?php
/**
 * Esse arquivo define a página de atualização de dependentes do militar
 */

require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

// define variaveis padrão
$pdo = conectar("membros");
$pdo2 = conectar("guarda");
$pdo3 = conectar("controlepessoal");
$idusuario;
$militar;

if($_SESSION['nivel_helpdesk'] != 'Administrador') { // limita acesso a quem não possui permissão
  die('Em desenvolvimento');
} else if(!isset($_GET['tkusr'])) {
  header('Location: index.php?token2='.base64_encode('Nenhum militar escolhido'));
  exit();
}

$idusuario = base64_decode($_GET['tkusr']) ?? $_SESSION['auth_data']['id'];

if ($_SESSION['auth_data']['contahd'] < "3" && $_SESSION['auth_data']['id'] != $idusuario) { // usuario sem permissao
  header('Location: index.php?token2='.base64_encode('Você não possui permissão para isso'));
  exit();
}

if($idusuario > 0) { // integrante da OM
  $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE id = :id');
  $stmt->bindValue('id', $idusuario, PDO::PARAM_INT);
    
    $stmt->execute();
  // die('ok');
    $militar = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
  } else if($idusuario < 0) {
    $stmt = $pdo2->prepare('SELECT * FROM visitante WHERE id = :id');
    $stmt->bindValue('id', ($id * -1), PDO::PARAM_INT);
    $stmt->execute();
    $militar = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
    if($militar['tipo'] != 'Militar Inativo' && $militar['tipo'] != 'Pensionista Militar') {
      header('Location: index.php?token2='.base64_encode('Essa pessoa não permite cadastro de dependentes'));
      exit();
    }
  } else {
    header('Location: index.php?token2='.base64_encode('Usuário inválido'));
    exit();
  }
  
  $_GET['token2'] = base64_encode(getPGrad('Cadastro de dependentes do ' . $militar['idpgrad'] . ' ' . $militar['nomecompleto']));
  
  
  


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
      <?php render_content_header('Atualizar dependentes de '. getPGrad($militar['idpgrad']) . ' ' . $militar['nomecompleto'], 'fa fa-cutlery'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-6">
            <form action="" method="post">
              <div class="panel"><?php render_cabecalho_painel('INCLUIR DEPENDENTE:', 'fas fa-plus', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-md-12"><?php render_visitante_ativo_por_tipo_select($id_visitante, 'abc123', 'Dependente de Militar', true); ?></div>
                    <div class="col-md-12"><?php render_grau_parentesco_dependente_select('parentesco'); ?></div>
                    <div class="col-md-6"><?php render_custom_input('PREC CP', 'prec_cp', 'prec_cp', '', 11, 'PREC CP', true, false); ?></div>
                    <div class="col-md-6"><?php render_custom_input('Obs:', 'Obs', 'obs', '', 50, 'Obs', false, false); ?></div>
                    <div class="col-md-12">
                      <hr>
                      <button type="submit" name="action" value='Incluir Dependente' class="btn btn-warning">INCLUIR DEPENDENTE</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
            <?php render_card1("ficha_cadben.php","Card ficha cadben","icon fas fa-list", "<h4 class='title'>Card ficha cadben</h4>"); ?>
            </div>
          <div class="col-sm-12 col-md-6">
            <div class="panel"><?php render_cabecalho_painel('DEPENDENTES CADASTRADOS:', 'fas fa-user', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <table class="table text-center">
                        <thead>
                            <td>PREC CP</td>
                            <td>Nome Completo</td>
                            <td>Parentesco</td>
                            <td>Data Nascimento</td>
                            <td>Observações</td>
                            <td>Ações</td>
                        </thead>
                        <tbody>
                            <?php $consulta = $pdo3->prepare('SELECT dependentes_fusex.id, dependentes_fusex.prec_cp, dependentes_fusex.parentesco, visitante.nomecompleto, visitante.datanascimento
                                FROM dependentes_fusex 
                                INNER JOIN guarda.visitante on dependentes_fusex.id_visitante = visitante.id 
                                WHERE dependentes_fusex.id_titular = :meu_id');
                                $consulta->bindValue(':meu_id', $_SESSION['auth_data']['id'], PDO::PARAM_INT);
                                $consulta->execute();
                                $dependentes = $consulta->fetchAll(PDO::FETCH_ASSOC);
                                foreach($dependentes as $dependente) { ?>
                            <tr>
                                <td><?= $dependente['prec_cp'] ?></td>
                                <td><?= $dependente['nomecompleto'] ?></td>
                                <td><?= $dependente['parentesco'] ?></td>
                                <td><?= $dependente['datanascimento'] ?></td>
                                <td><?= $dependente['obs'] ?></td>
                                <td>
                                    <a><button data-toggle="modal" data-target="#editDepModal<?= $dependente["id"] ?>" class="btn btn-success"><i class="fas fa-edit"></i></button></a> 
                                    <a><button class="btn btn-danger"><i class="fas fa-trash"></i></button></a>
                                </td>
                            </tr>
                            <!-- modal -->
                            <div class="modal fade" id="editDepModal<?= $dependente["id"] ?>" tabindex="-1" role="dialog" aria-labelledby="editDepModalLabel<?= $dependente["id"] ?>" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editDepModalLabel<?= $dependente["id"] ?>">Editar informações de dependente</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form method="post" action="cad_usu_indiv_dep.php?tkusr=<?=base64_encode($_SESSION['auth_data']['id'])?>">
                                            <div class="modal-body">
                                                <input type="hidden" name="dep_id" value="<?= $dependente["id"] ?>">
                                                <input type="hidden" name="action" value="atualizar">
                                                <h4 class="text-center"><?=$dependente['nomecompleto'] ?></h4>
                                                <?php render_custom_input('PREC CP', 'prec_cp', 'prec_cp', $dependente["prec_cp"], 11, 'PREC CP', true, false); ?>
                                                <?php render_grau_parentesco_dependente_select('parentesco', $dependente["parentesco"]); ?>
                                                <?php render_custom_input('Obs:', 'Obs', 'obs', $dependente["obs"], 50, 'Obs', false, false); ?>
                                                <?php render_data_field('nascimento', 'true', 'Data de nascimento:', $dependente["datanascimento"]); ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                                <button type="submit" action="atualizar" class="btn btn-primary">Atualizar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Fim Modal -->
                            <?php } ?>
                        </tbody>
                    </table>
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
</body>
</html>