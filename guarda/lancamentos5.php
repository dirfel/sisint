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

    $select_relatorio = $pdo1->prepare("SELECT $rel.idmembro, $rel.idusuario, $rel.situacao, visitante.nomecompleto FROM $rel LEFT JOIN visitante ON ($rel.idvisitante = visitante.id)
        WHERE $rel.id = :id");
    $select_relatorio->bindParam(":id", $id_rel, PDO::PARAM_INT);
    $select_relatorio->execute();
    while ($reg = $select_relatorio->fetch(PDO::FETCH_ASSOC)) {
        $idmembro = $reg['idmembro'];
        $idusuario = $reg['idusuario'];
        $situacao = $reg['situacao'];
        $visitante = $reg['nomecompleto'];
    }

    $select_usuarios = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE usuarios.id = :idmembro");
    $select_usuarios->bindParam(":idmembro", $idmembro, PDO::PARAM_INT);
    $select_usuarios->execute();
    while ($reg2 = $select_usuarios->fetch(PDO::FETCH_ASSOC)) {
        $nome_usuarios = $reg2['nomeguerra'];
        $pg_usuarios = getPGrad($reg2['idpgrad']);
    }
    
    $select_usuarios2 = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE usuarios.id = :idusuario");
    $select_usuarios2->bindParam(":idusuario", $idusuario, PDO::PARAM_INT);
    $select_usuarios2->execute();
    while ($reg2 = $select_usuarios2->fetch(PDO::FETCH_ASSOC)) {
        $nome_usuarios2 = $reg2['nomeguerra'];
        $pg_usuarios2 = getPGrad($reg2['idpgrad']);
    }

    $sistema = base64_encode('GUARDA');
    if ($idmembro == 0) {
        $obs = $situacao_excluir." ID".$id_rel.": O visitante " . $visitante . ": " . $situacao . ". Lançado pelo: " . $pg_usuarios2 . " " . $nome_usuarios2;
    } else {
        $obs = $situacao_excluir." ID".$id_rel.": O militar " . $pg_usuarios . " " . $nome_usuarios . ": " . $situacao . ". Lançado pelo: " . $pg_usuarios2 . " " . $nome_usuarios2;        
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
