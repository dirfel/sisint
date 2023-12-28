<?php
date_default_timezone_set("America/Cuiaba");
include "../recursos/models/conexao.php";

echo "<table border=1 width=100% cellpadding=3 cellspacing=0>\n";
echo "<tr>";
echo "<th align='center' valign='middle' width=80%>";
echo "<font size=2.5> ". strtoupper(NOME_OM) ." </font><br/>";
echo "<font size=2.5> ". strtoupper(NOME_HISTORICO_OM) ." </font><br/><br/>";
echo "<font size=2> RELATÓRIO DE MILITARES E SUAS DATAS DE ANIVERSÁRIO </font><br/>";
echo "</th>";
echo "</tr>";
echo "</table>";
echo "<p>";
?>
<html lang="pt-BR" class="fixed">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>ANIVERSARIANTES</title>
  <link rel="apple-touch-icon" sizes="120x120" href="favicon/favicon_guarda.png">
  <link rel="icon" type="image/png" sizes="192x192" href="favicon/favicon_guarda.png">
  <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon_guarda.png">
  <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon_guarda.png">
  <style>
    @media print {
      .noprint {
        display: none;
      }
    }
  </style>
</head>

<body>
  <!-- inicio novo plugin -->
  <h2 class="noprint">Gerar com filtros</h2>
  <form class="noprint" action="militares_aniversariantes.php" method="get">
    <!-- checkbox com os meses para exibir -->
    <?php 
    render_checkbox('Janeiro', 'mes1', 'mes[]', '01', false);
    render_checkbox('Fevereiro', 'mes2', 'mes[]', '02', false);
    render_checkbox('Março', 'mes3', 'mes[]', '03', false);
    render_checkbox('Abril', 'mes4', 'mes[]', '04', false);
    render_checkbox('Maio', 'mes5', 'mes[]', '05', false);
    render_checkbox('Junho', 'mes6', 'mes[]', '06', false);
    render_checkbox('Julho', 'mes7', 'mes[]', '07', false);
    render_checkbox('Agosto', 'mes8', 'mes[]', '08', false);
    render_checkbox('Setembro', 'mes9', 'mes[]', '09', false);
    render_checkbox('Outubro', 'mes10', 'mes[]', '10', false);
    render_checkbox('Novembro', 'mes11', 'mes[]', '11', false);
    render_checkbox('Dezembro', 'mes12', 'mes[]', '12', false); 
    ?>

    
    <label for="posto">Círculo:</label>
    <select name="circulo" id="circulo">
      <option value="0">Todos</option>
      <option value="1">Of/Sten/Sgt</option>
      <option value="2">Cb/Sd</option>
    </select>
    <label for="subunidade">Subunidade:</label>
    <select name="subunidade" id="subunidade">
      <option value="0">Todas</option>
      <?php $registros = listar_subunidades();
      $totreg = count($registros);
      for ($i = 0; $i < $totreg; $i++) {
        $reg = $registros[$i];
        echo "<option value='" . $reg['id'] . "'>" . $reg['descricao'] . "</option>";
      } ?>
    </select>
    <input type="submit" value="Filtrar" />
  </form>
  <?php
  $sql = "SELECT * FROM usuarios WHERE userativo = 'S' AND ";
  // defino os meses a buscar, se não houver, busco todos
  $lista_meses = array();
  if (isset($_GET['mes']) && count($_GET['mes']) > 0) {
    if (count($_GET['mes']) > 1) { $sql .= "("; }
    $lista_meses = $_GET['mes'];
    foreach ($lista_meses as $mes) { $sql .= "extract(month from datanascimento2) = " . $mes . " OR "; }
    if (count($lista_meses) > 0) { $sql = substr($sql, 0, -3); }
    if (count($_GET['mes']) > 1) { $sql .= ")"; }
    $sql .= " AND ";
  }
  $circulo = 0;
  if (isset($_GET['circulo'])) {
    if($_GET['circulo'] == 1){
      $circulo = 1;
      $sql .= " (idpgrad < 14) AND ";
    } else if($_GET['circulo'] == 2){
      $circulo = 2;
      $sql .= " (idpgrad >= 14) AND ";
    }

  }
  $subunidade = 0;
  if (isset($_GET['subunidade']) && $_GET['subunidade'] != 0) {
    $subunidade = $_GET['subunidade'];
    $sql .= " (idsubunidade = " . $subunidade . ") AND ";
  }
    
  $sql = substr($sql, 0, -4);
  $sql .= " ORDER BY extract(month from datanascimento2), extract(day from datanascimento2)";

  $pdo = conectar("membros");
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $totreg = count($registros);
    if ($totreg > 0) {
      echo "<table border=1 width=100% cellpadding=3 cellspacing=0>\n";
      echo "<tr>";
      echo "<th align='center' valign='middle'><font size=1.0> Nº </font></th>";
      echo "<th align='center' valign='middle'><font size=1.0> ANIVERSÁRIO </font></th>";
      echo "<th align='center' valign='middle'><font size=1.0> IDADE A COMPLETAR </font></th>";
      echo "<th align='center' valign='middle'><font size=1.0> MILITAR </font></th>";
      echo "<th align='center' valign='middle'><font size=1.0> SUBUNIDADE </font></th>";
      echo "</tr>\n";
      $date_now = date('md');
      for ($i = 0; $i < $totreg; $i++) {
        $reg = $registros[$i];
        $mdata = $reg['datanascimento'];
        $descsu4 = listar_subunidades($reg['idsubunidade']);
        $SUnidade4 = $descsu4[0];
        echo "<tr>";
        echo "<th align='center' valign='middle'><font size=1.0> " . ($i + 1) . " </font></th>";
        echo "<th align='center' valign='middle'><font size=1.0> " . $reg['datanascimento'] . " </font></th>";
        $nasc = explode("/", $reg['datanascimento']);
        $age;
        $age = (date('md', date('U', mktime(0, 0, 0, intval($nasc[0] ?? 0), intval($nasc[1] ?? 0), intval($nasc[2] ?? 0)))) > $date_now
            ? ((date('Y') -  ($nasc[2] ?? 0)) - 1)
            : (date('Y') - ($nasc[2] ?? 0)));

        echo "<th align='center' valign='middle'>";
        echo "<font size=1.0> " . $age . " </font>";
        echo "</th>";
        echo "<th align='center' valign='middle'>";
        echo "<font size=1.0> " . getPGrad($reg['idpgrad']) . " " . $reg['nomeguerra'] . " </font>";
        echo "</th>";
        echo "<th align='center' valign='middle'>";
        echo "<font size=1.0> " . $SUnidade4['descricao'] . " </font>";
        echo "</th>";
        echo "</tr>\n";
    }
      echo "</table>";
    } else {
      echo "Nenhum registro encontrado";
    }
  ?>
  
</body>

</html>