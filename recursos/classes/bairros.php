<?php

// CRUD Setores:

/**
 * (READ) Essa função lista os setores cadastrados em forma de array
 */
function listar_setores_de_bairros() {
    $consulta2 = conectar("membros")->prepare("SELECT * FROM setores");
    $consulta2->execute();
    return $consulta2->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * (CREATE) Essa função cria um novo setor
 */
function criar_setor_de_bairro($nomeSetor) {
    // checarei se esse setor já não existe:
    $setores = listar_setores_de_bairros();
    foreach($setores as $setor) {
        if($setor['setor'] == $nomeSetor) {
            return 0;
        }
    }
    $stmte = conectar('membros')->prepare("INSERT INTO setores (setor) VALUES (:setor)");
    $stmte->bindParam(":setor", $nomeSetor, PDO::PARAM_STR);
    return $stmte->execute();
}






// CRUD Bairros:

/**
 * (READ) Essa função lista os setores cadastrados em forma de array
 */
function listar_bairros($id = '') {
    $query = "SELECT * FROM bairros ORDER BY bairro ASC";
    if($id != '') {
        $query = "SELECT * FROM bairros WHERE id = :id";
    }
    $consulta = conectar("membros")->prepare($query);
    if($id != '') {
        $consulta->bindParam(":id", $id, PDO::PARAM_INT);
    }
    $consulta->execute();
    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}

?>