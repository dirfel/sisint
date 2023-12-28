<?php

function validaCpf($cpf) {
    $cpf = preg_replace('/[^0-9]/', "", $cpf);
    if($cpf == '00000000000') {
        return true;
    } else if(strlen($cpf) != 11){
        return false;
    } else if (preg_match('/([0-9])\1{10}/', $cpf)) {
        return false;
    }

    $number_quantiy_to_loop = [9, 10];
    foreach($number_quantiy_to_loop as $item) {
        $sum = 0;
        $number_to_multiplicate = $item + 1;
        for($index = 0; $index < $item; $index++) {
            $sum += $cpf[$index] * ($number_to_multiplicate--);
        }
        $result = (($sum * 10) % 11);
        if($cpf[$item] != $result) {
            return false;
        }
    }
    return true; 
}

function inserirSeparadoresCpf($cpf) {
    return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
}

function removerSeparadoresCpf($cpf) {
    return preg_replace('/[^\d{3}]/', '', $cpf);
}

function omitirDigitosCpf($cpf) {
    $cpfTratado = removerSeparadoresCpf($cpf);
    $temSeparadores = ($cpf != $cpfTratado);
    $cpfTratado[0] = '*';
    $cpfTratado[1] = '*';
    $cpfTratado[2] = '*';
    $cpfTratado[9] = '*';
    $cpfTratado[10] = '*';

    if($temSeparadores) {
        return inserirSeparadoresCpf($cpfTratado);
    } else {
        return $cpfTratado;
    }
}

?>