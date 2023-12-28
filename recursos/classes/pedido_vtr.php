<?php

/**
 * Cadastra o pedido de viatura. @param new_pedido_data é a variável $_POST 
 */
function registrar_pedido_vtr($new_pedido_data) {
    $natureza = $new_pedido_data['natureza'] ?? '';
    $itinerario = $new_pedido_data['itinerario'] ?? '';
    $distancia = $new_pedido_data['distancia'] ?? 0;
    $total_passageiros = $new_pedido_data['total_passageiros'] ?? 2;
    $datahora_saida = date_converter($new_pedido_data['data_saida']) . ' ' . $new_pedido_data['hora_saida'] . ":00";
    $datahora_chegada = date_converter($new_pedido_data['data_chegada']) . ' ' . $new_pedido_data['hora_chegada'] . ":00";
    $abastecimento = $new_pedido_data['abastecimento'] ?? 'N';
    $alojamento = $new_pedido_data['alojamento'] ?? 'N';
    $arranchamento = $new_pedido_data['arranchamento'] ?? 'N';
    $id_viatura = serialize($new_pedido_data['vtr']);
    $id_solicitante = $_SESSION['auth_data']['id'];

    $query = 'INSERT INTO pedido_vtr (id_viatura, id_solicitante, natureza, itinerario, distancia, total_passageiros, datahora_saida, datahora_chegada, abastecimento, alojamento, arranchamento) 
              VALUES (:id_viatura, :id_solicitante, :natureza, :itinerario, :distancia, :total_passageiros, :datahora_saida, :datahora_chegada, :abastecimento, :alojamento, :arranchamento)';
    $pdo = conectar('guarda');
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':id_viatura', $id_viatura, PDO::PARAM_STR);
        $stmt->bindValue(':id_solicitante', $id_solicitante, PDO::PARAM_INT);
        $stmt->bindValue(':natureza', $natureza, PDO::PARAM_STR);
        $stmt->bindValue(':itinerario', $itinerario, PDO::PARAM_STR);
        $stmt->bindValue(':distancia', $distancia, PDO::PARAM_INT);
        $stmt->bindValue(':total_passageiros', $total_passageiros, PDO::PARAM_INT);
        $stmt->bindValue(':datahora_saida', $datahora_saida, PDO::PARAM_STR);
        $stmt->bindValue(':datahora_chegada', $datahora_chegada, PDO::PARAM_INT);
        $stmt->bindValue(':abastecimento', $abastecimento, PDO::PARAM_STR);
        $stmt->bindValue(':alojamento', $alojamento, PDO::PARAM_STR);
        $stmt->bindValue(':arranchamento', $arranchamento, PDO::PARAM_STR);
        return $stmt->execute(); 
}

 ?>