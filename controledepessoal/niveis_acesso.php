<?php
// nessa tela posso gerenciar níveis de acesso dos usuários

require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if ($_SESSION['auth_data']['contahd'] < "3") {
    header('Location: index.php');
    exit();
  }

$p1 = conectar("membros");


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
      <?php render_content_header('Níveis de Acesso', 'fa fa-users-cog'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          
          <form id="inline_validation" action="conf_usu_novo.php" method="post">
            <div class="col-sm-12 col-md-12">
              <div class="panel"><?php render_cabecalho_painel('USUÁRIOS ATIVOS:', 'fa fa-user-plus', true); ?>
                <div class="panel-content" >
                  <table class="tabela table text-center table-sortable">
                    <thead>
                    <tr>
                        <th>Militar</th>
                        <th>Acesso Rancho</th>
                        <th>Acesso Fatos Observados</th>
                        <th>Acesso Guarda</th>
                        <th>Acesso Helpdesk</th>
                        <th>Acesso Controle Pessoal</th>
                        <th>Acesso SisCautela</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $stmt = $p1->prepare('SELECT * FROM usuarios WHERE userativo = "S" ORDER BY idpgrad, nomeguerra');
                    $stmt->execute();
                    $usrs = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($usrs as $usr) {
                        //arranchamento

                    if ($usr['acessorancho'] == "S") {

                      if ($usr['contarancho'] == "1") {
                        $res['nivel_arranchamento'] = "Usuário comum";
                      } else if ($usr['contarancho'] == "2") {
                        $res['nivel_arranchamento'] = "Furriel";
                      } else if ($usr['contarancho'] == "3") {
                        $res['nivel_arranchamento'] = "Aprovisionador";
                      } else if ($usr['contarancho'] == "4") {
                        $res['nivel_arranchamento'] = "Administrador";
                      } else {
                        $res['nivel_arranchamento'] = "Sem Acesso";
                      }
                    } else {
                      $res['nivel_arranchamento'] = "Sem Acesso";

                    }
                        //fatos observados

                    if ($usr['idpgrad'] <= 12) {
                      $res['nivel_fatos_observados'] = "Usuário comum";
                    } else {
                      $res['nivel_fatos_observados'] = "Sem Acesso";
                    }
                    
                        //guarda

                    if ($usr['acessoguarda'] == "S") {
                      if (($usr['contaguarda'] == "1" || $usr['contaguarda'] == "2")) {
                        $res['nivel_guarda'] = "Anotador";
                      } else if ($usr['contaguarda'] == "2") {
                        $res['nivel_guarda'] = "Cabo Gda";
                      } else  if ($usr['contaguarda'] == "3") {
                        $res['nivel_guarda'] = "Oficial e Sargento";
                      } else if ($usr['contaguarda'] == "4") {
                        $res['nivel_guarda'] = "Supervisor";
                      } else if ($usr['contaguarda'] == "5") {
                        $res['nivel_guarda'] = "Administrador";
                      } else {
                        $res['nivel_guarda'] = "Sem Acesso";
                      }
                    } else {
                      $res['nivel_guarda'] = "Sem Acesso";

                    }
                    
                        // helpdesk

                    if ($usr['acessohd'] == "S") {

                      if ($usr['contahd'] == "1") {
                        $res['nivel_helpdesk'] = "Usuário Comum";
                      } else if ($usr['contahd'] == "2") {
                        $res['nivel_helpdesk'] = "Supervisor";
                      } else  if ($usr['contahd'] == "3") {
                        $res['nivel_helpdesk'] = "Administrador";
                      } else {
                        $res['nivel_helpdesk'] = "Sem Acesso";
                      }
                    } else {
                      $res['nivel_helpdesk'] = "Sem Acesso";
                    }

                    // plano de chamada
                    if ($usr['acessopchamada'] == "S") {
                      if ($usr['contapchamada'] == "1") {
                        $res['nivel_plano_chamada'] = "Usuário comum";
                      } else if ($usr['contapchamada'] == "2") {
                        $res['nivel_plano_chamada'] = "Supervisor";
                      } else if ($usr['contapchamada'] == "3") {
                        $res['nivel_plano_chamada'] = "Administrador";
                      } else {
                        $res['nivel_plano_chamada'] = "Acesso Básico";
                      }
                    } else {
                      $res['nivel_plano_chamada'] = "Acesso Básico";
                    }

                    //sis_cautela

                    if ($usr['nivelacessocautela'] == "0") {
                      $res['nivel_sis_cautela'] = "Usuário comum";
                    } else if ($usr['nivelacessocautela'][1] == "A") {
                      $res['nivel_sis_cautela'] = "Aux Enc Mat";
                    } else if ($usr['nivelacessocautela'][1] == "S") {
                      $res['nivel_sis_cautela'] = "Enc Mat";
                    } else {
                      $res['nivel_sis_cautela'] = "Sem Acesso";
                    }
                    ?> 
                    <tr>
                        <td><a href="cad_usu_atualiza.php?tkusr=<?=base64_encode($usr['id'])?>&btn_atualiza_cadastro="><?=getPGrad($usr['idpgrad'])?> <?=$usr['nomeguerra']?></a></td>
                        <td><?=$res['nivel_arranchamento']?></td>
                        <td><?=$res['nivel_fatos_observados']?></td>
                        <td><?=$res['nivel_guarda']?></td>
                        <td><?=$res['nivel_helpdesk']?></td>
                        <td><?=$res['nivel_plano_chamada']?></td>
                        <td><?=$res['nivel_sis_cautela']?></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                  </table>                    
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
  <script type="text/javascript">
    $(".tabela").DataTable({
      order: [
        [0, "desc"]
      ],
      // scrollY: "512px",
      scrollCollapse: true,
      paging: true,
      searching: true
    });
  </script>
</body>

</html>