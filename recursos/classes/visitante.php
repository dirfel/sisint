<?php



/** Aqui será armazenado métodos para interagir com a tabela visitantes e rel_visitantes */

/**
 * Essa função retorna um array com a informação de TODOS os visitantes.
 * cada elemento desse array será um novo array com os dados do visitante.
 */
function read_visitante($tipo = null) {
    echo $tipo;
    $query = '';
    if($tipo == null) {
        $query = 'SELECT * FROM visitante WHERE userativo = "S" ORDER BY tipo, nomecompleto ASC';
        $stmt = conectar('guarda')->prepare($query);
    } else {
        $query = 'SELECT * FROM visitante WHERE (userativo = "S" AND tipo LIKE :tipo) ORDER BY nomecompleto ASC';
        $stmt = conectar('guarda')->prepare($query);
        $stmt->bindValue(':tipo', $tipo, PDO::PARAM_STR);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Essa função retorna um array com a informação dos visitantes que estão (@param situacao = 1) ou que não estão (@param situacao = 0) no interior da OM no momento.
 * cada elemento desse array será um novo array com os dados do visitante.
 */
function read_visitante_por_situacao($situacao) {
    $stmt = conectar('guarda')->prepare('SELECT * FROM visitante WHERE userativo = "S" AND situacao = :situacao ORDER BY tipo, nomecompleto ASC');
    $stmt->bindValue(':situacao', $situacao, PDO::PARAM_BOOL);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Essa função retorna um array com a informação dos visitantes e seus veículos por situação no momento.
 * cada elemento desse array será um novo array com os dados do visitante e seus veículos.
 * situação 0 -> fora da OM
 * situação 1 -> dentro da OM
 * situação 2 -> ambos os casos
 */
function read_visitante_por_situacao_com_veic($situacao) {
    $stmt = '';
    if($situacao == 2) {
        $stmt = conectar('guarda')->prepare("SELECT visitante.id, visitante.idpgrad ,visitante.nomecompleto,  visitante.datanascimento, visitante.identidade, visitante.cpf, visitante.tipo, visitante.idveiculo, visitante.cracha, veiculo.placa, veiculo.marca, 
        veiculo.modelo, veiculo.tipo AS veiculo_tipo FROM visitante LEFT JOIN veiculo ON (visitante.idveiculo = veiculo.id)");
    } else {
        $stmt = conectar('guarda')->prepare("SELECT visitante.id, visitante.nomecompleto,  visitante.datanascimento, visitante.identidade, visitante.cpf, visitante.tipo, visitante.idveiculo, visitante.cracha, veiculo.placa, veiculo.marca, 
        veiculo.modelo, veiculo.tipo AS veiculo_tipo FROM visitante LEFT JOIN veiculo ON (visitante.idveiculo = veiculo.id) WHERE visitante.situacao = ':situacao'");
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Essa função troca o @param situacao de zero para um ou vice-versa e retorna o número de linhas modificadas.
 * Isso acontece quando o visitante entra ou sai do portão das armas.
 */
function toggle_situacao_visitante($visitanteId, $situacaoAtual) {
    $stmt = conectar('guarda')->prepare('UPDATE visitante SET situacao = :situacao WHERE id = :id');
    $stmt->bindValue(':situacao', !$situacaoAtual, PDO::PARAM_BOOL);
    $stmt->bindValue(':id', $visitanteId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount();
}

/**
 * Obtem os dados do visitante através do id dele
 */
function get_visitante_by_id($visitanteId) {
    $stmt = conectar('guarda')->prepare('SELECT * FROM visitante WHERE id = :id');
    $stmt->bindValue(':id', $visitanteId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC)['0'] ?? null;
}

/**
 * Obtem a lista de todos registros do visitante na guarda
 */
function get_registros_visitante($visitanteId) {
    $stmt = conectar('guarda')->prepare('SELECT * from rel_visitantes WHERE idvisitante = :id ORDER BY id DESC');
    $stmt->bindValue(':id', $visitanteId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Obtem os dados do veículo a partir do id
 */
function get_veiculo_by_id($idVeiculo) {
    $stmt = conectar('guarda')->prepare('SELECT * FROM veiculo WHERE id = :id');
    $stmt->bindValue(':id', $idVeiculo, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC)['0'] ?? null;
}

function get_descricao_veic_by_id($idVeiculo) {
    $veic = get_veiculo_by_id($idVeiculo);
    return $veic == null ? '-' : strtoupper($veic['marca'] . ' ' . $veic['modelo'] . ' ' . $veic['placa']);
}












?>