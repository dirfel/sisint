<?php
/**
 * Nesse script estão implementados alguns widgets mais complexos que possam ser usados em mais de um lugar no sistema.
 * Essa prática facilita a reutilização de código no sistema de forma prática.
 */

/**
 * Painel de cadastro ou atualização de usuario
 */
function render_painel_dados_usuario($user_array = array())  { ?>
  <div class="col-md-6">
    <?php render_nome_completo_input('nome', $user_array['nomecompleto'] ?? ''); ?>
    <?php render_custom_input('Nome de Guerra:', 'inputMaxLength2', 'guerra', $user_array['nomeguerra'] ?? '', 50, 'Nome de Guerra', true, true, 'fa fa-user');?>
    <?php render_pgrad_select('pgrad', $user_array['idpgrad'] ??'30', true); ?>
    <?php render_custom_input('Endereço:', 'inputMaxLength3', 'endereco', $user_array['endereco'] ?? '', 100, 'Endereço e número da residência', true, false, 'fa fa-map');?>
    <?php render_bairros_select('bairro', true, $user_array['bairro'] ?? ''); ?>
    <?php render_custom_input('Cidade:', 'inputMaxLength4', 'cidade', $user_array['cidade'] ??'Três Lagoas', 100, 'Cidade', true, false, 'fa fa-location');?>
    <?php render_custom_input('Estado:', 'inputMaxLength5', 'estado', $user_array['estado'] ??'Mato Grosso do Sul', 100, 'Estado', true, false, 'fa fa-location');?>
  </div>
  <div class="col-md-6">
    <?php render_subunidades_select('subunidade', true, $user_array['idsubunidade'] ?? '30');?>
    <?php render_email_field('email', false, 'E-mail', $user_array['email'] ?? '');?>
    <?php render_telefone_field('fonefixo', false, 'Telefone fixo:', false, $user_array['fixo'] ?? '');?>
    <?php render_telefone_field('fonecelular', true, 'Telefone celular:', true, $user_array['celular'] ?? '');?>
    <?php render_data_field('datanascimento', true, 'Data de nascimento:', $user_array['datanascimento'] ?? '');?> 

    <div class="form-group">
      <label for="phone-mask">Identidade: <i class="fa fa-info-circle mr-xs"></i>
        Essa informação será <span class="code">CRIPTOGRAFADA</span>
      </label>
      <div class="input-group mb-sm">
        <span class="input-group-addon"><i class="fa fa-user-secret"></i></span>
        <input type="text" class="form-control" name="identidade" value="<?= base64_decode($user_array['identidade'] ?? '')?>" placeholder="Identidade - Apenas números" required="Preenchimento obrigatório">
      </div>
    </div>
    <div class="form-group">
      <label for="phone-mask">CPF: <i class="fa fa-info-circle mr-xs"></i>
        Essa informação será <span class="code">CRIPTOGRAFADA</span>
      </label>
      <div class="input-group mb-sm">
        <span class="input-group-addon"><i class="fa fa-user-secret"></i></span>
        <input type="text" class="form-control cpf maxLength" name="cpf" value="<?= base64_decode($user_array['cpf'] ?? '')?>" placeholder="CPF" minlength="14" maxlength="14" required="Preenchimento obrigatório">
      </div>
    </div>
  </div>
<?php }




/**
 * Formulário completo de cadastro/edição de visitante ou militar de outra OM
 */
function render_form_cadastro_visitante($action, $viz_array = array()){ ?>
  <form id="validation" action="<?=$action?>" method="post">
    <div class="panel"><?php render_cabecalho_painel(($viz_array == array() ? 'CADASTRAR' : 'EDITAR').' VISITANTE OU MILITAR DE OUTRA OM', 'fa fa-street-view', true); ?>
      <div class="panel-content"> 
        <div class="row">
        <div class="col-md-4 mb-sm"><?php render_pgrad_select('idpgrad', $viz_array['idpgrad'] ?? 30, true); ?></div>
          <div class="col-md-8 mb-sm"><?php render_nome_completo_input('nomecompleto', $viz_array['nomecompleto'] ?? ''); ?></div>
          <div class="col-md-12 mb-sm"></div>
          <div class="col-md-6 mb-sm"><?php render_select_cat_visitante('tipo', $viz_array['tipo'] ?? '', true); ?></div>
          <div class="col-md-3 mb-sm"><?php render_identidade_input('identidade', $viz_array['identidade'] ?? ''); ?></div>
          <div class="col-md-3 mb-sm"><?php render_cpf_input('cpf', $viz_array['cpf'] ?? ''); ?></div>
          <div class="col-md-12">
            <hr>
            <button type="submit" name="action" value='Cadastrar' class="btn btn-warning" style="width: 140px;"><?= ($viz_array == array() ? 'CADASTRAR' : 'EDITAR')?></button>
          </div>
        </div>
      </div>
    </div>
  </form>
<?php } 

function render_agenda_listagem($title_label, $icon, $id) { ?>
  <div class="panel"><?php render_cabecalho_painel($title_label, $icon, true); ?>
    <div class="panel-content">
      <div class="row">
        <div class="col-md-12 mb-sm">
          <div class="form-group">
            <table class="table text-center" id="<?=$id?>"></table>
          </div>
        </div>
      </div>
    </div>
  </div>

 <?php }

function render_formulario_agenda($evento = null) { ?>
  <div class="panel"><?php render_cabecalho_painel(($evento == null) ? 'CADASTRAR EVENTO' : "EDITAR EVENTO", 'fa fa-'. (($evento == null) ? 'plus' : 'edit'), true); ?>
    <div class="panel-content">
      <div class="row"><div class="col-md-12 mb-sm">
        <form class="form-group" method="post" action="<?= ($evento == null) ? 'cadastro_evento.php' : 'set_event.php?id='.$evento['id'] ?>" enctype="multipart/form-data">
          <div class="row">
            <div class="col-md-12 mb-sm"><?php render_custom_input('Título', 'titulo', 'titulo', $evento['titulo'] ?? '', 30, 'Título', true, false, 'fas fa-id-card-alt') ?></div>
              <div class="col-md-12 mb-sm">
                <div class="form-group">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fas fa-edit"></i></span>
                    <textarea type="text" class="form-control maxLength" name="descricao" placeholder="Descrição" maxlength="3000" required="Preenchimento obrigatório"><?= $evento['descricao'] ?? '' ?></textarea>
                  </div>
                </div>
              </div>
              <div class="col-md-12 mb-sm"><?php if($evento == null) { render_file_upload_button('arquivo'); } else { echo ''; }?></div>
              <div class="col-md-6 mb-sm">
                <div class="form-group">
                  <label for="datahorainicio">Início do Evento</label>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fas fa-calendar"></i></span>
                    <input type="datetime-local" placeholder="AAAA-MM-DD HH:mm:ss" class="form-control" value="<?= $evento['datahorainicio'] ?? '' ?>" name="datahorainicio" id="datahorainicio" required="Preenchimento obrigatório">
                  </div>
              </div>
            </div>
            <div class="col-md-6 mb-sm">
              <div class="form-group">
                <label for="datahorafim">Fim do Evento</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fas fa-calendar"></i></span>
                  <input type="datetime-local" placeholder="AAAA-MM-DD HH:mm:ss" class="form-control" value="<?= $evento['datahorafim'] ?? '' ?>" name="datahorafim" id="datahorafim">
                </div>
              </div>
            </div>
            <?php $consulta = read_usuarios_situacao('S');
            $lista = array('TODOS DA OM'=>'TODOS DA OM', 'SOMENTE EU'=>'SOMENTE EU');
            foreach($consulta as $usr) { $lista[$usr['id']] = (getPGrad($usr['idpgrad']).' '.$usr['nomeguerra']); } ?>
            <div class="col-md-12 mb-sm"><div id="listviz"></div></div>
            <div class="col-md-12 mb-sm">
            <?= ($evento != null) ? 'Para editar o evento você vai precisar atualizar os participantes' : '';
          render_default_select('tkusr', '', false, 'Selecione quem pode ver:', 'Militares da OM', 'fa fa-group', $lista, ''); ?>
          <button type="button" class="btn btn-primary" onclick="insertname($('#tkusr').val(), $('#tkusr option:selected').text());">Adicionar usuário</button>
        </div>
        <div class="col-md-12 mb-sm"><div class="form-group">
            <input class="btn btn-primary" type="submit" value="SALVAR">
            <?= $evento == null ? '' : '<input class="btn btn-danger" type="submit" name="acao" value="EXCLUIR">' ?>
            <p id="feedback">Você não pode cadastrar eventos em datas passadas</p>
        </div>
        </div>
      </div>
    </form>
  </div>
  </div>
  </div>
  <script>
  var namebox = $('#listviz');
  function insertname(id, militar) {
    if(militar == 'SOMENTE EU' || militar == 'TODOS DA OM' || $('#listviz').html().includes('SOMENTE EU') || $('#listviz').html().includes('TODOS DA OM')) {
      $('#listviz').empty();
      $('#listviz').append('<input type="hidden" name="viz[]" id="idH'+ id + '" value="'+ id +'">');
      $('#listviz').append(`<a id="idA`+ id + `" onclick="removename('`+ id +`')">`+ militar +`<br></a>`);
    } else if($('#listviz').html().includes('SOMENTE EU') || $('#listviz').html().includes('TODOS DA OM')) {
      $('#listviz').empty();
      $('#listviz').append('<input type="hidden" name="viz[]" id="idH'+ id + '" value="'+ id +'">');
      $('#listviz').append(`<a id="idA`+ id + `" onclick="removename('`+ id +`')">`+ militar +`<br></a>`);
    } else if($('#listviz').html().includes(id) && $('#listviz').html().includes(militar)) {
      $('#listviz').append('<input type="hidden" name="viz[]" id="idH'+ id + '" value="'+ id +'">');
      $('#listviz').append(`<a id="idA`+ id + `" onclick="removename('`+ id +`')">`+ militar +`<br></a>`);
    } else {
      $('#listviz').append('<input type="hidden" name="viz[]" id="idH'+ id + '" value="'+ id +'">');
      $('#listviz').append(`<a id="idA`+ id + `" onclick="removename('`+ id +`')">`+ militar +`<br></a>`);
    }
  }
  function removename(id) {
    $('#idA' + id).remove();
    $('#idH' + id).remove();
    if($('#listviz').html() == '') { insertname('SOMENTE EU', 'SOMENTE EU'); }
  }
  $(document).ready(function() {
    <?php if($evento == null) {
      echo "insertname('TODOS DA OM', 'TODOS DA OM');";
    } else {
      $db = unserialize($evento['viz']);

      foreach($db as $viz) {
        $mil = ImprimeConsultaMilitar2(read_usuario_by_id($viz));
        echo "insertname('".$viz."', '". $mil ."');";
      }
    } ?>
  })
  $(document).ready(function() { //esse código desabilita o botão salvar caso o datetime seja menor que agora
      var btnSalvar = $('input[type="submit"][value="SALVAR"]'); // Obter o botão salvar
      var campoDataHoraInicio = $('input[name="datahorainicio"]'); // Obter o campo de data e hora de início
      if (campoDataHoraInicio.val()) { // Verificar se o campo de data e hora de início é preenchido
        var dataHoraInicio = new Date(campoDataHoraInicio.val()); // Converter o valor do campo em um objeto de data
        var dataHoraAtual = new Date(); // Obter a data e hora atual
        if (dataHoraInicio < dataHoraAtual) { // Verificar se a data de início é menor que a data atual
          btnSalvar.prop('disabled', true); // Desabilitar o botão salvar
          $('#feedback').text('Você não pode cadastrar eventos em datas passadas.')
        }
      } else { btnSalvar.prop('disabled', true); $('#feedback').text(''); }
      campoDataHoraInicio.on('change', function() { // Adicionar um evento de mudança ao campo de data e hora de início
        if ($(this).val()) { // Verificar se o campo está preenchido
          var dataHoraInicio = new Date($(this).val()); // Converter o valor do campo em um objeto de data
          var dataHoraAtual = new Date(); // Obter a data e hora atual
          if (dataHoraInicio < dataHoraAtual) { // Verificar se a data de início é menor que a data atual
            btnSalvar.prop('disabled', true); // Desabilitar o botão salvar
            $('#feedback').text('Você não pode cadastrar eventos em datas passadas.')
          } else { btnSalvar.prop('disabled', false); $('#feedback').text(''); } // Habilitar o botão salvar
        }
      });
    });
  </script>
<?php }


function render_cadastro_viatura_form($viatura = null) { ?>

<div class="panel"><?php render_cabecalho_painel( ($viatura == null ? 'CADASTRAR NOVA VIATURA:' : ('EDITAR VIATURA '. $viatura['placa'])), 'fa fa-'. ($viatura == null ? 'plus' : 'edit'), true); ?>
  <div class="panel-content">
    <div class="row">
      <div class="col-md-12">
        <form id="inline-validation" action="#" method="post">
          <input type="hidden" name="viatura" value="<?= $viatura['id'] ?>">
          <div class="col-md-6"><?php render_custom_input('Modelo da Viatura:', 'modelo', 'modelo', $viatura['modelo'] ?? '', 30, '5 Ton', true, false, 'fa fa-car'); ?></div>
          <div class="col-md-6"><?php render_custom_input('Marca:', 'marca', 'marca', $viatura['marca'] ?? '', 30, 'Volkswagen', true, false, 'fa fa-industry'); ?></div>
          <div class="col-md-6"><?php render_custom_input('Placa (EB):', 'placa', 'placa', $viatura['placa'] ?? '', 10, '1234567890', true, false, 'fa fa-search'); ?></div>
          <div class="col-md-6"><?php render_tipo_veic_select('tipo', $viatura['tipo'] ?? ''); ?></div>
          <div class="col-md-6"><?php render_custom_input('Consumo médio (Km/l):', 'consumo', 'consumo', $viatura['consumo'] ?? '', 10, '10', true, false, 'fa fa-tint'); ?></div>
          <div class="col-md-6"><?php $combustivel['G'] = 'Gasolina'; $combustivel['D'] = 'Diesel';
          render_default_select('combustivel', $viatura['combustivel'] ?? '', true, 'Combustível:', 'Selecione', 'fa fa-tint', $combustivel, ''); ?></div>
          <div class="col-md-6"><?php render_custom_input('Total ocupantes:', 'total_ocupantes', 'total_ocupantes', $viatura['total_ocupantes'] ?? '2', 2, '', true, false, 'fa fa-users'); ?></div>
          <div class="col-md-6"><?php render_custom_input('Odômetro:', 'odometro', 'odometro', $viatura['odometro'] ?? '0', 10, '', true, false, 'fa fa-sort-numeric-asc'); ?></div>
          <div class="col-md-12">
            <div class="form-group"><hr>
              <button name="btn_add_vtr" type="submit" class="btn btn-primary" value="<?= ($viatura == null ? 'Cadastrar' : 'Editar') ?> Viatura"><?= ($viatura == null ? 'CADASTRAR' : 'EDITAR') ?></button>
            </div>
          </div>
        </form>
      </div>
      </div>
  </div>
</div>

<?php }
?>