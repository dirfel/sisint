<?php
// neste arquivo vamos definir os níveis de acesso que podem ser usados em todos os sistemas
// e criaremos um método para importar o nome do nível de acesso.

$niveis = array(
    'arranchamento' => array(
        0 => 'Sem acesso',
        1 => 'Furriel',
        2 => 'Aprovisionador',
        3 => 'Administrador'
    ),
    // 'fatosobservados' => array(), ->> Todos of, sten e sgt possuem acesso apenas
    'guarda' => array(
        0 => 'Sem acesso',
        1 => 'Anotador',
        2 => 'Cabo Gda',
        3 => 'Oficial e Sgt',
        4 => 'Supervisor',
        5 => 'Administrador'
    ),
    'helpdesk' => array(
        0 => 'Sem acesso',
        1 => 'Usuário Comum',
        2 => 'Supervisor',
        3 => 'Administrador'
    ),
    'planodechamada' => array(
        0 => 'Sem acesso',
        1 => 'Usuário Comum (Of Dia)',
        2 => 'Supervisor (Cmt fração, Sgte, S1, Cmt e Scmt)',
        3 => 'Administrador (Seç TI)'
    ),
    'res_armt' => array(
        0 => 'Sem acesso',
        1 => 'Supervisor',
        2 => 'Armeiro',
        3 => 'Administrador'
    ),
    'scd' => array(
        0 => 'Sem acesso',
        1 => 'Supervisor',
        2 => 'Armeiro',
        3 => 'Administrador'
    ),
    'sis_cautela' => array(
        '0' => 'Usuario Comum',
        'A' => 'Aux Enc Mat',
        'S' => 'Enc Mat',
    ), // o segundo dígito informa qual o deposito que o usuário não comum tem acesso
    'sist_com_soc' => array(
        0 => 'Sem acesso',
        1 => 'Usuário Comum',
        2 => 'Conformador',
        3 => 'Gestor HT',
        4 => 'Ch Com Soc',
        5 => 'Supervisor',
        6 => 'Administrador'
    ),
);

?>