<?php
include "../recursos/models/conexao.php";
date_default_timezone_set("America/Cuiaba");
$pdo = conectar("membros");
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
$fp = fopen("registra.txt", "a");

// Escreve "exemplo de escrita" no bloco1.txt
$conteudo = "$ip\r\n";
$escreve = fwrite($fp, $conteudo);
 
// Fecha o arquivo
fclose($fp);
?>
<!doctype html>
<html lang="pt-BR" class="fixed">
    <head>
        <?php include '../recursos/views/cabecalho.php'; ?>
        
    </head>
    <body>
        <div class="panel">
            <div class="panel-header panel-success">
                <h4>Caso seu nome não apareça listado abaixo, solicite o cadastro 
                    juntamente ao FURRIEL de sua SU. Dúvidas quanto ao acesso e senha,
                    deverá reportar ao pessoal da SEÇÃO DE INFORMÁTICA. O cadastro
                    para o novo SISTEMA DE ARRANCHAMENTO funciona também para o 
                    sistema de PLANO DE CHAMADAS</h4>
                <?php
                $consultausu = $pdo->prepare("SELECT * FROM usuarios WHERE userativo = 'S' ORDER BY idpgrad, nomeguerra ASC");
                $consultausu->execute();
                $qtdusers = $consultausu->fetchAll(PDO::FETCH_ASSOC);
                $qtd_users = count($qtdusers);
                ?>
            </div>
            <div class="panel-content">
                <div class="table-responsive">                                                    
                    <form action="#">
                        <table class="table table-striped table-hover table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>ID Usuário</th>
                                    <th>P/G</th>
                                    <th>Nome guerra</th>
                                    <th>Subunidade</th>
                                    <th>Identidade</th>
                                    <th>Senha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for ($i = 0; $i < $qtd_users; $i++) {
                                    $reg = $qtdusers[$i];
                                    $regpg = getPGrad($reg['idpgrad']);
                                    $descsu = listar_subunidades($reg['idsubunidade'])[0];
                                    echo("<tr>");
                                    echo("<td>" . $reg['id'] . "</td>");
                                    echo("<td>" . $regpg['postograd'] . "</td>");
                                    echo("<td>" . $reg['nomeguerra'] . "</td>");
                                    echo("<td>" . $descsu['descricao'] . "</td>");
                                    if ($reg['identidade'] <> "") {
                                        $idt = "OK";
                                    } else {
                                        $idt = "SEM ACESSO";
                                    }
                                    if ($reg['senha'] <> "") {
                                        $snh = "OK";
                                    } else {
                                        $snh = "SEM ACESSO";
                                    }
                                    echo("<td>" . $idt . "</td>");
                                    echo("<td>" . $snh . "</td>");
                                    echo("</tr>");
                                }
                                ?>
                            </tbody>
                        </table>
                        <div class="mb-md">
                        </div>
                        <div class="clearfix">
                            <h3>Total listado: <?php echo($qtd_users); ?></h3>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include '../recursos/views/scroll_to_top.php'; ?>
    <?php include '../recursos/views/footer.php'; ?>
</body>
</html>
