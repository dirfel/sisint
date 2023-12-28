<?php

// importar arquivos necessários para executar o código
include "../recursos/models/conexao.php";
require "../recursos/models/versession.php";
if ($_SESSION['nivel_com_soc'] == "Sem Acesso") {
    $msgerro = base64_encode('Usuário não possui acesso!');
    header('Location: ../sistemas/index.php?token=' . $msgerro);
    exit();
}
$pdo = conectar("sistcomsoc");

print_r($_GET);
print_r($_POST);

if(isset($_GET['act']) && $_GET['act'] == del && isset($_GET['id'])) {
    // DELETE
    try {
        $sql = "DELETE FROM reservas WHERE id = '".$_GET['id']."'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        header('Location: hospedes_ht.php?token2=' . base64_encode('Excluído com sucesso!'));
    } catch (Error $e) {
        header('Location: hospedes_ht.php?token=' . base64_encode('Erro inesperado!'));
    }
    
} else if(isset($_GET['id']) && isset($_POST['action']) && $_POST['action'] == 'Editar') {
    // EDIT
    // antes de inserir preciso verificar se não há reserva para este quarto no dia selecionado
    $sql = "SELECT * FROM reservas WHERE quarto = '".$_POST['quarto']."' AND id != '".$_GET['id']."'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $reservas_quarto = $stmt->fetchall(PDO::FETCH_ASSOC);
    foreach($reservas_quarto as $reserva) {
        // datas de checkin e checkout de hospedes já reservados
        $prep_data_checkin = intval($reserva['data_checkin'][6].$reserva['data_checkin'][7].$reserva['data_checkin'][8].$reserva['data_checkin'][9].$reserva['data_checkin'][3].$reserva['data_checkin'][4].$reserva['data_checkin'][0].$reserva['data_checkin'][1]);
        $prep_data_checkout = intval($reserva['data_checkout'][6].$reserva['data_checkout'][7].$reserva['data_checkout'][8].$reserva['data_checkout'][9].$reserva['data_checkout'][3].$reserva['data_checkout'][4].$reserva['data_checkout'][0].$reserva['data_checkout'][1]);
        
        // datas de checkin e checkout de hospede a cadastrar
        $prep_data_checkin_new = intval($_POST['data_checkin'][6].$_POST['data_checkin'][7].$_POST['data_checkin'][8].$_POST['data_checkin'][9].$_POST['data_checkin'][3].$_POST['data_checkin'][4].$_POST['data_checkin'][0].$_POST['data_checkin'][1]);
        $prep_data_checkout_new = intval($_POST['data_checkout'][6].$_POST['data_checkout'][7].$_POST['data_checkout'][8].$_POST['data_checkout'][9].$_POST['data_checkout'][3].$_POST['data_checkout'][4].$_POST['data_checkout'][0].$_POST['data_checkout'][1]);
        $conflito = false;
        
        if($reserva['quarto'] == $_POST['quarto'] || $prep_data_checkin_new >= $prep_data_checkout_new) {
            if($prep_data_checkout_new <= $prep_data_checkin){
                //não tem conflito assim
            } else if($prep_data_checkout <= $prep_data_checkin_new){
                //não tem conflito assim
            } else {
                $conflito = true;
                break;
            }
        }        
    }
    if($conflito) {
        header('Location: hospedes_ht.php?token=' . base64_encode('Já possui reserva para esse período nesse quarto!'));
    } else {
        $sql = "UPDATE reservas SET
                cpf =  '".base64_encode($_POST['cpf'])."',
                om = '".$_POST['om']."',
                data_checkin = '".$_POST['data_checkin']."',
                hora_checkin = '".$_POST['hora_checkin']."',
                data_checkout = '".$_POST['data_checkout']."',
                hora_checkout = '".$_POST['hora_checkout']."',
                veiculo_id = ".$_POST['veiculo_id'].",
                acompanhantes = ".$_POST['acompanhantes'].",
                motivo_reserva = '".$_POST['motivo_reserva']."',
                quarto = '".$_POST['quarto']."',
                gp_tarifa = '".$_POST['gp_tarifa']."',
                adicional_tarifa =  '".$_POST['adicional_tarifa']."'
                WHERE id = ".$_GET['id'];
        try{
            echo $sql;
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            
            header('Location: hospedes_ht.php?token2=' . base64_encode('Reserva atualizada com sucesso!'));
        } catch (Error $e) {
            header('Location: hospedes_ht.php?token=' . base64_encode('Erro inesperado!'));
        }
    }
} else { 

    
    // Insert
    // antes de inserir preciso verificar se não há reserva para este quarto no dia selecionado
    $sql = "SELECT * FROM reservas WHERE quarto = '".$_POST['quarto']."'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $reservas_quarto = $stmt->fetchall(PDO::FETCH_ASSOC);
    foreach($reservas_quarto as $reserva) {
        // datas de checkin e checkout de hospedes já reservados
        $prep_data_checkin = intval($reserva['data_checkin'][6].$reserva['data_checkin'][7].$reserva['data_checkin'][8].$reserva['data_checkin'][9].$reserva['data_checkin'][3].$reserva['data_checkin'][4].$reserva['data_checkin'][0].$reserva['data_checkin'][1]);
        $prep_data_checkout = intval($reserva['data_checkout'][6].$reserva['data_checkout'][7].$reserva['data_checkout'][8].$reserva['data_checkout'][9].$reserva['data_checkout'][3].$reserva['data_checkout'][4].$reserva['data_checkout'][0].$reserva['data_checkout'][1]);
        
        // datas de checkin e checkout de hospede a cadastrar
        $prep_data_checkin_new = intval($_POST['data_checkin'][6].$_POST['data_checkin'][7].$_POST['data_checkin'][8].$_POST['data_checkin'][9].$_POST['data_checkin'][3].$_POST['data_checkin'][4].$_POST['data_checkin'][0].$_POST['data_checkin'][1]);
        $prep_data_checkout_new = intval($_POST['data_checkout'][6].$_POST['data_checkout'][7].$_POST['data_checkout'][8].$_POST['data_checkout'][9].$_POST['data_checkout'][3].$_POST['data_checkout'][4].$_POST['data_checkout'][0].$_POST['data_checkout'][1]);
        $conflito = false;
        
        if($reserva['quarto'] == $_POST['quarto'] || $prep_data_checkin_new >= $prep_data_checkout_new) {
            if($prep_data_checkout_new <= $prep_data_checkin){
                //não tem conflito assim
            } else if($prep_data_checkout <= $prep_data_checkin_new){
                //não tem conflito assim
            } else {
                $conflito = true;
                break;
            }
        }
    }

    if($conflito) {
        header('Location: hospedes_ht.php?token=' . base64_encode('Já possui reserva para esse período nesse quarto!'));
    } else {
        
        // executa abaixo somente se puder cadastrar a reserva não havendo conflito
        
        $sql = "INSERT INTO reservas 
                (id_hospede, om, cpf, data_checkin, hora_checkin, data_checkout, hora_checkout, veiculo_id, acompanhantes, motivo_reserva, quarto, gp_tarifa, adicional_tarifa) 
                VALUES (".$_POST['id_hospede'].", '".$_POST['om']."', '".base64_encode($_POST['cpf'])."', '".$_POST['data_checkin']."', '".$_POST['hora_checkin']."', 
                '".$_POST['data_checkout']."', '".$_POST['hora_checkout']."', ".$_POST['veiculo_id'].", ".$_POST['acompanhantes'].", 
                '".$_POST['motivo_reserva']."', '".$_POST['quarto']."', '".$_POST['gp_tarifa']."', '".$_POST['adicional_tarifa']."')";
        try{
            echo $sql;
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            
            header('Location: hospedes_ht.php?token2=' . base64_encode('Reservado com sucesso!'));
        } catch (Error $e) {
            header('Location: hospedes_ht.php?token=' . base64_encode('Erro inesperado!'));
        }
    }
}
?>