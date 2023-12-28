<?php

include "../recursos/models/conexao.php";
require "../recursos/models/versession.php";
if ($_SESSION['nivel_com_soc'] == "Sem Acesso") {
    $msgerro = base64_encode('Usuário não possui acesso!');
    header('Location: ../sistemas/index.php?token=' . $msgerro);
    exit();
}
// conectar os banco de dados necessários
$pdo1 = conectar("membros");
$pdo2 = conectar("guarda");
$pdo3 = conectar("sistcomsoc");
$cadastros;
// obter da tabela usuarios o id, nome completo e idpgrad dos militares da OM
$consulta1 = $pdo1->prepare('SELECT id, nomecompleto, idpgrad, nomeguerra from usuarios order by idpgrad');
$consulta1->execute();
$reg1 = $consulta1->fetchAll(PDO::FETCH_BOTH); 

// // obter da tabela visitante o id, nome completo e tipo dos visitantes e militades de outra OM
$consulta2 = $pdo2->prepare('SELECT id, nomecompleto, tipo from visitante order by tipo');
$consulta2->execute();
$reg2 = $consulta2->fetchAll(PDO::FETCH_BOTH);

$consulta2 = $pdo3->prepare('SELECT * FROM escala_permanencia ORDER BY id DESC LIMIT 30');
$consulta2->execute();
$reg4 = $consulta2->fetchAll(PDO::FETCH_ASSOC);

$consultan = $pdo3->prepare('SELECT * FROM conformidade ORDER BY id DESC LIMIT 1');
$consultan->execute();
$reg5 = $consultan->fetchAll(PDO::FETCH_ASSOC); 

$sistema = base64_encode("SERVIÇOS COM SOC");

$idmembro = $_SESSION['auth_data']['id'];
$data = date("d/m/Y");

            $estemes = date('m/Y');
            $messeguinte = date('m/Y', strtotime('+1 month'));
            $consulta3 = $pdo3->prepare('SELECT * FROM reservas WHERE data_checkout LIKE "%'.date('m/Y').'" OR 
            data_checkout LIKE "%'.date('m/Y', strtotime('+1 month')).'" ORDER BY id DESC');
            $consulta3->execute();
            $reg3 = $consulta3->fetchAll(PDO::FETCH_ASSOC);
            $diaria2 = date("Ymd", time());
            $texto_ht1 = 'Sem hóspede';
            $texto_ht2 = 'Sem hóspede';
            $texto_ht3 = 'Sem hóspede';
            foreach($reg3 as $registro) {
              $prep_data_checkin = $registro['data_checkin'][6].$registro['data_checkin'][7].$registro['data_checkin'][8].$registro['data_checkin'][9].$registro['data_checkin'][3].$registro['data_checkin'][4].$registro['data_checkin'][0].$registro['data_checkin'][1];
              $prep_data_checkout = $registro['data_checkout'][6].$registro['data_checkout'][7].$registro['data_checkout'][8].$registro['data_checkout'][9].$registro['data_checkout'][3].$registro['data_checkout'][4].$registro['data_checkout'][0].$registro['data_checkout'][1];
              if(intval($diaria2) < intval($prep_data_checkout) && intval($diaria2) >= intval($prep_data_checkin)) {
                  if($registro['quarto'] == 'HT1') {
                    if($registro['id_hospede'] > 0) {
                      foreach($reg1 as $chave) {
                        if($chave['id'] == $registro['id_hospede']) {
                          $texto_ht1 = getPGrad($chave["idpgrad"]) . ' '. $chave["nomecompleto"] . ' ('. $chave["nomeguerra"] . ')';
                          break;
                        }
                      }
                    } else {
                      foreach($reg2 as $chave) {
                        $registro['id_hospede'] = str_replace('-', '', $registro['id_hospede']);
                        if($chave['id'] == $registro['id_hospede']) {
                          $texto_ht1 = $chave["nomecompleto"]. ' (' . $chave["tipo"] . ')';
                          break;
                        }
                      }
                    }
                  } else if($registro['quarto'] == 'HT2') {
                    if($registro['id_hospede'] > 0) {
                      foreach($reg1 as $chave) {
                        if($chave['id'] == $registro['id_hospede']) {
                          $texto_ht2 = getPGrad($chave["idpgrad"]) . ' '. $chave["nomecompleto"] . ' ('. $chave["nomeguerra"] . ')';
                          break;
                        }
                      }
                    } else {
                      foreach($reg2 as $chave) {
                        $registro['id_hospede'] = str_replace('-', '', $registro['id_hospede']);
                        if($chave['id'] == $registro['id_hospede']) {
                          $texto_ht2 = $chave["nomecompleto"]. ' (' . $chave["tipo"] . ')';
                          break;
                        }
                      }
                    }
                  } else if($registro['quarto'] == 'HT3') {
                    if($registro['id_hospede'] > 0) {
                      foreach($reg1 as $chave) {
                        if($chave['id'] == $registro['id_hospede']) {
                          $texto_ht3 = getPGrad($chave["idpgrad"]) . ' '. $chave["nomecompleto"] . ' ('. $chave["nomeguerra"] . ')';
                          break;
                        }
                      }
                      
                    } else {
                      foreach($reg2 as $chave) {
                        
                        $registro['id_hospede'] = str_replace('-', '', $registro['id_hospede']);
                        if($chave['id'] == $registro['id_hospede']) {
                          $texto_ht3 = $chave["nomecompleto"]. ' (' . $chave["tipo"] . ')';
                          break;
                        }
                      }
                    }
                  }
              }
            }   
            $ontem = 'Não escalado';
            $hoje = 'Não escalado';
            $amanha = 'Não escalado';
                    foreach($reg4 as $escalas){
                        if($escalas['date'] == date('Y-m-d', strtotime('-1 day'))) {
                            foreach($reg1 as $militar){
                                if($militar['id'] == $escalas['id_perm']) {
                                    $ontem = $militar['nomeguerra'];
                                    break;
                                }
                            }
                        } else if($escalas['date'] == date('Y-m-d', strtotime('+0 day'))) {
                            foreach($reg1 as $militar){
                                if($militar['id'] == $escalas['id_perm']) {
                                    $hoje = $militar['nomeguerra'];
                                    break;
                                }
                            }
                        } if($escalas['date'] == date('Y-m-d', strtotime('+1 day'))) {
                            foreach($reg1 as $militar){
                                if($militar['id'] == $escalas['id_perm']) {
                                    $amanha = $militar['nomeguerra'];
                                    break;
                                }
                            }
                        }
                    }
                    $conf1 = 'Não registrado hoje';
                    $conf2 = 'Não registrado hoje';
                        if(count($reg5) == 0 || date('Y-m-d', strtotime($reg5[0]['date'])) < date('Y-m-d')) {
                        } else { 
                            if($reg5[0]['status_ug1'] == 'S') {
                                $conf1 = 'Sem restrição';
                            } else if($reg5[0]['status_ug1'] == 'C') {
                                $conf1 = 'Com restrição';
                            }
                            if($reg5[0]['status_ug2'] == 'S') {
                                $conf2 = 'Sem restrição';
                            } else if($reg5[0]['status_ug2'] == 'S') {
                                $conf2 = 'Sem restrição';
                            }
                        }
            
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
      <?php render_content_header('Painel de Controle', 'fa fa-home'); ?>
        <div class="row animated zoomInDown">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-4">
          <?php render_card1(
              'gerenciar_visitantes.php', 'Gerenciar Visitantes', 'icon fa fa-person', 
              '<h4 class="title">Visitantes<br> </h4>
              <h4 class="subtitle text-left" style="padding-top:4px;">Gerencie informações de visitantes aqui</h4>'); ?>

          </div>
          <div class="col-sm-12 col-md-4">          
            <?php render_card1(
              'hospedes_ht.php', 'Controle de Hóspedes no HT', 'icon fa fa-book', 
              '<h4 class="title">Hóspedes<br> </h4>
              <h4 class="subtitle text-left" style="padding-top:4px;"><b>HT01: </b>'. $texto_ht1 .'</h4>
              <h4 class="subtitle text-left" style="padding-top:4px;"><b>HT02: </b>'. $texto_ht2 .'</h4>
              <h4 class="subtitle text-left" style="padding-top:4px;"><b>HT03: </b>'. $texto_ht3 .'</h4>'); ?>
            
            <?php render_card1(
              'escala_ht.php', 'HT - Permanência', 'icon fa fa-check', 
              '<h4 class="title">Permanência<br> </h4>
              <h4 class="subtitle text-left">Ontem: '. $ontem .'</h4>
              <h4 class="subtitle text-left" style="padding-top:4px;"><b>Hoje: '. $hoje .'</b></h4>
              <h4 class="subtitle text-left" style="padding-top:4px;">Amanhã: '. $amanha .'</h4>'); ?>
            
            <?php render_card1(
              'conformidade.php', 'Conformidade', 'icon fa fa-search', 
              '<h4 class="title">Conformidade<br> </h4>
              <h4 class="subtitle text-left" style="padding-top:4px;">160521: '. $conf1 .'</h4>
              <h4 class="subtitle text-left" style="padding-top:4px;">167521: '. $conf2 .'</h4>'); ?>

          </div>
          <div class="col-sm-12 col-md-4">
          </div>
        </div>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
        </div>
      </div>
        <?php include '../recursos/views/scroll_to_top.php'; ?>
    </div>
  </div>
  <?php include '../recursos/views/footer.php'; ?>
</body>
</html>