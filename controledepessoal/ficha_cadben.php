<?php
include "../recursos/models/conexao.php";
require "../recursos/models/versession.php";
$pdo1 = conectar("membros");
$pdo2 = conectar("controlepessoal");

// $id = base64_decode($_GET['id']);
$id = $_SESSION['auth_data']['id'];

// Obter informações do titular:
$titular = $pdo1->prepare("SELECT * FROM usuarios WHERE id = :id");
$titular->bindParam(":id", $id, PDO::PARAM_INT);
$titular->execute();
$titular = $titular->fetchAll(PDO::FETCH_ASSOC)[0];
// Obter lista de dependantes com seus dados:

$dependentes = $pdo2->prepare("SELECT * FROM dependentes_fusex INNER JOIN guarda.visitante ON id_visitante = visitante.id WHERE id_titular = :id");
$dependentes->bindParam(":id", $id, PDO::PARAM_INT);
$dependentes->execute();
$dependentes = $dependentes->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Ficha do CADBEN</title>
        <style>
            table {
                width: 100%;
                text-align: center;
            }
            .visto {
                column-width: 100px;
            }
            .tbl-conf {
                /* column-width: 50%; */
            }
            .cell-conf {
                border: solid black 1px;
            }
            @media print {
                @page { 
                    margin-top: 0; 
                    margin-bottom: 0; 
                }

            }
        </style>
    </head>
    <body>
       <div style="text-align: center;">
        <img width="140cm" height="150cm" src="../recursos/assets/brasao_armas.gif">
        <h4 style="margin-top: 0; font-size: 11px;">MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br><?=strtoupper(SUBORDINACAO)?><br><?=strtoupper(NOME_OM)?></h4>
       </div>
        <h3 style="text-align: center;">FICHA AUXILIAR PARA O EXAME DO CADBEN-FuSEx</h3>
        
        <p style="text-indent: 2em; text-align: justify;">Eu <?=$titular['nomecompleto']?>, beneficiário titular do Fundo de Saúde do Exército, Idt <?=base64_decode($titular['identidade'])?>, CPF <?=base64_decode($titular['cpf'])?>, Prec CP <?=$titular['prec_cp'] ?? "___________" ?>, declaro expressamente, sob as penas da lei, que meus beneficiários dependentes para fim de assistência médico-hospitalar pelo Fundo de Saúde do Exército (FUSEx), com amparo no que está disposto nos  art. 5º, 6º e 7º das IG 30-32:</p>
        <table class="tbl-conf">
            <thead>
                <tr>
                    <th class="cell-conf">NOME</th>
                    <th class="cell-conf">GRAU DE<br>PARENTESCO</th>
                    <th class="cell-conf">DATA DE<br>NASCIMENTO</th>
                    <th class="cell-conf">OBS</th>
                </tr>
            </thead>
            <?php 
            $numdeps = count($dependentes) > 7 ? count($dependentes) - 1 : 7;

            for($i=0; $i<$numdeps; $i++) { ?>
                <tr>
                    <td class="cell-conf"><?= $dependentes[$i]['nomecompleto'] ?? '---' ?></td>
                    <td class="cell-conf"><?= $dependentes[$i]['parentesco'] ?? '---' ?></td>
                    <td class="cell-conf"><?= $dependentes[$i]['datanascimento'] ?? '---' ?></td>
                    <td class="cell-conf"><?= $dependentes[$i]['obs'] ?? '---' ?></td>
                </tr>
            <?php } ?>
            
        </table>
        <h5 style="text-bold; margin-top: 0; text-align: justify;">Responsabilizo-me pela exatidão e veracidade das informações declaradas, ciente de que, se falsas, estarei infringindo o que está disposto no art. 299 do Código Penal e no art. 312 do Código Penal Militar, ficando sujeito às sanções civis, administrativas e criminais.</h5>
        <div style="text-align: center;">
        <?php
        
        $dia = date('j');
        $mes = date('n');
        $ano = date('Y');

        $meses = $array = array(
            1 => 'janeiro',
            2 => 'fevereiro',
            3 => 'março',
            4 => 'abril',
            5 => 'maio',
            6 => 'junho',
            7 => 'julho',
            8 => 'agosto',
            9 => 'setembro',
            10 => 'outubro',
            11 => 'novembro',
            12 => 'dezembro',
        );
        $mes_extenso = $meses[$mes];
        echo "<p>Três Lagoas - MS, " . $dia . ' de ' . $mes_extenso . ' de ' . $ano . '</p>';
        ?>

        <h5>____________________________________________________________________________<br><?=$titular['nomecompleto']?> - <?=getPGrad($titular['idpgrad'])?><br>Titular</h5>
        </div>
                
        
        <?= DateTime('now', DateTimeZone('America/Cuiaba'))->format('j \d\e F \d\e Y') ?>
        <script>
            alert('Verifique se seus dados estão corretamente preenchidos. Caso não estejam, atualize seus dados');
        </script>
    </body>
</html>