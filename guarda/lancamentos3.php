<?php
date_default_timezone_set("America/Cuiaba");
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";
if (!isset($_POST['action'])) {
    header('Location: lancamentos1.php');
    exit();
} else {
    $id_rel = base64_decode(filter_input(INPUT_GET, "id", FILTER_SANITIZE_SPECIAL_CHARS));
    $rel = base64_decode(filter_input(INPUT_GET, "rel", FILTER_SANITIZE_SPECIAL_CHARS));
    $dataatual = date("d/m/Y");
    $horaatual = date("H:i:s");
    $situacao_excluir = filter_input(INPUT_POST, "action", FILTER_SANITIZE_STRING);

    $pdo1 = conectar("guarda");
    $pdo2 = conectar("membros");

    $cad_veiculo = 'veiculo';
    $cad_visitante = 'visitante';
    $select_relatorio = $pdo1->prepare("SELECT $rel.id, $rel.idvisitante, $rel.idusuario, $rel.data, $rel.hora, $rel.situacao, $rel.idveiculo, 
        $cad_visitante.nomecompleto, $cad_visitante.tipo AS tipo_visit, $cad_veiculo.placa, $cad_veiculo.modelo, $cad_veiculo.marca, $cad_veiculo.tipo AS tipo_veiculo FROM $rel 
        LEFT JOIN $cad_visitante ON ($rel.idvisitante = $cad_visitante.id) 
        LEFT JOIN $cad_veiculo ON ($rel.idveiculo = $cad_veiculo.id)
        WHERE  $rel.id = :id");
    $select_relatorio->bindParam(":id", $id_rel, PDO::PARAM_INT);
    $select_relatorio->execute();
    while ($reg = $select_relatorio->fetch(PDO::FETCH_ASSOC)) {
        $idusuario = $reg['idusuario'];
        $situacao = $reg['situacao'];
        $nome_visitante = $reg['nomecompleto'];
        $idveiculo = $reg['idveiculo'];
        $placa_veiculo = $reg['placa'];
    }

    $select_usuarios = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE id = :idusuario");
    $select_usuarios->bindParam(":idusuario", $idusuario, PDO::PARAM_INT);
    $select_usuarios->execute();
    while ($reg2 = $select_usuarios->fetch(PDO::FETCH_ASSOC)) {
        $nome_usuarios = $reg2['nomeguerra'];
        $pg_usuarios = getPGrad($reg2['idpgrad']);
    }

    $sistema = base64_encode('GUARDA');
    if ($idveiculo > 0) {
        $obs = $situacao_excluir . " ID" . $id_rel . ": " . $nome_visitante . ", veículo placa " . $placa_veiculo . ": " . $situacao. ". Lançado pelo " . $pg_usuarios . " " . $nome_usuarios . ".";
    } else {
        $obs = $situacao_excluir . " ID" . $id_rel . ": " . $nome_visitante . ", nenhum veículo: " . $situacao. ". Lançado pelo " . $pg_usuarios . " " . $nome_usuarios . ".";
    }

    $excluir_rel = $pdo1->prepare("DELETE FROM $rel WHERE id = :id");
    $excluir_rel->bindParam(":id", $id_rel, PDO::PARAM_INT);

    if (
        $id_rel == '' or $id_rel == ' ' or $id_rel == '0' or
        $rel == '' or $rel == ' '
    ) {
        $msgerro = base64_encode('Erro na tentativa de excluir o lançamento!');
        header('Location: lancamentos1.php?token=' . $msgerro);
        exit();
    } else {
        $executa = $excluir_rel->execute();
        $executa2 = gerar_log_usuario($sistema, $obs);
        $msgsuccess = base64_encode('Lançamento excluído com sucesso!');
        header('Location: lancamentos1.php?token2=' . $msgsuccess);
        exit();
    }
}
