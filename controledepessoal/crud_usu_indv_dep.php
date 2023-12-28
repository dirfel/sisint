<?php

/**
 * Esse arquivo define as funções crud de dependentes de militar
 */

// importa recursos necessários
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

// realiza as validações necessárias para verificar se o usuário possui permissão para realizar essa ação
if($_SESSION['nivel_helpdesk'] != 'Administrador') { // limita acesso a quem não possui permissão
    die('Em desenvolvimento');
} else if(!isset($_GET['tkusr'])) { // se não há titular escolhido, aborta a operação
    header('Location: index.php?token2='.base64_encode('Nenhum militar escolhido.'));
    exit();
} else if(!isset($_POST['action'])) { // se não houver ação escolhida, aborta a missão
    header('Location: cad_usu_indiv_dep.php?token2='.base64_encode('Erro desconhecido.'));
    exit();
}

// crio variaveis para usar no código
$pdo = conectar('controlepessoal');

// não havendo problemas, iremos verificar qual a função solicitada e executar as ações necessárias:
if($_POST['action'] == 'atualizar') {
    if(!isset($_POST['prec_cp']) || !isset($_POST['parentesco']) || !isset($_POST['obs']) || !isset($_POST['nascimento']) || !isset($_POST['id'])){
        header('Location: cad_usu_indiv_dep.php?token2='.base64_encode('Faltou algum parâmetro.'));
        exit();
    }
    $stmt = $pdo->prepare('UPDATE dependentes_fusex 
                           SET prec_cp = :prec_cp, parentesco = :parentesco, obs = :obs, nascimento = :nascimento 
                           WHERE id = :dep_id');
    $stmt->bindValue(':dep_id', $data['dep_id'], PDO::PARAM_STR);
    $stmt->bindValue(':nomecompleto', $data['nomecompleto'], PDO::PARAM_STR);
    $stmt->bindValue(':parentesco', $data['parentesco'], PDO::PARAM_STR);
    $stmt->bindValue(':nascimento', $data['nascimento'], PDO::PARAM_STR);
    $stmt->bindValue(':obs', $data['obs'], PDO::PARAM_STR);

    $stmt->execute();

    if($stmt->rowCount() > 0) {
        header('Location: cad_usu_indiv_dep.php?token='.base64_encode('Dependente atualizado com sucesso.'));
    } else {
        header('Location: cad_usu_indiv_dep.php?token='.base64_encode('Erro ao atualizar dependente.'));
    }
    exit();
} else if($_POST['action'] == 'incluir') {


} else if($_POST['action'] == 'desvincular') {

} else if($_POST['action'] == '????') {

} else { // quando a ação for alguma que não foi programada, por segurança, retorna ao usuário essa mensagem de erro.
    header('Location: cad_usu_indiv_dep.php?token2='.base64_encode('Erro desconhecido.'));
    exit();
}



// cria a regra de negocio
function crud_dependentes($request, $data) {
    $pdo3 = conectar("controlepessoal");
    // $stmt;
    if($request == 'Incluir Dependente') {
        $stmt = $pdo3->prepare('');
        $sql = 'INSERT INTO xxxx (x, y, z, ...) VALUES (:a, :b, :c, :d)';
    } else if($request == 'atualizar') {
        
        
    } else if($request == 'Desvincular Dependente') {
        $stmt = $pdo3->prepare('');
        $sql = 'UPDATE xxxx SET a = :a WHERE b = :b';
    }
}

if(isset($_POST['action'])) {
    if($_POST['action'] == 'Incluir Dependente') {
        if (base64_decode($_GET['tkusr']) == $_SESSION['auth_data']['id'] || $_SESSION['nivel_helpdesk'] == 'Administrador') {
            $token2 = "Dependente incluído com sucesso";
        } else {
            $token = "Você não tem permissão para realizar essa aividade";
        }
    } else if($_POST['action'] == 'atualizar') {
        if (base64_decode($_GET['tkusr']) == $_SESSION['auth_data']['id'] || $_SESSION['nivel_helpdesk'] == 'Administrador' || $_SESSION['nivel_helpdesk'] == 'Supervisor') {
            if(crud_dependentes('atualizar', $_POST)) {
              $token2 = "Dependente alterado com sucesso";
            } else {
              $token = 'Erro ao alterar dependente';
            }
        } else {
            $token = "Você não tem permissão para realizar essa aividade";
        }
    } else if($_POST['action'] == 'Desvincular Dependente') {
        if (base64_decode($_GET['tkusr']) == $_SESSION['auth_data']['id'] || $_SESSION['nivel_helpdesk'] == 'Administrador' || $_SESSION['nivel_helpdesk'] == 'Supervisor') {
            $token2 = "Dependente desvinculado com sucesso";
        } else {
            $token = "Você não tem permissão para realizar essa aividade";
        }
    }
}


?>