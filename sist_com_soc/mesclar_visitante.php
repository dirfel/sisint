<?php

/**
 * Esse arquivo gerencia a mescla de visitantes, ou seja, quando há dois registros da mesma pessoa cadastrada 
 * no sistema, o adminstrador pode juntar todas as informações para dentro de um único registro e excluir a
 * duplicidade, evitando assim a sobrecarga no banco de dados e a otimização das consultas.
 */

 require "../recursos/models/versession.php";
 include "../recursos/models/conexao.php";
  
 $p1 = conectar('guarda');
 $p2 = conectar('sistcomsoc');
 $p3 = conectar('controlepessoal');
 
// 1. checar se o usuario tem permissão para fazer essa ação
if (!$_SESSION['nivel_plano_chamada'] == "Supervisor" && !$_SESSION['nivel_plano_chamada'] == "Administrador") {
    $msgerro = base64_encode('Usuário não possui acesso! Somente usuários: Supervisor e administrador!');
    header('Location: visualizar_visitante.php?id='.base64_encode($_POST['op_a']).'&token=' . $msgerro);
    exit();
} else if($_POST['op_a'] == null || $_POST['op_b'] == null) {
    $msgerro = base64_encode('Erro inesperado!');
    header('Location: gerenciar_visitantes.php?token=' . $msgerro);
    exit();
} else if($_POST['op_a'] == $_POST['op_b']) {   
    $msgerro = base64_encode('Não é possível mesclar dados do mesmo registro de usuário!');
    header('Location: visualizar_visitante.php?id='.base64_encode($_POST['op_a']).'&token=' . $msgerro);
    exit();
}

// 2. receber via post, o id de visitante dos registros
$op_a = base64_decode($_POST['op_a']); // registro que manterá existindo, absorvendo todos os lançamentos do B
$op_b = base64_decode($_POST['op_b']); // registro que será excluído, passando todos registros ao $op_a

// 3. atualizar na tabela res_visitantes a coluna idvisitante para $op_a onde idvisitante = $op_b
$stmt = $p1->prepare('UPDATE rel_visitantes SET idvisitante = :op_a WHERE idvisitante = :op_b');
$stmt->bindParam(':op_a', $op_a);
$stmt->bindParam(':op_b', $op_b);
$stmt->execute();
$cols = $stmt->rowCount();


// 4. obter os dados de $op_a e $op_b da tabela visitante
$vis_a = get_visitante_by_id($op_a);
$vis_b = get_visitante_by_id($op_b);

// echo $cols;
// echo '<pre>';
// print_r($vis_a);
// print_r($vis_b);
// echo '</pre>';
// die('<br>FIM!');
// 5. verifico se há veículo cadastrado para $op_b e direciono para o outro
if($vis_b['idveiculo'] != 0 && $vis_a['idveiculo'] == 0) {
    $stmt = $p1->prepare('UPDATE visitante SET idveiculo = :veic_b WHERE id = :op_a');
    $stmt->bindParam(':op_a', $op_a);
    $stmt->bindParam(':veic_b', $vis_b['idveiculo']);
    $stmt->execute();
    $cols += $stmt->rowCount();
}

// 6. verifico se há crachá cadastrado para $op_b
if($vis_b['cracha'] != 0 && $vis_a['cracha'] == 0) {
    $stmt = $p1->prepare('UPDATE visitante SET cracha = :cracha_b WHERE id = :op_a');
    $stmt->bindParam(':op_a', $op_a);
    $stmt->bindParam(':cracha_b', $vis_b['cracha']);
    $stmt->execute();
    $cols += $stmt->rowCount();
}

// 7. verifico se a situacao = 1 para $op_b
if($vis_b['situacao'] != 0 && $vis_a['situacao'] == 0) {
    $stmt = $p1->prepare('UPDATE visitante SET situacao = :situacao_b WHERE id = :op_a');
    $stmt->bindParam(':op_a', $op_a);
    $stmt->bindParam(':situacao_b', $vis_b['situacao']);
    $stmt->execute();
    $cols += $stmt->rowCount();
}

// 8. verifico se há registro de hóspede no HT e passo para op_a
$op_amod = '-'.$op_a;
$op_bmod = '-'.$op_b;
$stmt = $p2->prepare('UPDATE reservas SET id_hospede = :op_a WHERE id_hospede = :op_b');

$stmt->bindParam(':op_a', $op_amod);
$stmt->bindParam(':op_b', $op_bmod);
$cols += $stmt->rowCount();

// 9. verifico se não é dependente de militar, veterano ou pensionista cadastrado para fins de fusex
$stmt = $p3->prepare('UPDATE dependentes_fusex SET id_visitante = :op_a WHERE id_visitante = :op_b');
$stmt->bindParam(':op_a', $op_a);
$stmt->bindParam(':op_b', $op_b);
$cols += $stmt->rowCount();

//////////////////////////////////////////////////////////////
// 10. remover o usuario $op_b
$stmt = $p1->prepare('DELETE FROM visitante WHERE id = :op_b');
$stmt->bindParam(':op_b', $op_b);
$stmt->execute();
$cols += $stmt->rowCount();

// 11. retornar a página anterior e exibir o feedback ao usuarios se deu certo ou não
if($cols) {
    gerar_log_usuario('Sist_Com_soc', 'mesclou os visitantes: '. $vis_a . ' e ' . $vis_b);
    $msgsucesso = base64_encode('Foram alteradas ' . $cols . ' informações no banco de dados.');
    header('Location: visualizar_visitante.php?id='.base64_encode($op_a).'&token2=' . $msgsucesso);
} else {
    $msgerro = base64_encode('Nenhum registro alterado!');
    header('Location: visualizar_visitante.php?id='.base64_encode($op_a).'&token=' . $msgerro);
}
?>