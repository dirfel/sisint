<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
?>
<!doctype html>
<html lang="pt-BR" class="fixed">
    <head>
      <?php include '../recursos/views/cabecalho.php'; ?>
    </head>
    <body>
        <div class="wrap">
        <div class="page-header">
        <?php render_painel_usu('ARRANCHAMENTO', $_SESSION['nivel_arranchamento']); ?>
    </div>
    <div class="page-body">
      <div class="left-sidebar">
        <?php include 'menu_opc.php'; ?>
      </div>
                <div class="content">
                    <?php render_content_header('Histórico dos meus arranchamentos', 'fa fa-cutlery'); ?>
                    <div class="row animated fadeInUp">                        
                        <div class="col-sm-12">
                            <div class="panel">
                                <div class="panel-content">
                                    <table id="responsive-table" class="data-table table table-striped table-hover responsive nowrap"
                                           cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Registro</th> 
                                                <th>Data</th>                                             
                                                <th>Café</th>
                                                <th>Almoço</th>
                                                <th>Jantar</th>
                                                <th>Modo</th>
                                                <th>Data Grav</th>
                                                <th>Hora Grav</th>
                                                <th>Quem foi?</th>                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $iduser = $_SESSION['auth_data']['id'];
                                            $pdo = conectar("arranchamento");
                                            $conspronto = $pdo->prepare("SELECT * FROM arranchado WHERE iduser = $iduser");
                                            $conspronto->execute();
                                            while ($reg2 = $conspronto->fetch(PDO::FETCH_ASSOC)) :
                                                /* Para recuperar um ARRAY utilize PDO::FETCH_ASSOC */
                                                echo("<tr>");
                                                echo("<td>" . $reg2['id'] . "</td>");
                                                echo("<td>" . $reg2['data'] . "</td>");                                                
                                                echo("<td>" . $reg2['cafe'] . "</td>");                                                                                               
                                                echo("<td>" . $reg2['almoco'] . "</td>");
                                                echo("<td>" . $reg2['jantar'] . "</td>");
                                                echo("<td>" . $reg2['modo'] . "</td>");
                                                echo("<td>" . $reg2['datagrava'] . "</td>");
                                                echo("<td>" . $reg2['horagrava'] . "</td>");
                                                echo("<td>" . $reg2['quemgrava'] . "</td>");
                                                echo("</tr>");
                                            endwhile;
                                            ?>
                                        </tbody>
                                    </table>
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