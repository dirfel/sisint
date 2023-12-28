<?php

// CREATE
function processa_upload($numidt, $arquivo) {
    $diretorio = "../anexo/" . $numidt;
    try{
        if (!empty($arquivo["name"])) {
            if (!file_exists($diretorio)) {
                mkdir($diretorio, 0777);
            }
            $extensao = strtolower(end(explode(".", $arquivo["name"])));
            if($extensao != 'pdf' && $extensao != 'png' && $extensao != 'jpg' && $extensao != 'jpeg') {
                die('Formato não autorizado');
            }
            // Gera um nome único para o arquivo 
            $nome_imagem = md5(uniqid(time())) . "." . $extensao;
            // Caminho de onde ficará o arquivo 
            $caminho = $diretorio . "/" . $nome_imagem;
            // Faz o upload do arquivo para seu respectivo caminho 
            move_uploaded_file($arquivo["tmp_name"], $caminho);
            // Insere os dados no banco com arquivo inclusive
            return $caminho;
        } else {
            return "";
        }
    } catch(Error $e){
        print_r($e); die('');
    }
}
// Remove
function remove_arquivo($file_path) {
    unlink($file_path);
}

?>