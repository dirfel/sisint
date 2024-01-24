<?php

/**
 * Esse é o arquivo mais importante dos Sistemas Integrados, fiz questão de super documentá-lo pois ele merece!
 * Aqui estão definidas todas as variáveis de configuração, todas as dependências que permitem o sistema funcionar.
 * Caso distribua esse sistema a outra OM, é importante remover senhas salvas aqui
 */

$isTest = true; // Essa variável deve ser usada APENAS em ambiente de desenvolvimento e testes 

date_default_timezone_set("America/Cuiaba"); // Define o fuso horário do sistema

/* Configurações do Banco de dados */
define('DB_DRIVER', 'mysql');
define('DB_HOST', 'localhost');
if($isTest) {
  // caso esteja no ambiente de desenvolvimento ou teste, essa será a senha
  define('DB_USER', 'root');
  define('DB_PWD', '');
} else {
  // em ambiente de produção será essa
  define('DB_USER', 'Mudar aqui');
  define('DB_PWD', 'Mudar aqui');
}

/* Configurações da organização militar que executa o sistema */
define('SISBOL_URL', 'http://10.10.10.10/band/'); // colocar o endedeço com o caminho para a pasta band
define('NOME_OM', '3ª Bateria de Artilharia Antiaérea'); // colocar o nome por extenso somente com as iniciais em maiúsculo
define('NOME_HISTORICO_OM', 'Bateria Presidente Ernesto Geisel'); // colocar o nome por extenso somente com as iniciais em maiúsculo
define('ORIGEM', '3ª Bia A AAé 1978'); // esse é o texto entre parênteses
define('ABR_OM', '3ª Bia AAAe'); // colocar o nome abreviado da om
define('PREPOSICAO', 'à'); // 'à' Bateria ou 'ao' batalhão
define('SUBORDINACAO', '4ª Brigada de Cavalaria Mecanizada'); // nome da om a qual esta se subordina
define('CIDADE', 'Três Lagoas'); // nome da cidade que a om está localizada
define('UF', 'MS'); // Abreviatura do estado

/* Configurações do sistema */
define('PASTA_RAIZ', '/var/www/html/sisint/'); // caminho da raiz do codigo php
define('PASTA_BKP', '/var/www/bkp/'); // caminho da pasta de backup do codigo php. Exige que a pasta seja do usuario www-data. Corrije com o comando: "chown www-data /var/www/bkp"

/**
 * @param Atenção! Não modificar nada daqui para baixo
 */

/* Recursos que estão integrados a esta página */
include_once '../recursos/classes/pdo.php';            // Conexão com o banco de dados mysql
include_once '../recursos/widgets/layout_widgets.php'; // Layout de cards para exibição padronizada ao usuario final
include_once '../recursos/widgets/card.php';           // Layout de cards para exibição padronizada ao usuario final
include_once '../recursos/widgets/form_fields.php';    // campos de texto, menus dropdown, checkbox, radiobuttom, ...
include_once '../recursos/widgets/documentos.php';     // elementos presentes em relatórios criados para impressão
include_once '../recursos/widgets/macro_widgets.php';  // formularios mais complexos prontos
include_once '../recursos/classes/visitante.php';      // funções relacionadas a visitantes
include_once '../recursos/classes/bairros.php';        // funções relacionadas a bairros
include_once '../recursos/classes/su.php';             // funções relacionadas a subunidades
include_once '../recursos/classes/viaturas.php';       // funções relacionadas a viaturas militares
include_once '../recursos/classes/pedido_vtr.php';     // funções relacionadas a pedidos de viatura
include_once '../recursos/classes/usuarios.php';       // funções relacionadas a usuarios
include_once '../recursos/classes/pgrad.php';          // funções relacionadas a posto e graduação de militares
include_once '../recursos/classes/cpf.php';            // funções relacionadas a cpf de pessoas
include_once '../recursos/classes/datahora.php';       // implementa ajustes de data e hora
include_once '../recursos/classes/logs.php';           // logs de desenvolvimento e logs de uso pelo usuário
include_once '../recursos/classes/bkp.php';            // gera backup do código fonte em /var/www/bkp/
include_once '../recursos/models/file_upload.php';     // abstrai mecanismo de upload de arquivos no sistema
