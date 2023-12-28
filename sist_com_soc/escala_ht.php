<?php
/*
Esse sistema facilita a inserção de informações relativas à omunicação social da om
É dividido nos seguintes módulos:
    - Hotel de Trânsito (Reservas, pronto, permanências)
    - Contatos da Com Soc (falta iniciar)
    - OMs do Cmdo Mil A e outras de interesse do Cmt (falta iniciar)
    - 

*/
include "../recursos/models/conexao.php";
require "../recursos/models/versession.php";
if ($_SESSION['nivel_com_soc'] == "Sem Acesso") {
    $msgerro = base64_encode('Usuário não possui acesso!');
    header('Location: ../sistemas/index.php?token=' . $msgerro);
    exit();
}
// conectar os banco de dados necessários
$pdo1 = conectar("membros");
$pdo2 = conectar("sistcomsoc");

$consulta1 = $pdo1->prepare('SELECT id, idpgrad, nomeguerra, celular FROM usuarios ORDER BY idpgrad');
$consulta1->execute();
$reg1 = $consulta1->fetchAll(PDO::FETCH_ASSOC); 

$consulta2 = $pdo2->prepare('SELECT * FROM escala_permanencia ORDER BY id DESC LIMIT 30');
$consulta2->execute();
$reg2 = $consulta2->fetchAll(PDO::FETCH_ASSOC); 
?> 
<!doctype html>
<html lang="pt-BR" class="fixed">
    
    <head>
        <?php include '../recursos/views/cabecalho.php'; ?>
    </head>
    
    <body>
        <div class="wrap">
            <div class="page-header">
            <?php render_painel_usu('SERVIÇOS COM SOC', $_SESSION['nivel_com_soc']); ?>
    </div>
    <div class="page-body">
      <div class="left-sidebar">
          <?php include 'menu_opc.php'; ?>
      </div>
      <div class="content">
      <?php render_content_header('Permenência ao HT', 'fa fa-home'); ?>
        <div class="row animated zoomInDown">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-4">
            <!-- lista o dia anterior, o atual e os próximos 7 dias, permitindo escolher o permanencia do HT (não permite para o dia anterior) -->
            <div class="panel widgetbox wbox-2 bg-scale-0">
                <div class="panel-content color-primary">
                    <a data-toggle="tooltip" data-placement="top" title="Escala ontem">
                        <div class="row">
                        <div class="col-xs-2">
                            <span class="icon fa fa-calendar-day"></span>
                        </div>
                        <div class="col-xs-10">
                            <h4 class="title">Escala ontem</h4>
                            <h4 class="subtitle text-left"><b>Escalado: </b>
                            <?php
                            foreach($reg2 as $escalas){
                                if($escalas['date'] == date('Y-m-d', strtotime('-1 day'))) {
                                    foreach($reg1 as $militar){
                                        if($militar['id'] == $escalas['id_perm']) {
                                            print($militar['nomeguerra'].'</h4>');
                                            print('<h4 class="subtitle text-left"><b>Telefone:</b> '.$militar['celular']);
                                            break;
                                        }
                                        
                                    }
                                    break;
                                }
                            }
                            ?>
                        </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="panel widgetbox wbox-2 bg-scale-0">
            <div class="panel-content color-primary">
              <a data-toggle="tooltip" data-placement="top" title="Escala hoje">
                <div class="row">
                  <div class="col-xs-2">
                    <span class="icon fa fa-calendar-day"></span>
                  </div>
                  <div class="col-xs-10">
                    <h4 class="title">Escala hoje</h4>
                    <h4 class="subtitle text-left"><b>Escalado:</b>
                    <?php
                            foreach($reg2 as $escalas){
                                if($escalas['date'] == date('Y-m-d', strtotime('+0 day'))) {
                                    foreach($reg1 as $militar){
                                        if($militar['id'] == $escalas['id_perm']) {
                                            print($militar['nomeguerra'].'</h4>');
                                            print('<h4 class="subtitle text-left"><b>Telefone:</b> '.$militar['celular']);
                                            break;
                                        }
                                        
                                    }
                                    break;
                                }
                            }
                            ?>
                </div>
                </div>
              </a>
            </div>
          </div>
            <div class="panel widgetbox wbox-2 bg-scale-0">
            <div class="panel-content color-primary">
              <a data-toggle="tooltip" data-placement="top" title="Escala amanhã">
                <div class="row">
                  <div class="col-xs-2">
                    <span class="icon fa fa-calendar-day"></span>
                  </div>
                  <div class="col-xs-10">
                    <h4 class="title">Escala amanhã</h4>
                    <h4 class="subtitle text-left"><b>Escalado:</b>
                    <?php
                            foreach($reg2 as $escalas){
                                if($escalas['date'] == date('Y-m-d', strtotime('+1 day'))) {
                                    foreach($reg1 as $militar){
                                        if($militar['id'] == $escalas['id_perm']) {
                                            print($militar['nomeguerra'].'</h4>');
                                            print('<h4 class="subtitle text-left"><b>Telefone:</b> '.$militar['celular']);
                                            break;
                                        }
                                        
                                    }
                                    break;
                                }
                            }
                            ?>
                  </div>
                </div>
              </a>
            </div>
          </div>
          <div class="panel widgetbox wbox-2 bg-scale-0">
            <div class="panel-content color-primary">
              <a data-toggle="tooltip" data-placement="top" title="Escala <?=date('d/m', strtotime('+2 day'))?>">
                <div class="row">
                  <div class="col-xs-2">
                    <span class="icon fa fa-calendar-day"></span>
                  </div>
                  <div class="col-xs-10">
                    <h4 class="title">Escala <?=date('d/m', strtotime('+2 day'))?></h4>
                    <h4 class="subtitle text-left"><b>Escalado:</b>
                    <?php
                            foreach($reg2 as $escalas){
                                if($escalas['date'] == date('Y-m-d', strtotime('+2 day'))) {
                                    foreach($reg1 as $militar){
                                        if($militar['id'] == $escalas['id_perm']) {
                                            print($militar['nomeguerra'].'</h4>');
                                            print('<h4 class="subtitle text-left"><b>Telefone:</b> '.$militar['celular']);
                                            break;
                                        }
                                        
                                    }
                                    break;
                                }
                            }
                            ?>
                  </div>
                </div>
              </a>
            </div>
          </div>
          </div>
          <div class="col-sm-12 col-md-4">
          <div class="panel widgetbox wbox-2 bg-scale-0">
            <div class="panel-content color-primary">
              <a data-toggle="tooltip" data-placement="top" title="Escala <?=date('d/m', strtotime('+3 day'))?>">
                <div class="row">
                  <div class="col-xs-2">
                    <span class="icon fa fa-calendar-day"></span>
                  </div>
                  <div class="col-xs-10">
                    <h4 class="title">Escala <?=date('d/m', strtotime('+3 day'))?></h4>
                    <h4 class="subtitle text-left"><b>Escalado:</b>
                    <?php
                            foreach($reg2 as $escalas){
                                if($escalas['date'] == date('Y-m-d', strtotime('+3 day'))) {
                                    foreach($reg1 as $militar){
                                        if($militar['id'] == $escalas['id_perm']) {
                                            print($militar['nomeguerra'].'</h4>');
                                            print('<h4 class="subtitle text-left"><b>Telefone:</b> '.$militar['celular']);
                                            break;
                                        }
                                        
                                    }
                                    break;
                                }
                            }
                            ?>
                  </div>
                </div>
              </a>
            </div>
          </div>
          <div class="panel widgetbox wbox-2 bg-scale-0">
            <div class="panel-content color-primary">
              <a data-toggle="tooltip" data-placement="top" title="Escala <?=date('d/m', strtotime('+4 day'))?>">
                <div class="row">
                  <div class="col-xs-2">
                    <span class="icon fa fa-calendar-day"></span>
                  </div>
                  <div class="col-xs-10">
                    <h4 class="title">Escala <?=date('d/m', strtotime('+4 day'))?></h4>
                    <h4 class="subtitle text-left"><b>Escalado:</b>
                    <?php
                            foreach($reg2 as $escalas){
                                if($escalas['date'] == date('Y-m-d', strtotime('+4 day'))) {
                                    foreach($reg1 as $militar){
                                        if($militar['id'] == $escalas['id_perm']) {
                                            print($militar['nomeguerra'].'</h4>');
                                            print('<h4 class="subtitle text-left"><b>Telefone:</b> '.$militar['celular']);
                                            break;
                                        }
                                        
                                    }
                                    break;
                                }
                            }
                            ?>
                  </div>
                </div>
              </a>
            </div>
          </div>
          <div class="panel widgetbox wbox-2 bg-scale-0">
            <div class="panel-content color-primary">
              <a data-toggle="tooltip" data-placement="top" title="Escala <?=date('d/m', strtotime('+5 day'))?>">
                <div class="row">
                  <div class="col-xs-2">
                    <span class="icon fa fa-calendar-day"></span>
                  </div>
                  <div class="col-xs-10">
                    <h4 class="title">Escala <?=date('d/m', strtotime('+5 day'))?></h4>
                    <h4 class="subtitle text-left"><b>Escalado:</b>
                    <?php
                        foreach($reg2 as $escalas){
                            if($escalas['date'] == date('Y-m-d', strtotime('+5 day'))) {
                                foreach($reg1 as $militar){
                                    if($militar['id'] == $escalas['id_perm']) {
                                        print($militar['nomeguerra'].'</h4>');
                                        print('<h4 class="subtitle text-left"><b>Telefone:</b> '.$militar['celular']);
                                        break;
                                    }   
                                }
                                break;
                            }
                        }
                        ?>
                  </div>
                </div>
              </a>
            </div>
          </div>
          <div class="panel widgetbox wbox-2 bg-scale-0">
            <div class="panel-content color-primary">
              <a data-toggle="tooltip" data-placement="top" title="Escala <?=date('d/m', strtotime('+6 day'))?>">
                <div class="row">
                  <div class="col-xs-2">
                    <span class="icon fa fa-calendar-day"></span>
                  </div>
                  <div class="col-xs-10">
                    <h4 class="title">Escala <?=date('d/m', strtotime('+6 day'))?></h4>
                    <h4 class="subtitle text-left"><b>Escalado:</b>
                    <?php foreach($reg2 as $escalas){
                        if($escalas['date'] == date('Y-m-d', strtotime('+6 day'))) {
                            foreach($reg1 as $militar){
                                if($militar['id'] == $escalas['id_perm']) {
                                    echo $militar['nomeguerra'].'</h4>';
                                    echo '<h4 class="subtitle text-left"><b>Telefone:</b> '.$militar['celular'];
                                    break;
                                }
                            }
                            break;
                        }
                    } ?>
                  </div>
                </div>
              </a>
            </div>
          </div>
          </div>
          <div class="col-sm-12 col-md-4">
          <form id="validation" action="edit_escala.php" method="post">
              <div class="panel"><?php render_cabecalho_painel('ESCALAR PERMANÊNCIA', 'fas fa-street-view', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-md-12 mb-sm"><?php render_militar_ativo_select('id_perm', 'select2-example-basic', true, false); ?></div>
                    <div class="col-md-12 mb-sm"><?php render_data_field('date', true, 'Data serviço:', null) ?></div>
                    <div class="col-md-12">
                      <hr>
                      <button type="submit" name="action" value="escalar" class="btn btn-primary" style="width: 140px;">ESCALAR</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
        <?php include '../recursos/views/scroll_to_top.php'; ?>
    </div>
  </div>
  <?php include '../recursos/views/footer.php'; ?>
</body>
</html>