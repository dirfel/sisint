<?php



/** Aqui será armazenado métodos para interagir com a tabela usuarios e outras relacionadas */

/**
 * Essa função retorna um array com a informação de TODOS os usuarios (ativos e inativos).
 * cada elemento desse array será um novo array com os dados do usuario.
 * Obs: sv ev, civis e dependentes de militar não possuem dependentes
 */
function read_usuarios() {
    $stmt = conectar('membros')->prepare('SELECT * FROM usuarios WHERE (idpgrad != 16) ORDER BY idpgrad, nomecompleto ASC');
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function titulares_fusex() {
  $stmt = conectar('guarda')->prepare('SELECT * FROM visitante WHERE (tipo LIKE "%Militar%") AND (tipo NOT LIKE "%dependente%") AND (idpgrad != 16)'); 
  $stmt->execute();
  $titulares =  $stmt->fetchAll(PDO::FETCH_ASSOC);
  $result = array();
  foreach($titulares as $titular) {
    $titular['id'] = '-' . $titular['id'];
  }

  $titulares = array_merge($titulares, read_usuarios());
  return $titulares;
}

/**
 * Essa função retorna um array com a informação dos usuarios ativos (@param userativo = "S") ou inativos (@param userativo != "S").
 * cada elemento desse array será um novo array com os dados do usuario.
 */
function read_usuarios_situacao($userativo) {
    $sql = $userativo == "S" ? 'SELECT * FROM usuarios WHERE userativo = "S" ORDER BY idpgrad, nomecompleto ASC' : 'SELECT * FROM usuarios WHERE NOT userativo = "S" ORDER BY idpgrad, nomecompleto ASC';
    $stmt = conectar('membros')->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Essa função retorna um array com a informação de um usuário pelo @param id ou null caso de erro
 */
function read_usuario_by_id($id) { 
    $sql = 'SELECT * FROM usuarios WHERE id = :id';
    $stmt = conectar('membros')->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() != 1) {
        return null;
    } else {
        return $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
    }
}
/* Funções de formatação de nome de militares */

/* Funções de Formatação */

/**
 * Essa função recebe o @param mil, um array contendo informações do militar e retorna uma String no formato:
 * postograd nomecompleto (nomeguerra)
 */
function ImprimeConsultaMilitar($mil) {
    return ((getPGrad($mil['idpgrad'] ?? 0) ) . " - " . ($mil['nomecompleto'] ?? '') . " (" . ($mil['nomeguerra'] ?? '') . ")");
  }
  
  /**
   * Essa função recebe o @param mil, um array contendo informações do militar e retorna uma String no formato:
   * postograd nomeguerra
   */
  function ImprimeConsultaMilitar2($mil) {
  
    $retorno = (getPGrad($mil['idpgrad']) . " " . $mil['nomeguerra']);
    
    return $retorno;
  }
  
  /* Funções de busca no banco de dados + formatação */
  
  /**
   * Essa função recebe o @param id do militar, busca por esse id no banco de dados e retorna uma String no formato:
   * postograd nomecompleto (nomeguerra)
   */
  function consultaMilitar($id) {
    $consultaReg = read_usuario_by_id($id);
  
    return ImprimeConsultaMilitar($consultaReg);
  }

  /**
   * Essa função recebe o @param id do militar, busca por esse id no banco de dados e retorna uma String no formato:
   * postograd nomeguerra
   */
  function consultaMilitar3($id) {
    $consultaReg = read_usuario_by_id($id);
  
    return ImprimeConsultaMilitar2($consultaReg) ?? 'Registro não encontrado';
  }
  
  
  /**
   * Essa função recebe o @param id de um usuário e retorna uma string contendo o posto e graduação e o nome completo
   */
  function consultaMilitar2($id) {
      $consulta = conectar("membros")->prepare("SELECT id, nomeguerra, nomecompleto, idpgrad FROM usuarios WHERE id = :id AND userativo = 'S'");
    $consulta->bindParam(":id", $id, PDO::PARAM_INT);
    $consulta->execute();
    $consultaReg = $consulta->fetchAll();
    $consultaResultado = (getPGrad($consultaReg[0]['idpgrad']) . " - " . $consultaReg[0]['nomecompleto']);
    
    return $consultaResultado;
  }
  
  /**
   * Essa função recebe o @param id de um usuário e retorna uma string contendo o posto e graduação e o nome completo
   */
  function consultaMilitarAssinatura($id) {
      $consulta = conectar("membros")->prepare("SELECT id, nomeguerra, nomecompleto, idpgrad FROM usuarios WHERE id = :id AND userativo = 'S'");
    $consulta->bindParam(":id", $id, PDO::PARAM_INT);
    $consulta->execute();
    $consultaReg = $consulta->fetchAll();
    $consultaResultado = ($consultaReg[0]['nomecompleto'] . " - " . getPGrad($consultaReg[0]['idpgrad']));
    
    return $consultaResultado;
  }
  
  
  
  
  
  function consultaMilitarSelection($pgrad) {
    // Essa função recebe um pgrad e retorna uma array de usuarios ativos contendo:
    //     1. id (critério de ordenamento)
    //     2. Nome completo
    //     3. Nome de guerra
    //     4. Posto ou graduação
    $consulta = conectar("membros")->prepare("SELECT id, nomecompleto, idpgrad, nomeguerra FROM usuarios 
      WHERE (userativo = 'S' AND idpgrad >= :pgrad) ORDER BY idpgrad, nomecompleto ASC");
    $consulta->bindParam(":pgrad", $pgrad, PDO::PARAM_INT);
    $consulta->execute();
    $consultaReg = $consulta->fetchAll();
  
    return $consultaReg;
  }
  




?>