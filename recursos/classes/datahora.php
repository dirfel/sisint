<?php




//função para tratar datas com separador "/"
function date_converter($_date = null) {
    $format = '/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/';
    if ($_date != null && preg_match($format, $_date, $partes)) {
        return $partes[3] . '-' . $partes[2] . '-' . $partes[1];
    }
    return false;
}

//função para tratar datas com separador "-"
function date_converter2($_date = null) {
  $format = '/^([0-9]{4})\-([0-9]{2})\-([0-9]{2})$/';
  if ($_date != null && preg_match($format, $_date, $partes)) {
    return $partes[3] . '/' . $partes[2] . '/' . $partes[1];
  }
  return false;
}


function calculaData($data1, $hora1, $data2, $hora2) {
    $data_Inicial = date_converter($data1) . $hora1;
    if ($data2 == "" && $hora2 == "") {
        $data_Atual = date("Y-m-d H:i:s");
    } else {
        $data_Atual = date_converter($data2) . $hora2;
    }
    $date_time = new DateTime($data_Atual);
    $diff = $date_time->diff(new DateTime($data_Inicial));
    return $diff->format('%m mês(es), %d dia(s), %H hora(s), %i minuto(s)');
}


function calculaTempo($hora_inicial, $hora_final) {
    $i = 1;
    $tempo_total = null;

    $tempos = array($hora_final, $hora_inicial);

    foreach ($tempos as $tempo) {
        $segundos = 0;

        list($h, $m, $s) = explode(':', $tempo);
        $segundos += $h * 3600;
        $segundos += $m * 60;
        $segundos += $s;
        $tempo_total[$i] = $segundos;
        $i++;
    }
    $segundos = $tempo_total[1] - $tempo_total[2];

    $horas = floor($segundos / 3600);
    $segundos -= $horas * 3600;
    $minutos = str_pad((floor($segundos / 60)), 2, '0', STR_PAD_LEFT);
    $segundos -= $minutos * 60;
    $segundos = str_pad($segundos, 2, '0', STR_PAD_LEFT);

    return "$horas:$minutos:$segundos";
}

function mesPorExtenso($mesNum) {
    $meses = [
        '', 'Janeiro', 'Fevereiro', 'Março', 'Abril',
        'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro',
        'Outubro', 'Novembro', 'Dezembro'
    ];
    return $meses[intval($mesNum)];
}

function diasNoMes($mes, $ano) {
    $m = intval($mes);
    $a = intval($ano);

    if($m == 1 || $m == 3 || $m == 5 || $m == 7 || $m == 8 || $m == 10 || $m == 12) { return 31;
    } else if($m == 4 || $m == 6 || $m == 9 || $m == 11) {                            return 30;
    } else if($a % 4 == 0 && $m == 2) {                                               return 29;
    } else if($m == 2) {                                                              return 28;
    } else {                                                                          return 0;
    }
}

function diaPorExtenso($dia, $mes, $ano) {
    return $dia . ' de ' . mesPorExtenso($mes) . ' de ' . $ano;
}

function diaPorExtensoComLocal($dia, $mes, $ano, $cidade, $uf) {
    return $cidade . ' - ' . $uf . ', ' . diaPorExtenso($dia, $mes, $ano);
}

?>