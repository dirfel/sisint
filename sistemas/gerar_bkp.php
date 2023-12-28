<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
$sucesso_fonte = criar_backup_codigo();
$sucesso_sql = criar_backup_sql();
if($sucesso_fonte) {
    header('location: index.php?token2='.base64_encode('Backup do Código Fonte e '.$sucesso_sql.' Bancos de Dados salvos em: /var/www/bkp/'));
} else if($sucesso_sql) {
    header('location: index.php?token2='.base64_encode($sucesso_sql. ' Bancos de Dados salvos em: /var/www/bkp/'));
} else {
    header('location: index.php?token='.base64_encode('Erro ao salvar o backup. Verifique permissões da pasta de usuario no servidor'));
}
 exit('');
?>