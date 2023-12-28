<?php

include "../recursos/models/conexao.php";
require "../recursos/models/versession.php";
$pdo1 = conectar("membros");
$pdo2 = conectar("guarda");
$pdo3 = conectar("sistcomsoc");
$consultan = $pdo3->prepare('SELECT * FROM conformidade ORDER BY id DESC LIMIT 1');
$consultan->execute();
$reg5 = $consultan->fetchAll(PDO::FETCH_ASSOC); 


?>

<!DOCTYPE html>
<html>
    <head>
        <title>Gerado por: <?= ImprimeConsultaMilitar2($_SESSION['auth_data']) ?></title>
        <style>
            table {
                width: 100%;
                text-align: center;
            }
            .visto {
                column-width: 100px;
            }
            .cell-conf {
                border: solid black 1px;
            }
        </style>
    </head>
    <body>
        <!-- visto Scmt e Fisc Adm -->
        <table>
            <thead>
                <tr>
                    <th class="visto">VISTO</th>
                    <th>3ª BATERIA DE ARTILHARIA ANTIAÉREA</th>
                    <th class="visto">VISTO</th>
                </tr>
            </thead>
            
            <tr>
                    <td class="visto">____________<br>S Cmt</td>
                    <td>PRONTO DA CONFORMIDADE DE REGISTRO E GESTÃO E HOTEL DE TRÂNSITO DO DIA <?= date('d/m/Y')?></td>
                    <td class="visto">____________<br>Fisc Adm</td>
                </tr>
         
        </table>
        <br>
        <h3 style="text-align: center;">Conformidade de Registro e Gestão</h3>
        <!-- tabela conf -->
        <table class="tbl-conf">
            <thead>
                <tr>
                    <th class="cell-conf">Conta</th>
                    <th class="cell-conf">160521</th>
                    <th class="cell-conf">167521</th>
                </tr>
            </thead>
            <tr>
                <th class="cell-conf">Status</th>
                <td class="cell-conf"><?=$reg5[0]['status_ug1'] == 'S' ? 'Sem restrição' : 'Com restrição' ?></td>
                <td class="cell-conf"><?=$reg5[0]['status_ug2'] == 'S' ? 'Sem restrição' : 'Com restrição' ?></td>
            </tr>
            <tr>
                <th class="cell-conf">Descrição</th>
                <td class="cell-conf"><?=$reg5[0]['descricao_ug1'] == '' ? '-' : $reg5[0]['descricao_ug1'] ?></td>
                <td class="cell-conf"><?=$reg5[0]['descricao_ug2'] == '' ? '-' : $reg5[0]['descricao_ug2'] ?></td>
            </tr>
        </table>
        <!-- assinatura conf -->
        <br>
        <br>
        <?php
        // obter id do conformador
        $consulta = $pdo3->prepare('SELECT * FROM conformador ORDER BY id desc LIMIT 1');
        $consulta->execute();
        $cons = $consulta->fetchAll(PDO::FETCH_ASSOC); 

        // obter dados do conformador
        $consulta1 = $pdo1->prepare('SELECT id, nomecompleto, idpgrad FROM usuarios WHERE id = '.$cons[0]['id_conformador'].' LIMIT 1');
        $consulta1->execute();
        $reg1 = $consulta1->fetchAll(PDO::FETCH_ASSOC);

        ?>
        <p style="text-align: center;"><?= $reg1[0]['nomecompleto'] . ' - ' . getPGrad($reg1[0]['idpgrad']) ?><br>Encarregado da Conformidade de Registro e Gestão</p>
        <hr>
        <!-- titulo ht -->
        <h3 style="text-align: center;">Hotel de Trânsito</h3>
        <!-- tabela ht -->
        <table class="tbl-conf" style="font-size: 0.75em;">
            <thead>
                <tr>
                    <th class="cell-conf">Quarto</th>
                    <th class="cell-conf">Hóspede</th>
                    <th class="cell-conf">OM</th>
                    <th class="cell-conf">Motivo reserva</th>
                    <th class="cell-conf">Acompanhantes</th>
                    <th class="cell-conf">Prev. Checkin / Checkout</th>
                </tr>
            </thead>
        <?php
        $consulta = $pdo3->prepare('SELECT *
        FROM reservas 
        WHERE data_checkout LIKE "%'.date('m/Y').'" OR data_checkout LIKE "%'.date('m/Y', strtotime('+1 month')).'" ORDER BY id DESC');
        $consulta->execute();
        $ht = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $d = date("Ymd", time());
        $d1 = date("Ymd", time() + 86400);
        $d2 = date("Ymd", time() + 172800);
        $linhas = 0;
        foreach($ht as $hosp) {
            $prep_data_checkin = $hosp['data_checkin'][6].$hosp['data_checkin'][7].$hosp['data_checkin'][8].$hosp['data_checkin'][9].$hosp['data_checkin'][3].$hosp['data_checkin'][4].$hosp['data_checkin'][0].$hosp['data_checkin'][1];
            $prep_data_checkout = $hosp['data_checkout'][6].$hosp['data_checkout'][7].$hosp['data_checkout'][8].$hosp['data_checkout'][9].$hosp['data_checkout'][3].$hosp['data_checkout'][4].$hosp['data_checkout'][0].$hosp['data_checkout'][1];
            if(
                ($prep_data_checkin <= $d && $prep_data_checkout > $d) ||
                ($prep_data_checkin <= $d1 && $prep_data_checkout > $d1) ||
                ($prep_data_checkin <= $d2 && $prep_data_checkout > $d2)
                ) {
                    $linhas++;
                    echo '<tr>';
                        echo '<td class="cell-conf">'.$hosp['quarto'].'</td>';
                        $a = '';
                            if($hosp['id_hospede'] > 0) {
                                $consulta1 = $pdo1->prepare('SELECT id, nomecompleto, idpgrad, nomeguerra from usuarios where id = '.$hosp['id_hospede']);
                            $consulta1->execute();
                            $reg1 = $consulta1->fetchAll(PDO::FETCH_ASSOC)[0];
                                if($reg1['id'] == $hosp['id_hospede']) {
                                    $a = getPGrad($reg1["idpgrad"]) . ' '. $reg1["nomecompleto"] . ' ('. $reg1["nomeguerra"] . ')';
                                }
                            
                        } else {
                            $id_hosp = str_replace('-', '', $hosp['id_hospede']);
                            $consulta2 = $pdo2->prepare('SELECT id, nomecompleto, tipo from visitante where id = '.$id_hosp);
                            $consulta2->execute();
                            $reg2 = $consulta2->fetchAll(PDO::FETCH_ASSOC)[0];
                                if($reg2['id'] == $id_hosp) {
                                    $a = $reg2["nomecompleto"]. ' (' . $reg2["tipo"] . ')';
                                }
                            }

                        echo '<td class="cell-conf">'.$a.'</td>';
                        echo '<td class="cell-conf">'.$hosp['om'].'</td>';
                        echo '<td class="cell-conf">'.$hosp['motivo_reserva'].'</td>';
                        echo '<td class="cell-conf">'.$hosp['acompanhantes'].'</td>';
                        echo '<td class="cell-conf">'.$hosp['data_checkin'].' '.$hosp['hora_checkin'].' e '.$hosp['data_checkout'].' '.$hosp['hora_checkout'].'</td>';
                    echo '</tr>';
            
                }
        }

        if($linhas == 0) {
            echo '<tr>';
                echo '<td class="cell-conf">-</td>';
                echo '<td class="cell-conf">-</td>';
                echo '<td class="cell-conf">-</td>';
                echo '<td class="cell-conf">-</td>';
                echo '<td class="cell-conf">-</td>';
                echo '<td class="cell-conf">-</td>';
            echo '</tr>';
        }

        ?>
        </table>
        <!-- dados permanencia -->
        <?php
        // obter id do permanencia
        $consulta = $pdo3->prepare('SELECT * FROM escala_permanencia ORDER BY id DESC');
        $consulta->execute();
        $cons = $consulta->fetchAll(PDO::FETCH_ASSOC);
        // echo '<pre>';
        // print_r($cons);
        // echo '</pre>';
        foreach($cons as $con) {
            // echo 'www';
            // try {
                if($con['date'] == date('Y-m-d')) {
                $consulta1 = $pdo1->prepare('SELECT id, nomeguerra, idpgrad, celular FROM usuarios WHERE id = '.$con['id_perm'].' LIMIT 1');
                
                    $consulta1->execute();
                    $reg1 = $consulta1->fetchAll(PDO::FETCH_ASSOC)[0];
                    echo 'Permanência: ' . ImprimeConsultaMilitar2($reg1) . ' - Telefone: ' . $reg1['celular'];
                    break;
                }
            // } catch (Error $e) {
                
            //     echo '<pre>';
            //     print_r($e);
            //     echo '</pre>';
            // }
            
        }

        ?> 
        <!-- assinatura ht -->
        <?php
        // obter id do gestor
        $consulta = $pdo3->prepare('SELECT * FROM gestor_ht ORDER BY id desc LIMIT 1');
        $consulta->execute();
        $cons = $consulta->fetchAll(PDO::FETCH_ASSOC); 

        // obter dados do conformador
        $consulta1 = $pdo1->prepare('SELECT id, nomecompleto, idpgrad FROM usuarios WHERE id = '.$cons[0]['id_gestor'].' LIMIT 1');
        $consulta1->execute();
        $reg1 = $consulta1->fetchAll(PDO::FETCH_ASSOC);

        ?>
        <br>
        <br>
        <p style="text-align: center;"><?= $reg1[0]['nomecompleto'] . ' - ' . getPGrad($reg1[0]['idpgrad']) ?><br>Gestor do Hotel de Trânsito</p>
    </body>
    <script>alert('Pronto Gerado com sucesso!\nPresione CTRL + P para imprimir ou salvar como PDF, caso deseje.')</script>
</html>