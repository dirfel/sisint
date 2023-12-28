<?php

define('PGRADS', [
    -1 => 'Gen Ex',
     0 => 'Gen Div',
     1 => 'Gen Bda',
     2 => 'Cel',
     3 => 'Ten Cel',
     4 => 'Maj',
     5 => 'Cap',
     6 => '1º Ten',
     7 => '2º Ten',
     8 => 'Asp',
     9 => 'STen',
     10 => '1º Sgt',
     11 => '2º Sgt',
     12 => '3º Sgt',
     14 => 'Cb',
     15 => 'Sd EP',
     16 => 'Sd EV',
     17 => 'Civil',
  ]); // essa constante define os postos e graduação existentes no sistema. Caso deseje, crie novos desde que sejam valores inteiros

/**
 * função básica para converter id de posto e guaduação na respectiva abreveatura
 */
function getPGrad($id) {
    if($id == 30) { // 30 é o valor que o sistema trata quando não há posto/graduação cadastrado
        return '';
    }
    return PGRADS[$id] ?? '';
}

?>