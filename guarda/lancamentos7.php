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

    $select_relatorio = $pdo1->prepare("SELECT $rel.idusuario, $rel.data_p, $rel.hora_p, $rel.data_r, $rel.hora_r, rot_guarda_funcao.nomefuncao 
    FROM $rel LEFT JOIN rot_guarda_funcao ON ($rel.idfuncao = rot_guarda_funcao.idfuncao) WHERE $rel.id = :id");
    $select_relatorio->bindParam(":id", $id_rel, PDO::PARAM_INT);
    $select_relatorio->execute();
    while ($reg = $select_relatorio->fetch(PDO::FETCH_ASSOC)) {
        $idusuario = $reg['idusuario'];
        $funcao = $reg['nomefuncao'];
        $data_p = $reg['data_p'];
        $hora_p = $reg['hora_p'];
        $data_r = $reg['data_r'];
        $hora_r = $reg['hora_r'];
    }

    $select_usuarios2 = $pdo2->prepare("SELECT nomeguerra, idpgrad FROM usuarios WHERE id = :idusuario");
    $select_usuarios2->bindParam(":idusuario", $idusuario, PDO::PARAM_INT);
    $select_usuarios2->execute();
    while ($reg2 = $select_usuarios2->fetch(PDO::FETCH_ASSOC)) {
        $nome_usuarios_lancou = $reg2['nomeguerra'];
        $pg_usuarios_lancou = getPGrad($reg2['idpgrad']);
    }

    $sistema = base64_encode('GUARDA');
    $obs = $situacao_excluir." ID".$id_rel.": ".$funcao." - Permanência: ".$hora_p." ".$data_p." - Ronda: ".$hora_r." ".$data_r.". Lançado pelo: ".$pg_usuarios_lancou." ".$nome_usuarios_lancou;

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
