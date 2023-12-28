<?php
// importar arquivos necessários para executar o código
include "../recursos/models/conexao.php";
require "../recursos/models/versession.php";
if ($_SESSION['nivel_com_soc'] == "Sem Acesso") {
    $msgerro = base64_encode('Usuário não possui acesso!');
    header('Location: ../sistemas/index.php?token=' . $msgerro);
    exit();
}
// calcula quantos dias a frente serão carregados
$max_days = 31;
if(isset($_GET['max_days'])) {
    $max_days = $_GET['max_days'];
}

// conectar os banco de dados necessários
$pdo1 = conectar("membros");
$pdo3 = conectar("sistcomsoc");
$cadastros;

?>
<!doctype html>
<html lang="pt-BR" class="fixed">
    
    <head>
        <?php include '../recursos/views/cabecalho.php'; ?>
    </head>
    
    <body>
        <div class="wrap">
            <div class="page-header">
            <?php render_painel_usu('SERVIÇOS COM SOC', $_SESSION['nivel_com_soc']); ?>
    </div>
    <div class="page-body">
      <div class="left-sidebar">
          <?php include 'menu_opc.php'; ?>
      </div>
      <div class="content">
      <?php render_content_header('Conformidade', 'fa fa-home'); ?>
        <div class="row animated zoomInDown">
          <?php include '../recursos/views/token.php'; ?>
          <div class="col-sm-12 col-md-9">
          <form id="validation" action="insert_conformidade.php" method="post">
              <div class="panel"><?php render_cabecalho_painel('REGISTRAR CONFORMIDADE:', 'fa fa-street-view', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-md-12 mb-sm"><h4>Conta 160521</h4></div>
                    <div class="col-md-4 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Restrição:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fas fa-id-card-alt"></i></span>
                          <select name="status_ug1" class="form-control select select2-hidden-accessible" style="width: 100%" required="Preenchimento obrigatório" tabindex="-1" aria-hidden="true">
                            <option></option>
                            <optgroup label="Selecione a UASG">
                                <option value="S" selected>Sem Restrição</option>
                                <option value="C">Com Restrição</option>
                            </optgroup>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-8 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Descrição: (Se houver restrição)</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fas fa-compass"></i></span>
                          <input type="text" class="form-control maxLength" name="descricao_ug1" placeholder="Motivo" maxlength="1000">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12 mb-sm"><hr></div>
                    <div class="col-md-12 mb-sm"><h4>Conta 167521</h4></div>
                    <div class="col-md-4 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Restrição:</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fas fa-id-card-alt"></i></span>
                          <select name="status_ug2" class="form-control select select2-hidden-accessible" style="width: 100%" required="Preenchimento obrigatório" tabindex="-1" aria-hidden="true">
                            <option></option>
                            <optgroup label="Selecione a UASG">
                                <option value="S" selected>Sem Restrição</option>
                                <option value="C">Com Restrição</option>
                            </optgroup>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-8 mb-sm">
                      <div class="form-group">
                        <label for="form-group" class="control-label">Descrição: (Se houver restrição)</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fas fa-compass"></i></span>
                          <input type="text" class="form-control maxLength" name="descricao_ug2" placeholder="Motivo" maxlength="1000">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                        <hr>
                        <div class="form-group">
                            <label for="form-group" class="control-label">Responsável pela conformidade diária:</label>
                            <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-street-view"></i></span>
                              <select name="conformador" class="form-control select select2-hidden-accessible" style="width: 100%" required="Preenchimento obrigatório" tabindex="-1" aria-hidden="true">
                              <optgroup label="Selecione um militar">
                                <option></option>
                                <?php $sql = 'SELECT * FROM conformador';
                                $stmt = $pdo3->prepare($sql);
                                $stmt->execute();
                                $id_conf = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]['id_conformador'];

                                $sql = 'SELECT id, idpgrad, nomeguerra FROM usuarios WHERE userativo = "S" AND idpgrad <= 13 ORDER BY idpgrad ASC, nomeguerra ASC';
                                $stmt = $pdo1->prepare($sql);
                                $stmt->execute();
                                $reg1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                foreach($reg1 as $chave) { ?>
                                <option value="<?= $chave["id"]?>"<?=$id_conf == $chave['id'] ? ' selected' : ''?>>
                                    <?= getPGrad($chave["idpgrad"])?> <?=$chave["nomeguerra"]?>
                                </option>
                                <?php } ?>
                              </optgroup>  
                              </select>
                            </div>
                          </div>
                          <hr>
                          <button type="submit" name="action" value="registrar" class="btn btn-primary" style="width: 140px;">Registrar</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="col-sm-12 col-md-3">
          <form id="validation" action="test.php" method="post">
              <div class="panel"><?php render_cabecalho_painel('HISTÓRICO:', 'fa fa-history', true); ?>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-md-12 mb-sm">
                    <table class="table text-center">
                      <thead><tr><th>Dia</th><th>160521</th><th>167521</th></tr></thead>
                      <tbody>
                        <?php
                            // essa consulta retornará todos os hospedes que a data de checkout já passou
                            $estemes = date('m/Y');
                            $messeguinte = date('m/Y', strtotime('+1 month'));
                            $consulta3 = $pdo3->prepare('SELECT * FROM conformidade ORDER BY id DESC');
                            $consulta3->execute();
                            $reg3 = $consulta3->fetchAll(PDO::FETCH_ASSOC);
                        // gero uma linha pra cada dia
                        for($i=0;$i<$max_days;$i++){
                            $diaria = date("d/m", time() - 86400 * $i);
                            $diaria2 = date("Ymd", time() - 86400 * $i);
                            // para cada elemento em reg 3 verifico se checkin <= hoje e checkout >= hoje
                            $txt1 = '-';
                            $color1 = '';
                            $title1 = 'title="Não registrado"';

                            $txt2 = '-';
                            $color2 = '';
                            $title2 = 'title="Não registrado"';

                            foreach($reg3 as $registro) {
                                $dateregistro = date('Ymd', strtotime($registro['date']));
                                if($dateregistro == $diaria2) {
                                    $txt1 = $registro['status_ug1'];
                                    $txt2 = $registro['status_ug2'];
                                    if($registro['status_ug1'] == 'C') {
                                        $color1 = 'btn btn-warning';
                                        $title1 = 'title="Com Restrição: '.$registro['descricao_ug1'].'"';
                                    } else {
                                        $color1 = 'btn btn-success';
                                        $title1 = 'title="Sem Restrição"';
                                    }
                                    if($registro['status_ug2'] == 'C') {
                                        $color2 = 'btn btn-warning';
                                        $title2 = 'title="Com Restrição: '.$registro['descricao_ug2'].'"';
                                    } else {
                                        $color2 = 'btn btn-success';
                                        $title2 = 'title="Sem Restrição"';
                                    }
                                    break;
                                }
                            }                            
                            echo '<tr>';
                                echo '<th>' . $diaria . '</td>';
                                echo '<td><a data-toggle="tooltip" data-placement="top" class="'.$color1.'" '. $title1 .'>'.$txt1.'</a></td>';
                                echo '<td><a data-toggle="tooltip" data-placement="top" class="'.$color2.'" '. $title2 .'>'.$txt2.'</a></td>';
                            echo '</tr>'; } ?>
                      <tbody>
                    </table>
                    <a href="conformidade.php?max_days=<?= (intval($max_days) + 30) ?>" class="btn btn-primary">Carregar mais 30 dias</a>
                    </div>
                  </div>
                </div>
              </div>
          </div>
        </div>
        <div class="row animated fadeInUp">
          <?php include '../recursos/views/token.php'; ?>
        </div>
      </div>
       <?php include '../recursos/views/scroll_to_top.php'; ?>
    </div>
  </div>
  <?php include '../recursos/views/footer.php'; ?>
</body>

</html>