<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

$p1 = conectar("membros");
$p2 = conectar("agenda");

$idmembro = $_SESSION['auth_data']['id'];
$conslogin = $p1->prepare("SELECT data, idmembro, id FROM logins WHERE idmembro = $idmembro ORDER BY id DESC");
$conslogin->execute();
$login = $conslogin->fetchAll(PDO::FETCH_ASSOC);
$totlogin = count($login);
if ($totlogin > 0) {
  $dadoslogin = $login[0];
}

// Obter eventos na agenda para hoje e próximos 6 dias
$d0 = date('Y-m-d');
$d6 = date('Y-m-d', strtotime($d0 . ' + 6 days'));
$sql = 'SELECT * FROM evento WHERE 
       (datahorainicio BETWEEN "' . $d0 . '" AND "' . $d6 . '") OR (datahorafim BETWEEN "' . $d0 . '" AND "' . $d6 . '")
       ORDER BY datahorafim ASC';
$stmt = $p2->prepare($sql);
$stmt->execute();
$evts = $stmt->fetchAll(PDO::FETCH_ASSOC);
$eventos = [];
foreach($evts as $evt) {
    $viz = unserialize($evt['viz']);
    if(in_array($_SESSION['auth_data']['id'] ,$viz) || $evt['autor'] == $_SESSION['auth_data']['id'] || in_array('TODOS DA OM' ,$viz)) {
        array_push($eventos, $evt);
    }
} ?>
<!doctype html>
<html lang="pt-BR" class="fixed">
<head><?php include '../recursos/views/cabecalho.php'; ?></head>
<body>
  <div class="wrap">
    <div class="page-header"><?php render_painel_usu('SISTEMAS INTEGRADOS'); ?></div>
    <div class="page-body">
      <div class="left-sidebar">
        <?php include 'menu_opc.php'; ?>
      </div>
      <div class="content">
        <?php render_content_header('Painel Inicial', 'fa fa-home'); ?>
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <?php include '../recursos/views/token.php'; ?>
            </div>
            <div class="col-sm-12 col-md-12">
                <div class="col-sm-12 col-lg-4 animated fadeInRightBig">
                <!-- <h2>Perfil</h2> -->
                <?php render_card1('../controledepessoal/cad_usu_indiv.php?tkusr='.base64_encode($_SESSION['auth_data']['id']), 'Clique aqui para atualizar seus dados', 'icon fas fa-user', 
                '<h4 class="subtitle"><b>Mantenha seus dados atualizados</b></h4>
                <h4 class="subtitle"><b>Última atualização: </b>'. (date_converter2($_SESSION['auth_data']['ult_atlz_dados']) ?? 'Sem registro') .'</h4>
                <h1 class="title" style="font-size: 19px;">Atualizar dados</h1>'); ?>

                <?php render_card1('../controledepessoal/cad_usu_indiv_senha.php?tkusr='.base64_encode($_SESSION['auth_data']['id']), 'Clique aqui para atualizar sua senha', 'icon fas fa-key', 
                '<h4 class="subtitle"><b>Sua senha de acesso é <u>'. $_SESSION['auth_data']['str'] .'</u></b></h4>
                <h4 class="subtitle"><b>Última atualização: </b>'. (date_converter2($_SESSION['auth_data']['ult_troca_senha']) ?? 'Sem registro') .'</h4>
                <h1 class="title" style="font-size: 19px;">Atualizar senha</h1>'); ?>

                <?php render_card1('../controledepessoal/cad_usu_indiv_senha.php?tkusr="' . base64_encode($_SESSION['auth_data']['id']), 'Atualizar Senha',  'icon fa fa-user-secret', 
                '<h4 class="subtitle">Meu Último Acesso:<b>'. ($dadoslogin['data']).'</b></h4>
                <h4 class="subtitle" style="padding-top:4px;">Meu Total de Acessos:<b>'. $totlogin .'</b></h4>
                <h4 class="subtitle" style="padding-top:4px;">Meu IP:<b>'. $_SESSION['user_ip'] .'</b></h4>'); ?>
              
                <?php render_card2(
                  '', '', 'icon fa fa-file-pdf', 
                  '<h4 class="subtitle">Consulta de Boletim Interno:</h4>
                  <select id="select-bi" name="bi" class="form-control"></select><hr>
                  <button class="btn btn-primary" id="btn-bi">Exibir</button>', 
                  '', ''
                ); ?>
                <?php if($_SESSION['nivel_helpdesk'] == 'Administrador') {          
                render_card1('gerar_bkp.php', 'Clique aqui para gerar backup do código fonte', 'icon fas fa-code', 
                '<h4 class="subtitle"><b>Mantenha o código fonte e o banco de dados protegidos</b></h4>
                <h1 class="title" style="font-size: 19px;">Fazer Backup</h1>'); 

                render_card1('#', 'Se a hora divergir, corrija o servidor', 'icon fa fa-clock', 
                '<h4 class="subtitle">A Data e Hora do servidor podem estar desatualizadas</h4>
                <h1 class="title" style="font-size: 19px;">'.date('d/m/Y h:i').'</h1>
                <h4 id="datahoralocal" class="subtitle">Data e hora local: </h4>'); 
                
                } ?>
            </div>
            <div class="col-sm-12 col-lg-4 animated fadeInRightBig">

            <div class="panel"><?php render_cabecalho_painel('ANIVERSARIANTES DO MÊS', 'fas fa-birthday-cake', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-md-12 mb-sm">
                        <table class="table table-hover text-center">
                            <?php 
                                $mesatual = date("m");
                                $sql = "SELECT * FROM usuarios WHERE userativo = 'S' AND month(datanascimento2) = $mesatual ORDER BY datanascimento ASC";
                                $stmt = $p1->prepare($sql);
                                $stmt->execute();
                                $aniversarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                $su = listar_subunidades();
                                foreach($aniversarios as $aniv) { ?>
                                <tr  class="font-weight-light small<?=substr($aniv['datanascimento'],0,2)==date('d') ? ' bg-success' : ''?>">
                                <td><?=substr($aniv['datanascimento'], 0, 5)?></td>
                                <td><?=getPGrad($aniv['idpgrad']).' '.$aniv['nomeguerra']?></td>
                                <td><?=$su[($aniv['idsubunidade'] - 1)]['descricao']?></td>
                            </tr>
                            <?php } ?>
                        </table>
                        <a href="../sist_com_soc/militares_aniversariantes.php" class="btn btn-primary">Mais Opções</a>
                    </div>
                  </div>
                </div>
            </div>
            </div>
            <div class="col-sm-12 col-lg-4 animated fadeInRightBig">
            <div class="panel"><?php render_cabecalho_painel('PRÓXIMOS EVENTOS', 'fas fa-calendar', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-md-12 mb-sm">
                      <div class="form-group">
                        <table class="table table-hover text-center">
                            <tr><th>Título</th><th>Anexo</th><th>Início</th><th>Autor</th></tr>
                            <?php foreach($eventos as $evento) {
                                $stmt = $p1->prepare('SELECT id, idpgrad, nomeguerra FROM usuarios WHERE id = ' . $evento['autor']);
                                $stmt->execute();
                                $autor = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
                                echo '<tr>';
                                    echo '<td><a data-toggle="tooltip" data-placement="top" href="#" title="'.$evento['descricao'].'">'.$evento['titulo'].'</a></td>';
                                    echo '<td>'. ( $evento['anexo'] == '' ? '-' : '<a href="../agenda/'.$evento['anexo'].'"><i class="fas fa-paperclip"></i></a>' ) .'</td>';
                                    echo '<td>'.$evento['datahorainicio'][8].$evento['datahorainicio'][9].'/'.$evento['datahorainicio'][5].$evento['datahorainicio'][6].' '.$evento['datahorainicio'][11].$evento['datahorainicio'][12].':'.$evento['datahorainicio'][14].$evento['datahorainicio'][15].'</td>';
                                    //echo '<td>'.$evento['datahorafim'][8].$evento['datahorafim'][9].'/'.$evento['datahorafim'][5].$evento['datahorafim'][6].' '.$evento['datahorafim'][11].$evento['datahorafim'][12].':'.$evento['datahorafim'][14].$evento['datahorafim'][15].'</td>';
                                    echo '<td>'.ImprimeConsultaMilitar2($autor).'</td>';
                                echo '</tr>';
                            } ?>
                        </table>
                        <a href="../agenda" class="btn btn-primary">Ver Agenda completa</a>
                      </div>
                    </div>
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
  <script>
    console.log('<?= strtotime($_SESSION['auth_data']['ult_atlz_dados'] ?? '0000-00-00') < strtotime('-30 days') ?>');
    <?php if($_SESSION['auth_data']['str'] != 'FORTE') { ?>
    alert("Foi detectado que a sua senha de acesso é FRACA!\nA partir de hoje senhas fortes serão obrigatórias. Troque ela imediatamente.");
    document.location="../controledepessoal/cad_usu_indiv_senha.php?tkusr=<?=base64_encode($_SESSION['auth_data']['id'])?>";
    <?php } else if(strtotime($_SESSION['auth_data']['ult_atlz_dados'] ?? '0000-00-00') < strtotime('-30 days')) { ?>
        alert("Faz tempo que o Senhor não atualiza seus dados do plano de chamada!\nAtualize já.");
        document.location="../controledepessoal/cad_usu_indiv.php?tkusr=<?=base64_encode($_SESSION['auth_data']['id'])?>";
        
    <?php 
        // Ainda não é hora de comprar briga com a tropa  XD
        // Esse código obriga o usuário a trocar sua senha dentro do numero de dias definido na linha abaixo
        //} else if(strtotime($_SESSION['auth_data']['ult_troca_senha'] ?? '0000-00-00') < strtotime('-60 days')) { ?>
        //alert("Faz tempo que o Senhor não atualiza sua senha!\nAtualize já.");
        //document.location="../controledepessoal/cad_usu_indiv_senha.php?tkusr=<?=base64_encode($_SESSION['auth_data']['id'])?>";
    <?php } ?>
    
  </script>
  <script>
    // farei a requisição para obter a lista de boletins e aditamentos
    $.get('<?=SISBOL_URL?>classes/scan_bi_folder.php', function(data) {
        let bols =  JSON.parse(data);
        //agora filtrarei tudo o que é bi
        let filt_bols = bols.filter((file_name) => file_name.includes("_boletim_interno.pdf"));
        //agora busco somente o última para exibir no card "último bi"
        let ultimo_bi = filt_bols.slice(-1)[0];
        //agora atribuo os valores ao respectivo local
        $('#ult_bi_url').attr('href', '<?=SISBOL_URL?>boletim/' + ultimo_bi);
        //insiro as opções no select dos boletins:
        for (var i=filt_bols.length-1;i>=0;i--) {
            var value = filt_bols[i].replace("_boletim_interno.pdf", "").replace("_O_", ", BI ");
            $("#select-bi").append('<option value="'+ filt_bols[i] +'">' + value + '</option>');
        }
        //implemento a função ao clicar no boletim:
            $("#btn-bi").click(function (){
                var pdf = $("#select-bi").val();
                var url = '<?=SISBOL_URL?>boletim/' + pdf;
                window.open(url);
            });
    })
  </script>
    <?php  if($_SESSION['nivel_helpdesk'] == 'Administrador') { ?>
      <script>
      function gerarBackup() {
        $.ajax({ url: '/backup.php', method: 'POST',
          success: function(){
            alert('Backup gerado com sucesso!');
          }
        });
      }
      </script>
      <script>$("#datahoralocal").text("Data e hora local: " + Date().toLocaleString());</script>
    <?php } ?>
</body>
</html>