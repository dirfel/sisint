<?php

// CRUD ubunidades:

/**
 * (READ) Essa função lista as Subunidades cadastradas em forma de array
 */
function listar_subunidades($id = '') {
    $query = "SELECT * FROM subunidade ORDER BY descricao ASC";
    if($id != '') {
        $query = "SELECT * FROM subunidade WHERE id = :id";
    }
    $consulta = conectar("membros")->prepare($query);
    if($id != '') {
        $consulta->bindParam(":id", $id, PDO::PARAM_INT);
    }
    $consulta->execute();
    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}

?>