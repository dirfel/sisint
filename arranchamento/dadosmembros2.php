<?php
require "../recursos/models/versession.php";
include "../recursos/models/conexao.php";

if ($_SESSION['auth_data']['contarancho'] < "3") {
  header('Location: index.php');
  exit();
}

$p1 = conectar("membros");
$p2 = conectar("arranchamento");
$sistema = base64_encode('SISTEMA DE ARRANCHAMENTO');
$idmembro = $_SESSION['auth_data']['id'];
$idusuario = filter_input(INPUT_POST, "usuario");
$consulta = $p1->prepare("SELECT * FROM usuarios WHERE id = $idusuario AND userativo = 'S'");
$consulta->execute();
$users = $consulta->fetchAll(PDO::FETCH_ASSOC);
$user = $users[0];
$nomeguerra = $user['nomeguerra'];
$postograd = getPGrad($user['idpgrad']);
$confirmado = "SIM";

$cafe = $p2->prepare("SELECT * FROM arranchado WHERE iduser = :idusuario AND cafe = :cafe");
$cafe->bindParam(':idusuario', $idusuario);
$cafe->bindParam(':cafe', $confirmado);
$cafe->execute();
$oscafes = $cafe->fetchAll(PDO::FETCH_ASSOC);
$totalcafe = count($oscafes);
if ($totalcafe > 0) {
  $ultimocafe = $oscafes[$totalcafe - 1];
  $datacafe = $ultimocafe['data'];
} else {
  $datacafe = "***";
}

$almoco = $p2->prepare("SELECT * FROM arranchado WHERE iduser = :idusuario AND almoco = :almoco");
$almoco->bindParam(':idusuario', $idusuario);
$almoco->bindParam(':almoco', $confirmado);
$almoco->execute();
$osalmocos = $almoco->fetchAll(PDO::FETCH_ASSOC);
$totalalmoco = count($osalmocos);
if ($totalalmoco > 0) {
  $ultimoalmoco = $osalmocos[$totalalmoco - 1];
  $dataalmoco = $ultimoalmoco['data'];
} else {
  $dataalmoco = "***";
}

$janta = $p2->prepare("SELECT * FROM arranchado WHERE iduser = :idusuario AND jantar = :janta");
$janta->bindParam(':idusuario', $idusuario);
$janta->bindParam(':janta', $confirmado);
$janta->execute();
$osjantas = $janta->fetchAll(PDO::FETCH_ASSOC);
$totaljanta = count($osjantas);
if ($totaljanta > 0) {
  $ultimojanta = $osjantas[$totaljanta - 1];
  $datajanta = $ultimojanta['data'];
} else {
  $datajanta = "***";
}
?>
<!doctype html>
<html lang="pt-BR" class="fixed left-sidebar-collapsed">

<head><?php include '../recursos/views/cabecalho.php'; ?></head>

<body>
  <div class="wrap">
    <div class="page-header"><?php render_painel_usu('ARRANCHAMENTO', $_SESSION['nivel_arranchamento']); ?></div>
    <div class="page-body">
      <div class="left-sidebar"><?php include 'menu_opc.php'; ?></div>
      <div class="content">
      <?php render_content_header('Informação individual do '. $postograd . ' ' . $nomeguerra, 'fa fa-history'); ?>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-md-4">
            <?php render_card2('', '', 'icon fa fa-user', 
            '<h4 class="subtitle color-darker-1">'.$postograd.'</h4><h1 class="title color-primary">'. $nomeguerra.'</h1>', '', '') ?>
            <?php render_card2('', '', 'icon fa fa-coffee', 
            '<h4 class="subtitle color-darker-2"><b>Arranchamento para o Café</b></h4>
            <h4 class="subtitle color-darker-2" style="padding-top:4px;">Arranchamento totais: <b>'.$totalcafe.'</b></h4>
            <h4 class="subtitle color-darker-2" style="padding-top:4px;">Último Arranchamento: <b>'.$datacafe.'</b></h4>', '', '') ?>
            <?php render_card2('', '', 'icon fas fa-drumstick-bite', 
            '<h4 class="subtitle color-darker-2"><b>Arranchamento para o Almoço</b></h4>
            <h4 class="subtitle color-darker-2" style="padding-top:4px;">Arranchamento totais: <b>'.$totalalmoco.'</b></h4>
            <h4 class="subtitle color-darker-2" style="padding-top:4px;">Último Arranchamento: <b>'.$dataalmoco.'</b></h4>', '', '') ?>
            <?php render_card2('', '', 'icon fas fa-hotdog', 
            '<h4 class="subtitle color-darker-2"><b>Arranchamento para o Jantar</b></h4>
            <h4 class="subtitle color-darker-2" style="padding-top:4px;">Arranchamento totais: <b>'.$totaljanta.'</b></h4>
            <h4 class="subtitle color-darker-2" style="padding-top:4px;">Último Arranchamento: <b>'.$datajanta.'</b></h4>', '', '') ?>
          </div>
          <div class="col-sm-12 col-md-8">
            <div class="panel"><?php render_cabecalho_painel('HISTÓRICO DE ARRANCHAMENTOS:', 'fa fa-eye', true); ?>
              <div class="panel-content">
                <table id="responsive-table" class="tabela table table-striped table-hover responsive nowrap" width="100%">
                  <thead>
                    <tr>
                      <th>Registro</th><th>Data</th><th>Café</th><th>Almoço</th><th>Jantar</th>
                      <th>Modo</th><th>Data Grav</th><th>Hora Grav</th><th>Quem foi?</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $listarancho = $p2->prepare("SELECT * FROM arranchado WHERE iduser = :iduser");
                    $listarancho->bindParam(':iduser', $idusuario);
                    $listarancho->execute();
                    while ($reg2 = $listarancho->fetch(PDO::FETCH_ASSOC)) :
                      echo ("<tr>");
                      echo ("<td>".$reg2['id']."</td><td>".$reg2['data']."</td><td>".$reg2['cafe']."</td>");
                      echo ("<td>".$reg2['almoco']."</td><td>".$reg2['jantar']."</td><td>".$reg2['modo']."</td>");
                      echo ("<td>".$reg2['datagrava']."</td><td>".$reg2['horagrava']."</td><td>".$reg2['quemgrava']."</td>");
                      echo ("</tr>");
                    endwhile; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php include '../recursos/views/scroll_to_top.php'; ?>
    </div> 
  </div>
  <?php include '../recursos/views/footer.php'; ?>
  <script type="text/javascript">
    $(".tabela").DataTable({
      order: [
        [0, "desc"]
      ],
      scrollY: "500px",
      scrollCollapse: true,
      paging: false,
      searching: false
    });
  </script>
</body>

</html>