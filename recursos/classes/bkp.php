<?php
function criar_backup_codigo() {
    try {
        $data = date('Y-m-d-h');
        $filename = 'bkp-' . $data . '.zip';
        $path = PASTA_BKP . $filename;
        
        $cmd = 'zip -r ' . $path . ' '.PASTA_RAIZ.'*';
        $saida = shell_exec($cmd);
        return true;
     } catch(Error $e) {
         return true;
     }
}

function criar_backup_sql() {
    $ret = 0;
    $databases = array('agenda', 'arranchamento', 'controlepessoal', 'guarda', 'helpdesk', 'membros', 'siscautela', 'sistcomsoc');
    foreach($databases as $database) {
        $file = PASTA_BKP."/".$database."-`date +%Y%m%d%H%M%S`.sql";
        // Fazer backup do banco de dados
        $command = "mysqldump -u ".DB_USER." -p '".$database."' --password='".DB_PWD."' -h localhost > ".$file;
        shell_exec($command);

        // Verificar se o backup foi criado com sucesso
        if (file_exists($file)) {
            $ret = $ret++;
        }
    }
    return $ret;
}

?>