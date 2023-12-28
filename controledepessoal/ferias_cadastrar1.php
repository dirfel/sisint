<?php
// esse arquivo é a interface ao usuário
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
if ($_SESSION['nivel_plano_chamada'] == "Sem Acesso") {
    $msgerro = base64_encode('Usuário não possui acesso!');
    header('Location: ../sistemas/index.php?token=' . $msgerro);
    exit();
}
$p1 = conectar("membros");
$p2 = conectar('controlepessoal');
$stmt = $p1->prepare('SELECT * FROM usuarios WHERE userativo = "S" ORDER BY idpgrad, nomeguerra ASC');
$stmt->execute();
$reg1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
$escolhido = '';
?>
<!doctype html>
<html lang="pt-BR" class="fixed">

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
      <?php render_content_header('Controle de Férias', 'fa fa-home'); ?>
        <div class="row animated zoomInDown">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-4">
            <form id="validation" action="ferias_cadastrar1.php" method="get">
              <div class="panel"><?php render_cabecalho_painel('ESCOLHA O MILITAR:', 'fa fa-street-view', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-md-12 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Escolha o militar:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-street-view"></i></span>
                          <select name="id_usr" class="form-control select select2-hidden-accessible" style="width: 100%" required="Preenchimento obrigatório" tabindex="-1" aria-hidden="true">
                            <optgroup label="Selecione um militar">
                                <option></option>
                                <?php foreach($reg1 as $chave) { ?>
                                <option value="<?= $chave["id"]?>">
                                    <?= getPGrad($chave["idpgrad"])?> <?=$chave["nomeguerra"]?>
                                </option>
                                <?php } ?>
                            </optgroup>  
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <hr>
                      <button type="submit" class="btn btn-primary" style="width: 140px;" >
                        CONSULTAR
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
            <div class="panel"><?php render_cabecalho_painel('HISTÓRICO DE FÉRIAS:', 'fa fa-history', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-md-12 mb-sm">
                      <table class="table table-bordered text-center">
                        <tr><td>Ano Referência</td><td>Dias Cadastrados</td></tr>
                        <?php
                          if(isset($_GET['id_usr'])) {
                            $stmt = $p2->prepare('SELECT MIN(anoref) FROM ferias WHERE id_usr = '.$_GET['id_usr']);
                            $stmt->execute();
                            $anorefmin = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]['MIN(anoref)'];
                            $stmt = $p2->prepare('SELECT MAX(anoref) FROM ferias WHERE id_usr = '.$_GET['id_usr']);
                            $stmt->execute();
                            $anorefmax = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]['MAX(anoref)'];
                            $ano = $anorefmin;
                            while($ano<=$anorefmax) {

                            $stmt = $p2->prepare('SELECT anoref, SUM(DATEDIFF(datafim, datainicio)) as tot FROM ferias WHERE id_usr = '.$_GET['id_usr'].' AND tipo != 0 AND anoref = '.$ano);
                            $stmt->execute();
                            $consu = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]['tot'];
                              echo '<tr>';
                                echo '<td>'.$ano.'</td>';
                                echo '<td>'.$consu.'</td>';
                              echo '</tr>';
                              $ano++;
                            }
                          }?>
                      </table>
                  </div>
                </div>
              </div>
            </div>
        </div>
    <div class="col-sm-12 col-md-8">
        
            <?php
             if(!isset($_GET['id_usr'])) { $ano_cons = isset($_GET['ano_cons']) ? $_GET['ano_cons'] : date('Y')-1; ?>
            <div class="panel"><?php render_cabecalho_painel('REGISTROS DE PLANO DE FÉRIAS EM <a href="ferias_cadastrar1.php?ano_cons='.$ano_cons-1 . '"><i class="fa fa-arrow-left text-danger"></i>   '.$ano_cons.'   <a href="ferias_cadastrar1.php?ano_cons='.$ano_cons+1 .'"><i class="fa fa-arrow-right text-danger"></i>', 'fa fa-car', true); ?>
                <div class="panel-content">
                    <table class="table">
                        <tr class="text-center">
                            <td>Militar</td>
                            <td>Situação</td>
                            <td>Dias Gozados</td>
                            <td>Dias Cadastrados</td>
                            <td>Ações</td>
                        </tr>
                        <?php
                        //obterei quantos dias disponiveis tem no $ano_cons para cada militar
                        foreach($reg1 as $mil) {
                            $stmt = $p2->prepare('SELECT SUM(DATEDIFF(datafim, datainicio)) as tot FROM ferias WHERE id_usr = '.$mil['id'].' AND gozado = 1 AND anoref = '.$ano_cons);
                            $stmt->execute();
                            $consu = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]['tot'] ?? 0;

                            $stmt = $p2->prepare('SELECT SUM(DATEDIFF("'.date('Y-m-d').'", datainicio)) as tot FROM ferias WHERE id_usr = '.$mil['id'].' AND gozado = -1 AND anoref = '.$ano_cons);
                            $stmt->execute();
                            $abe = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]['tot'] ?? 0;
                            $consu += $abe;

                            $stmt = $p2->prepare('SELECT SUM(DATEDIFF(datafim, datainicio)) as tot FROM ferias WHERE id_usr = '.$mil['id'].' AND anoref = '.$ano_cons);
                            $stmt->execute();
                            $cad = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]['tot'] ?? 0;
                        ?>
                        <tr>
                            <td><?= ImprimeConsultaMilitar2($mil) ?></td>
                            <td class="text-center"><?=$abe == 0 ? '-' : 'Em férias' ?></td>
                            <td class="text-center"><?=$consu?><?= $consu > 30 ? ' !!!!!!!' : ''?></td>
                            <td class="text-center"><?=$cad?><?= $cad > 30 ? ' !!!!!!!' : ''?></td>
                            <td class="text-center"><a href="ferias_cadastrar1.php?id_usr=<?=$mil['id']?>"><i class="fa fa-eye"></i></a></td>
                        </tr>
                        <?php } ?>

                    </table>
                </div>
            </div>
             <?php }
             if(isset($_GET['id_usr'])) {
                $stmt = $p1->prepare('SELECT id, nomeguerra, idpgrad FROM usuarios WHERE id = '.$_GET['id_usr']);
                $stmt->execute();
                $reg2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if(count($reg2) == 1) { $escolhido = getPGrad($reg2[0]["idpgrad"]).' '.$reg2[0]["nomeguerra"];
                } else { echo 'Erro ao obter informações'; }
            ?>
            <div class="panel"><?php render_cabecalho_painel('REGISTROS DO '.$escolhido, 'fa fa-check', true); ?>
                <div class="panel-content">
                    <?php $stmt = $p2->prepare('SELECT * FROM ferias WHERE id_usr = '.$_GET['id_usr'].' ORDER BY datainicio ASC');
                    $stmt->execute();
                    $reg3 = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    if(count($reg3) == 0) { ?>
                        Usuário não possui registro inicial de férias. Cadastre agora:
                        <form  id="validation2" action="ferias_cadastrar2.php" method="post">
                          <input type="hidden" name="id_usr" value="<?=$_GET['id_usr']?>">
                          <div class="row">
                            <div class="col-sm-12 col-lg-4 form-group">
                              <div class="form-group">
                                <label for="form-group" class="control-label">Ano referência:</label>
                                <div class="input-group">
                                  <span class="input-group-addon"><i class="fa fa-street-view"></i></span>
                                  <input id="anoref" name="anoref" class="form-control" id="aa" type="number" value="<?=date('Y')?>" required>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-12 col-lg-4 form-group">
                              <div class="form-group">
                                <label for="form-group" class="control-label">Data de inicio de direito:</label>
                                <div class="input-group">
                                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                  <input name="datainicio" id="datainicio" class="form-control" id="aa" type="date" required>
                                </div>
                              </div>
                            </div>
                        </div><hr><button class="btn btn-primary" href="#">CADASTRAR</button>
                        </form>
                        <?php
                    } else {
                    ?>
                  <table class="table table-bordered text-center">
                    <tr>
                        <td>Ano Ref</td>
                        <td>Tipo</td>
                        <td>Início</td>
                        <td>Apresentação</td>
                        <td>Dias gozados</td>
                        <td>Ação</td>
                    </tr>
                    <?php
                    $proxFerias = 0;
                    foreach($reg3 as $registro) {
                        echo '<tr>';
                        if($registro['tipo'] == 0) {
                            echo '<td>'.$registro['anoref'].'</td>';
                            echo '<td colspan="5"> Passa a ter direito de férias em '.date_converter2($registro['datainicio']).'</td>';
                            $proxFerias = $proxFerias > $registro['anoref'] ? $registro : $registro['anoref'];
                           
                        } else {
                            $tipo = 'Férias';
                            if($registro['tipo'] == 2) {
                                $tipo = 'Desconto em férias';
                            }
                            echo '<td>'.$registro['anoref'].'</td>';
                            echo '<td>'.$tipo.'</td>';
                            echo '<td>'.date_converter2($registro['datainicio']).'</td>';
                            echo '<td>'.date_converter2($registro['datafim']).'</td>';
                            $linkEdit = '<a href="ferias_cadastrar1.php?id_usr='.$_GET['id_usr'].'.&registry='.$registro['id'].'&action=editar" data-toggle="tooltip" title="Editar" class="color-secondary"><i class="fa fa-edit"></i></a> ';
                            $linkClose = '<a href="ferias_cadastrar5.php?id_usr='.$_GET['id_usr'].'.&registry='.$registro['id'].'&action=excluir" data-toggle="tooltip" title="Excluir" class="color-danger" onclick="'. "return confirm('Você tem certeza?')" .'"><i class="fa fa-close"></i></a> ';
                            
                            // $linkClose = '<a href="#" data-toggle="tooltip" title="Excluir lançamento" class="color-danger"><i class="fa fa-close"></i></a>';
                            if($registro['gozado'] == -1) {
                                $hoje = date('Y-m-d');
                                $diff = floor((strtotime($hoje) - strtotime($registro['datainicio']))/(60*60*24));
                                echo '<td>'.$diff.' (Não apresentado)</td>';
                                echo '<td>'.$linkEdit.$linkClose.'</td>';
                              } else if($registro['gozado'] == 1) {
                                $diff = floor((strtotime($registro['datafim']) - strtotime($registro['datainicio']))/(60*60*24));
                                echo '<td>'.$diff.' (gozado)</td>';
                                echo '<td>'.$linkEdit.$linkClose.'</td>';
                              } else {
                                echo '<td>Férias não gozadas!</td>';
                                echo '<td>'.$linkEdit.$linkClose.'</td>';
                            }
                        }
                        echo '</tr>';
                    }
                    ?>
                  </table>
                  <?php } ?>
                </div>
              </div>
            <?php } ?>
            <!-- </div>
          </div> -->
          <?php if(!isset($_GET['registry']) && isset($_GET['id_usr']) && count($reg3) != 0) { ?>
          <form id="validation3" action="ferias_cadastrar3.php?id_usr=<?=$_GET['id_usr']?>" method="post">
              <div class="panel"><?php render_cabecalho_painel('CADASTRAR PERÍODO OU DESCONTO DE FÉRIAS:', 'fa fa-edit', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-md-6 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Ano referência:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-list"></i></span>
                          <input class="form-control" value="<?=$proxFerias?>" type="number" name="anoref">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Tipo:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-question"></i></span>
                          <select name="tipo" class="form-control select select2-hidden-accessible" style="width: 100%" required="Preenchimento obrigatório" tabindex="-1" aria-hidden="true">
                            <option value="1">Férias</option> 
                            <option value="2">Desconto em Férias</option>  
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Data início:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                          <input class="form-control" id="datainicio" onchange="refreshFim();" type="date" name="datainicio" required>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Duração (dias):</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-car"></i></span>
                          <input class="form-control" id="duracao" onchange="refreshFim();" type="number" min="1" value="10" max="30" name="duracao" required>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-2 mb-sm">
                            <button type="button" class="btn btn-primary" onclick="$('#duracao').val(10); refreshFim(); return false">10 dias</button>
                            </div>
                            <div class="col-md-2 mb-sm">
                                    <button type="button" class="btn btn-primary" onclick="$('#duracao').val(15); refreshFim(); return false">15 dias</button>
                                    </div>
                        <div class="col-md-2 mb-sm">
                      <button type="button" class="btn btn-primary" onclick="$('#duracao').val(30); refreshFim(); return false">30 dias</button>
                    </div>
                    <div class="col-md-12 mb-sm">
                    </div>
                    <div class="col-md-3 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Data apresentação:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                          <input class="form-control" id="datafim" type="text" name="datafim" disabled>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 mb-sm">
                      <label for="form-check">Status</label>
                      <div class="form-check">
                        <input class="form-check-input" id="radio1" type="checkbox" name="iniciado">
                        <label for="radio1" class="form-check-label">Já iniciado</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" id="radio2" type="checkbox" name="finalizado">
                        <label for="radio2" class="form-check-label">Já finalizado</label>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <hr>
                      <button type="submit" name="action" value="Cadastrar" class="btn btn-primary" style="width: 140px;">
                        CADASTRAR
                      </button>
                    </div>
                  </div>
                </div>
              </div>
          </form>
          <?php } else if(isset($_GET['id_usr']) && count($reg3) != 0){ 
            $sql = 'SELECT * FROM ferias WHERE id = ' . $_GET['registry'];
            $stmt = $p2->prepare($sql);
            $stmt->execute();
            $reg4 = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
            ?>
            <form id="validation4" action="ferias_cadastrar4.php?id_usr=<?=$_GET['id_usr']?>&registry=<?=$_GET['registry']?>" method="post">
              <div class="panel"><?php render_cabecalho_painel('EDITAR PERÍODO OU DESCONTO DE FÉRIAS:', 'fa fa-edit', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-md-6 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Ano referência:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-list"></i></span>
                          <input class="form-control" type="number" value="<?=$reg4['anoref']?>" name="anoref" required>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Tipo:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-question"></i></span>
                          <select name="tipo" class="form-control select select2-hidden-accessible" style="width: 100%" required="Preenchimento obrigatório" tabindex="-1" aria-hidden="true">
                            <option value="1" <?=$reg4['tipo'] == 1 ? 'selected' : ''?>>Férias</option> 
                            <option value="2" <?=$reg4['tipo'] == 2 ? 'selected' : ''?>>Desconto em Férias</option>  
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Data início:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                          <input class="form-control" id="datainicio" onchange="refreshFim();" type="date" name="datainicio" value="<?=$reg4['datainicio']?>" required>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Data apresentação:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                          <input class="form-control" id="datafim" type="date" name="datafim" value="<?=$reg4['datafim']?>">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 mb-sm">
                      <label for="form-check">Status</label>
                      <div class="form-check">
                        <input class="form-check-input" id="radio1" type="checkbox" name="iniciado" <?=$reg4['gozado'] == -1 ? 'checked' : ''?>>
                        <label for="radio1" class="form-check-label">Já iniciado</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" id="radio2" type="checkbox" name="finalizado" <?=$reg4['gozado'] == 1 ? 'checked' : ''?>>
                        <label for="radio2" class="form-check-label">Já finalizado</label>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <hr>
                      <button type="submit" name="action" value="Editar" class="btn btn-primary" style="width: 140px;">EDITAR</button>
                    </div>
                  </div>
                </div>
              </div>
          </form>
            <?php } ?>
        </div>
          <?php include '../recursos/views/token.php'; ?>
      </div>
      <?php include '../recursos/views/scroll_to_top.php'; ?>
    </div>
  </div>
  <?php include '../recursos/views/footer.php'; ?>
  <script>
    function refreshFim(){
        //script para atualizar a data de retorno
      var ini = new Date($('#datainicio').val());
      var dur = $('#duracao').val();
      
      ini.setDate(ini.getDate() + parseInt(dur)+1);

      $('#datafim').val(
        (ini.getDate() < 10 ? '0'+ini.getDate() : ini.getDate()) +'/'+
        ((ini.getMonth()+1) < 10 ? '0'+(ini.getMonth()+1) : (ini.getMonth()+1)) +'/'+
        ini.getFullYear()
      );
 
      saveFormData();
    }

    //obter dados iniciais do formulário
        $('#datainicio').val(window.localStorage.getItem('datainicio') ?? '');
        $('#duracao').val(window.localStorage.getItem('duracao') ?? 10);
        refreshFim();
    
    //salvar dados do formulário para próximos cadastros
    function saveFormData() {
        
        window.localStorage.setItem('datainicio', $('#datainicio').val().getDate());
        window.localStorage.setItem('duracao', $('#duracao').val());
    }
  </script>
</body>

</html>