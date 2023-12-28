<?php

/**
 * Lista uma array com dados de todas as viaturas;
 * @param situacao: 2 = qualquer, 1 = fora da OM, 0 = na OM
 * @param baixada: 2 = qualquer, 1 = baixada, 0 = em condições de uso
 */
function listar_viaturas($situacao = 2, $baixada = 2) {
    if($situacao == 0) { $situacao = 1;
    } else if($situacao == 1) { $situacao = 0; }
    if($baixada == 0) { $baixada = 1;
    } else if($baixada == 1) { $baixada = 0; }
    $query = 'SELECT * FROM viatura WHERE NOT situacao = :situacao AND NOT baixada = :baixada';
    $stmt = conectar('guarda')->prepare($query);
    $stmt->bindValue(':situacao', $situacao, PDO::PARAM_INT);
    $stmt->bindValue(':baixada', $baixada, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_viatura_by_id($id) {
    $query = 'SELECT * FROM viatura WHERE id = :id';
    $stmt = conectar('guarda')->prepare($query);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC)['0'] ?? array();
}

/**
 * Implementa a lógica para criar uma viatura
 * TODO: ainda não implementei validações mais avançadas no front end e nao textei inserir valores inválidos
 */
function add_viatura($placa, $tipo, $modelo, $marca, $consumo, $combustivel, $total_ocupantes, $odometro) {
    $query = 'INSERT INTO viatura (placa, tipo, modelo, marca, combustivel, consumo, total_ocupantes, odometro) VALUES (:placa, :tipo, :modelo, :marca, :combustivel, :consumo, :total_ocupantes, :odometro)';
    $stmt = conectar('guarda')->prepare($query);
    $stmt->bindValue(':placa', $placa, PDO::PARAM_STR);
    $stmt->bindValue(':tipo', $tipo, PDO::PARAM_STR);
    $stmt->bindValue(':modelo', $modelo, PDO::PARAM_STR);
    $stmt->bindValue(':marca', $marca, PDO::PARAM_STR);
    $stmt->bindValue(':combustivel', $combustivel, PDO::PARAM_STR);
    $stmt->bindValue(':consumo', $consumo, PDO::PARAM_INT);
    $stmt->bindValue(':total_ocupantes', $total_ocupantes, PDO::PARAM_INT);
    $stmt->bindValue(':odometro', $odometro, PDO::PARAM_INT);
    return $stmt->execute();
}

/**
 * Implementa a lógica para editar algum parâmetro de uma viatura;
 * @param id é  oid da viatura que queremos editar;
 * @param parametro é o parametro a ser alterado;
 * @param new_value é o valor novo;
 * @param param_type é o tipo de parametro, Ex: PDO::PARAM_STR;
 */
function edit_param_viatura($id, $parametro, $new_value, $param_type) {
    $query = sprintf('UPDATE viatura SET `%s` = :new_value WHERE id = :id', $parametro);
    $stmt = conectar('guarda')->prepare($query);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':new_value', $new_value, $param_type);
    return $stmt->execute();
}

?>