<?php

/* Aqui estão salvos widgets usados em formulários no sistema de TI, com implementação rápida e fácil manutenção */

/**
 * Esse é o select de mais baixo nível, dele é possível criar outros selects apenas mudando os atributos abaixo:
 * @param attr_name é o name do input
 * @param initianValue é o valor marcado
 * @param required é bool e define se o preenchimento é obrigatório
 * @param label é a label do select
 * @param icon é o icone exibido (fas fa-icon)
 * @param data é a array dos dados a ser exibido nos options do select (value => text)
 */
function render_default_select($attr_name, $initialValue, $required, $label, $optionLabel, $icon, $data, $onChange) { ?>
    <div class="form-group">
        <label for="<?=$attr_name?>" class="control-label"><?=$label?></label>
        <div class="input-group mb-sm">
            <span class="input-group-addon"><i class="<?=$icon?>"></i></span>
            <select id="<?=$attr_name?>" name="<?=$attr_name?>" class="form-control select disableInput" style="width: 100%" <?=($required ? 'required="Preenchimento obrigatório"' : '')?>, onchange="<?=$onChange?>">
                <option></option>
                <optgroup label="<?=$optionLabel?>">
                    <?php foreach($data as $key => $value) {
                        if ($key == ($initialValue ?? 'XXX')) {
                            echo ("<option selected value=" . $key . ">" . $value . "</option>");
                        } else {
                            echo ("<option value=" . $key . ">" . $value . "</option>");
                        }
                    } ?>
                </optgroup>
            </select>
        </div>
    </div>
<?php }

/**
 * Essa função renderiza o <select> com os pgrads, posso escolher se é required e selecionar valor por padrão.
 * Escolher @param selectedPGrad = 30 se quiser que não esteja marcada
 */
function render_pgrad_select($attr_name, $selectedPGrad, $required) { 
    $selectedPGrad == "" ? 30 : $selectedPGrad;
    ?>
    <div class="form-group">
        <label for="<?=$attr_name?>" class="control-label">Posto/Graduação:</label>
        <div class="input-group mb-sm">
            <span class="input-group-addon"><i class="fa fa-star"></i></span>
            <select id="<?=$attr_name?>" name="<?=$attr_name?>" class="form-control select disableInput" style="width: 100%" <?=($required ? 'required="Preenchimento obrigatório"' : '')?>>
                <option></option>  
                <optgroup label="Posto Graduação">
                    <?php foreach(PGRADS as $pgradId => $pgrad) {
                        if ($pgradId == ($selectedPGrad ?? 'XXX')) {
                            echo ("<option selected value=" . $pgradId . ">" . $pgrad . "</option>");
                        } else {
                            echo ("<option value=" . $pgradId . ">" . $pgrad . "</option>");
                        }
                    } ?>
                </optgroup>
            </select>
        </div>
    </div>
<?php }

/**
 * Essa função renderiza um select com o postograd e nome de todos militares cadastrados e ativos no sistema
 */
function render_militar_ativo_select($attr_name, $select_id, $required, $codificar = true) { ?>
    <div class="form-group">
        <label for="<?=$select_id?>" class="control-label">Selecione o Militar da OM na lista:</label>
        <div class="input-group mb-sm">
            <span class="input-group-addon"><i class="fa fa-group"></i></span>
            <select name="<?=$attr_name?>" id="<?=$select_id?>" class="select form-control" style="width: 100%" <?=($required ? 'required="Preenchimento obrigatório"' : '')?>>
                <option></option>
                <optgroup label='Militar da OM'>
                <?php
                $consulta = conectar('membros')->prepare("SELECT id, idpgrad, nomeguerra FROM usuarios WHERE userativo = 'S' ORDER BY idpgrad, nomeguerra ASC");
                $consulta->execute();
                while ($reg = $consulta->fetch(PDO::FETCH_ASSOC)) :
                echo ("<option value=" . ($codificar ? base64_encode($reg['id']) : $reg['id']) . ">" . ImprimeConsultaMilitar2($reg). "</option>");
                
                endwhile;
                ?>
                </optgroup>
            </select>
        </div>
    </div>

<?php } 

/**
 * Select dos bairros cadastrados no sistema
 */
function render_bairros_select($attr_name, $required, $selected_bairro = '0') { 
    ?>
    <div class="form-group mb-sm">
        <label for="bairro" class="control-label">Bairro:</label>
        <select name="<?=$attr_name?>" id="bairro" class="select form-control" style="width: 100%" <?=($required ? 'required="Preenchimento obrigatório"' : '')?>>
            <option></option>
            <optgroup label='Bairros'>
                <?php $consulta = listar_bairros();
                foreach ($consulta as $reg2) {
                    echo ('<option value="' . $reg2['id'] .'"'.($reg2['id'] == $selected_bairro ? ' selected' : '').'>' . $reg2['bairro'] . '</option>');
                } ?>
            </optgroup>
        </select>
    </div>
<?php }
/**
 * Select dos bairros cadastrados no sistema
 */
function render_setores_de_bairros_select($attr_name, $required, $selected_setor = '0') { 
    ?>
    <div class="form-group mb-sm">
        <label for="setor" class="control-label">Setor:</label>
        <select name="<?=$attr_name?>" id="setor" class="select form-control" style="width: 100%" <?=($required ? 'required="Preenchimento obrigatório"' : '')?>>
            <option></option>
            <optgroup label='Setores'>
                <?php $consulta = listar_setores_de_bairros();
                foreach ($consulta as $reg2) {
                    echo ('<option value="' . $reg2['id'] .'"'.($reg2['id'] == $selected_setor ? ' selected' : '').'>' . $reg2['setor'] . '</option>');
                } ?>
            </optgroup>
        </select>
    </div>
<?php }

/**
 * Select das subunidades cadastradas no sistema
 */
function render_subunidades_select($attr_name, $required, $selected_su = '0') { ?>
    <div class="form-group mb-sm">
        <label for="<?=$attr_name?>" class="control-label">Subunidade:</label>
        <div class="input-group mb-sm">
            <span class="input-group-addon"><i class="fa fa-sitemap"></i></span>
            <select id="<?=$attr_name?>" name="<?=$attr_name?>" class="select form-control" style="width: 100%" <?=($required ? 'required="Preenchimento obrigatório"' : '')?>>
                <option></option>
                <optgroup label='Subunidade'>
                    <?php $consulta = listar_subunidades();
                    foreach ($consulta as $reg4) {
                        echo ('<option value="' . $reg4['id'] . '"'.($reg4['id'] == $selected_su ? ' selected' : '').'>' . $reg4['descricao'] . '</option>');
                    } ?>
                </optgroup>
            </select>
        </div>
    </div>
<?php }

/**
 * Renderiza o input de hora
 */
function render_hora_field($attr_name, $required, $label = 'Hora:', $now = true) { ?>
    <div class="form-group">
        <label for="<?=$attr_name?>" class="control-label"><?=$label?></label>
        <div class="input-group bootstrap-timepicker timepicker">
            <span class="input-group-addon date-time-color"><i class="fa fa-clock-o"></i></span>
            <input type="text" id="<?=$attr_name?>" class="form-control time" placeholder="XX:XX" name="<?=$attr_name?>" value="<?= $now ? date("H:i") : '' ?>" pattern="^\d{1,2}:\d{2}$" <?=($required ? 'required' : '')?>>
        </div>
    </div>

<?php } 

/**
 * Renderiza o input de data, atenção ao @param date.
 * se date = "now", busca a data atual, se for null, retorna string vazia, se for uma data, retorna essa data.
 */
function render_data_field($attr_name, $required, $label_text, $date) { 
    switch ($date) {
        case 'now':
            $date = date('d/m/Y');
            break;
        case 'ontem':
            $date = date('d/m/Y', strtotime('-1 day'));
            break;
        case null:
            $date = '';
            break;
        default:
            break;
    } ?>
    <div class="form-group">
        <label for="<?=$attr_name?>" class="control-label"><?=$label_text?></label>
        <div class="input-group date">
            <span class="input-group-addon date-time-color"><i class="fa fa-calendar"></i></span>
            <input type="text" id="<?=$attr_name?>" class="form-control" name="<?=$attr_name?>" autocomplete="off" value="<?=$date?>" placeholder="DD/MM/AAAA" pattern="^\d{2}/\d{2}/\d{4}$" <?=($required ? 'required' : '')?>>
        </div>
    </div>
<?php } 

/**
 * Renderiza o input de email.
 */
function render_email_field($attr_name, $required, $label_text, $value) {
    ?>
    <div class="form-group">
    <label for="<?=$attr_name?>" class="control-label"><?=$label_text?></label>
    <div class="input-group mb-sm">
      <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
      <input type="email" id="<?=$attr_name?>" class="form-control" id="inputMaxLength5" value="<?=$value?>" name="<?=$attr_name?>" placeholder="endereco@provedor.com" maxlength="100" <?=($required ? 'required' : '')?>>
    </div>
  </div>
<?php }

/**
 * Renderiza o input de telefone.
 * se @param nono_digito = false -> ddd + 8 digitos; senão -> ddd + 9 digitos
 */
function render_telefone_field($attr_name, $required, $label_text, $nono_digito, $value) { ?>
  <div class="form-group">
    <label for="<?=$attr_name?>"><?=$label_text?></label>
    <div class="input-group mb-sm">
      <span class="input-group-addon"><i class="fa fa-<?=$nono_digito ? 'mobile-' : '' ?>phone"></i></span>
      <input id="<?=$attr_name?>" type="text" class="form-control <?=$nono_digito ? 'cellPhone' : 'phone' ?>" value="<?=$value?>" name="<?=$attr_name?>" placeholder="XX-XXXX<?=$nono_digito ? 'X' : '' ?>-XXXX" pattern="^\d{2}-\d{<?=$nono_digito ? '5' : '4' ?>}-\d{4}$" <?=($required ? 'required' : '')?>>
    </div>
  </div>
<?php }

/**
 * Renderiza o select das categorias de visitantes
 */
function render_select_cat_visitante($attr_name, $selected_value, $required) { ?>
    <div class="form-group">
        <label for="<?=$attr_name?>" class="control-label">Categoria do Visitante:</label>
        <select id="<?=$attr_name?>" name="<?=$attr_name?>" class="form-control select" style="width: 100%" <?=($required ? 'required="Preenchimento obrigatório"' : '')?>>
            <option></option>
            <optgroup label='Categoria'>
                <option value='Militar da Ativa'<?= $selected_value == 'Militar da Ativa' ? 'selected' : ''?>>Militar da Ativa</option>
                <option value='Militar Inativo'<?= $selected_value == 'Militar Inativo' ? 'selected' : ''?>>Militar Inativo</option>
                <option value='Pensionista Militar'<?= $selected_value == 'Pensionista Militar' ? 'selected' : ''?>>Pensionista Militar</option>
                <option value='Dependente de Militar'<?= $selected_value == 'Dependente de Militar' ? 'selected' : ''?>>Dependente de Militar</option>
                <option value='Civil'<?= $selected_value == 'Civil' ? 'selected' : ''?>>Civil</option>
            </optgroup>
        </select>
    </div>
<?php } 


/**
 * Nome completo input
 */
function render_nome_completo_input($attr_name, $value = "") { ?>
    <div class="form-group">
        <label for="<?=$attr_name?>" class="control-label">Nome completo:</label>
        <input id="<?=$attr_name?>" type="text" class="form-control text-uppercase maxLength" value="<?=$value?>" name="<?=$attr_name?>" autocomplete="off" placeholder="Nome Completo" maxlength="100" required="Preenchimento obrigatório">
    </div>
<?php } 

/**
 * Idt mil input
 */
function render_identidade_input($attr_name, $value = '') { ?>
    <div class="form-group">
        <label for="identidade" class="control-label">Identidade:</label>
        <input type="text" id="identidade" class="form-control idt maxLength" name="<?=$attr_name?>" value="<?=$value?>" placeholder="Identidade" maxlength="12" required="Preenchimento obrigatório">
    </div>
<?php } 

/**
 * Cpf input com validação de dados
 */
function render_cpf_input($attr_name, $value = '') { 
    $randnum = rand(0, 99);
    ?>
    <div class="form-group">
        <label for="frmcpf<?=$randnum?>" id="cpflabel<?=$randnum?>" class="control-label">CPF:</label>
        <input id="frmcpf<?=$randnum?>" type="text" onkeyup="validarCPF<?=$randnum?>(this.value);" class="form-control cpf" name="<?=$attr_name?>" value="<?=$value?>" placeholder="CPF">
    </div>
    <script>
          lastCpf = null;
          function validarCPF<?=$randnum?>(cpfA) {
            cpf = cpfA
            if(lastCpf == cpf) {return true;}
            lastCpf = cpf;
            if (vercpf<?=$randnum?>(document.getElementById('frmcpf<?=$randnum?>').value.replace('.', '').replace('.', '').replace('-', ''))){
              document.getElementById('frmcpf<?=$randnum?>').submit();
              // TODO
            } else {
              errors = "1";
              if (errors) {
                $('#cpflabel<?=$randnum?>').text('CPF: (CPF inválido)');
              }
              document.retorno = (errors == '');
            }
          }
          function vercpf<?=$randnum?>(cpf) {
              if (cpf.length != 11 ||
                  cpf == "11111111111" ||
                  cpf == "22222222222" ||
                  cpf == "33333333333" ||
                  cpf == "44444444444" ||
                  cpf == "55555555555" ||
                  cpf == "66666666666" ||
                  cpf == "77777777777" ||
                  cpf == "88888888888" ||
                  cpf == "99999999999")
                  return false;
              add = 0;
              for (i = 0; i < 9; i++)
                      add += parseInt(cpf.charAt(i)) * (10 - i);
              rev = 11 - (add % 11);
              if (rev == 10 || rev == 11)
                  rev = 0;
              if (rev != parseInt(cpf.charAt(9)))
                  return false;
              add = 0;
                      for (i = 0; i < 10; i++)
                      add += parseInt(cpf.charAt(i)) * (11 - i);
              rev = 11 - (add % 11);
              if (rev == 10 || rev == 11)
                  rev = 0;
              if (rev != parseInt(cpf.charAt(10)))
                  return false;
              console.log('O CPF INFORMADO É VÁLIDO.');
              $('#cpflabel<?=$randnum?>').text('CPF: (CPF válido)');
              return true;
          }
      </script>
<?php }  

/**
 * Dados de veículo veic (select e inputs)
 */
function render_tipo_veic_select($attr_name, $initial_value = '') { ?>
    <div class="form-group">
        <label for="veic" class="control-label">Tipo do veículo:</label>
        <select id="veic" name="<?=$attr_name?>" class="form-control select" style="width: 100%" required="Preenchimento obrigatório">
            <option></option>
            <optgroup label='Tipo'>
                <option value='Carro'<?= $initial_value == 'Carro' ? ' selected' : ''?>>Carro</option>
                <option value='Caminhao'<?= $initial_value == 'Caminhao' ? ' selected' : ''?>>Caminhao</option>
                <option value='Onibus'<?= $initial_value == 'Onibus' ? ' selected' : ''?>>Onibus</option>
                <option value='Caminhonete'<?= $initial_value == 'Caminhonete' ? ' selected' : ''?>>Caminhonete</option>
                <option value='Van'<?= $initial_value == 'Van' ? ' selected' : ''?>>Van</option>
                <option value='Moto'<?= $initial_value == 'Moto' ? ' selected' : ''?>>Moto</option>
                <option value='Blindado sobre Lagartos'<?= $initial_value == 'Blindado sobre Lagartos' ? ' selected' : ''?>>Blindado sobre Lagartos</option>
                <option value='Blindado sobre Rodas'<?= $initial_value == 'Blindado sobre Rodas' ? ' selected' : ''?>>Blindado sobre Rodas</option>
            </optgroup>
        </select>
    </div>

<?php } 

/**
 * Dados de veículo veic (select e inputs)
 */
function render_marca_veic_select($attr_name) { 
    $list_marcas = array('ACURA', 'AGRALE', 'ALFA ROMEO', 'AMERICAR', 'ASIA', 'ASTON MARTIN', 'AUDI',
    'AUSTIN-HEALEY', 'AVALLONE', 'BENTLEY', 'BIANCO', 'BMW', 'BRASFIBRA', 'BRM', 'BUGRE', 'CADILLAC',
    'CHERY', 'CHEVROLET', 'CHRYSLER', 'CITROËN', 'DAFRA', 'DODGE', 'FERCAR BUGGY', 'FERRARI', 'FIAT',
    'FORD', 'GMC', 'HARLEY-DAVIDSON', 'HONDA', 'HUMMER', 'HYUNDAI', 'INFINITI', 'IVECO', 'JAC', 'JAGUAR',
    'JEEP', 'KASINSKI', 'KAWASAKI', 'KIA', 'LAMBORGHINI', 'LAMBRETTA', 'LAND ROVER', 'LEXUS', 'LIFAN',
    'LOTUS', 'MASERATI', 'MAZDA', 'MCLAREN', 'MERCEDES-BENZ', 'MINI', 'MITSUBISHI', 'MOBBY', 'NISSAN',
    'PEUGEOT', 'PONTIAC', 'PORSCHE', 'PUMA', 'RENAULT', 'ROLLS-ROYCE', 'SHINERAY', 'SSANGYONG', 'SUBARU',
    'SUZUKI', 'TAC', 'TESLA', 'TOYOTA', 'TRIUMPH', 'TROLLER', 'VOLKSWAGEN', 'VOLVO', 'YAMAHA',
    'OUTRA MARCA');
    ?>
    <div class="form-group">
        <label for="marca" class="control-label">Marca:</label>
        <select id="marca" name="<?=$attr_name?>" class="form-control select" style="width: 100%" required="Preenchimento obrigatório">
            <option></option>
            <optgroup label='Marcas'>
                <?php foreach($list_marcas as $marca) { echo '<option value="'.$marca.'">'.$marca.'</option>'; } ?>
            </optgroup>
        </select>
    </div>

<?php } 
/**
 * Dados de veículo veic (select e inputs)
 */
function render_cor_veic_select($attr_name) { 
    $list_cores = array('AMARELO', 'AZUL', 'BEGE', 'BRANCO', 'BRONZE', 'CINZA', 'DOURADO', 'LARANJA',
    'MARROM', 'PRATA', 'PRETO', 'ROSA', 'ROXO', 'VERDE', 'VERMELHO', 'VINHO', 'INDEFINIDA'); ?>
    <div class="form-group">
        <label for="cor" class="control-label">Cor:</label>
        <select id="cor" name="<?=$attr_name?>" class="form-control select" style="width: 100%" placeholder="Escolha uma opção" required="Preenchimento obrigatório">
            <option></option>
            <optgroup label='Cores'>
                <?php foreach($list_cores as $cor) { echo '<option value="'.$cor.'">'.$cor.'</option>'; } ?> 
            </optgroup>
        </select>
    </div>

<?php }
/**
 * Dados de veículo veic (select e inputs)
 */
function render_placa_veic_field($attr_name) { ?>
    <div class="form-group">
        <label for="placa" class="control-label">Placa:</label>
        <input id="placa" type="text" class="form-control text-uppercase placa" name="<?=$attr_name?>" placeholder="Placa" pattern="^[A-Za-z]{3}-\d{1}[A-Za-z0-9]{1}\d{2}$" required="Preenchimento obrigatório">
    </div>

<?php }
/**
 * Dados de veículo veic (select e inputs)
 */
function render_modelo_veic_field($attr_name) { ?>
    <div class="form-group">
        <label for="modelo" class="control-label">Modelo:</label>
        <input id="modelo" type="text" class="form-control text-uppercase maxLength" name="<?=$attr_name?>" autocomplete="off" placeholder="Modelo" maxlength="30" required="Preenchimento obrigatório">
    </div>

<?php }

/**
 * input de texto genérico e personalizavel.
 * @param label_text é o texto da label;
 * @param input_id é o id do input;
 * @param attr_text é o valor do atributo name;
 * @param default_value é o valor do atributo name;
 * @param max_length é int com a quant de caracteres limite;
 * @param placeholder é o valor do atributo placeholder; e
 * @param required e @param uppercase são boolean.
 * @param fa_icon  é a classe do icone font-awesome.
 */
function render_custom_input($label_text, $input_id, $attr_name, $default_value, $max_length, $placeholder, $required, $uppercase, $fa_icon = '') { ?>
    <div class="form-group">
        <label for="<?=$input_id?>" class="control-label"><?=$label_text?></label>
        <div class="input-group">
            <?= $fa_icon == '' ? '' : '<span class="input-group-addon"><i class="'.$fa_icon.'"></i></span>' ?>
            <input type="text" value="<?=$default_value?>" id="<?=$input_id?>" class="form-control<?=($uppercase ? ' text-uppercase' : '')?> maxLength" name="<?=$attr_name?>" autocomplete="off" placeholder="<?=$placeholder?>" maxlength="<?=$max_length?>" <?=($required ? 'required="Preenchimento obrigatório"' : '')?>>
        </div>
    </div>
<?php } 

function render_radio_button($label_text, $input_id, $attr_name, $default_value, $checked) { ?>
    <div class="radio radio-custom radio-warning">
        <input type="radio" id="<?=$input_id?>" name="<?=$attr_name?>" value="<?=$default_value?>"<?=($checked ? ' checked' : '') ?>>
        <label for="<?=$input_id?>"><?=$label_text?></label>
    </div>
<?php }

function render_checkbox($label_text, $input_id, $attr_name, $default_value, $checked) { ?>
    <div class="checkbox-custom checkbox-warning">
        <input type="checkbox" name="<?=$attr_name?>" id="<?=$input_id?>" value="<?=$default_value?>"<?=($checked ? ' checked' : '') ?>>
        <label class="check" for="<?=$input_id?>"><?=$label_text?></label>
    </div>
<?php } 




/**
 * Essa função renderiza um select com os visitantes cadastrados e ativos no sistema
 */
function render_visitante_ativo_select($attr_name, $select_id, $required) { 
    $visitantes = read_visitante(); ?>
    <div class="form-group">
        <label for="inputMaxLength" class="control-label">Selecione o Visitante na lista:</label>
        <div class="input-group mb-sm">
            <span class="input-group-addon"><i class="fa fa-group"></i></span>
            <select name="<?=$attr_name?>" id="<?=$select_id?>" class="select form-control" style="width: 100%" <?=($required ? 'required="Preenchimento obrigatório"' : '')?>>
                <option></option>
                <optgroup label='Visitante cadastrado'>
                <?php foreach($visitantes as $reg) {
                    echo ("<option value=" . base64_encode($reg['id']) . ">" . getPGrad($reg['idpgrad']) . ' ' . $reg['nomecompleto']. ' - ' . $reg['tipo'] . ' - ' . $reg['cpf'] . "</option>");
                } ?>
                </optgroup>
            </select>
        </div>
    </div>

<?php } 

/**
 * Essa função renderiza um select com os visitantes cadastrados e ativos no sistema por tipo
 */
function render_visitante_ativo_por_tipo_select($attr_name, $select_id, $tipo, $required) { 
    $visitantes = read_visitante($tipo); ?>
    <div class="form-group">
        <label for="<?=$select_id?>" class="control-label">Selecione o <?=$tipo ?? 'Visitante'?> na lista:</label>
        <div class="input-group mb-sm">
            <span class="input-group-addon"><i class="fa fa-group"></i></span>
            <select name="<?=$attr_name?>" id="<?=$select_id?>" class="select form-control" style="width: 100%" <?=($required ? 'required="Preenchimento obrigatório"' : '')?>>
                <option></option>
                <optgroup label='Visitante cadastrado'>
                <?php foreach($visitantes as $reg) {
                    echo ("<option value=" . base64_encode($reg['id']) . ">" . getPGrad($reg['idpgrad']) . ' ' . $reg['nomecompleto']. ' - ' . $reg['tipo'] . ' - ' . $reg['cpf'] . "</option>");
                } ?>
                </optgroup>
            </select>
        </div>
        <p>Não encontrou? pode ser que essa pessoa não esteja cadastrada ou esteja cadastrada com o tipo errado.</p>
    </div>

<?php } 


function render_grau_parentesco_dependente_select($attr_name, $selectedValue = "") { ?>
    <div class="form-group">
        <label for="parentesco" class="control-label">Tipo de dependente:</label>
        <div class="input-group mb-sm">
            <span class="input-group-addon"><i class="fa fa-people-roof"></i></span>
            <select id="parentesco" name="<?=$attr_name?>" class="form-control select" style="width: 100%" required="Preenchimento obrigatório">
                <option></option>
                <optgroup label='Tipo'>
                    <option <?= ($selectedValue=="Cônjuge" ? 'selected' : "") ?> value='Cônjuge'>Cônjuge</option>
                    <option <?= ($selectedValue=="Companheiro(a)" ? 'selected' : "") ?> value='Companheiro(a)'>Companheiro(a)</option>
                    <option <?= ($selectedValue=="Filho/enteado menor de 21 anos" ? 'selected' : "") ?> value='Filho/enteado menor de 21 anos'>Filho/enteado menor de 21 anos</option>
                    <option <?= ($selectedValue=="Filho/enteado inválido" ? 'selected' : "") ?> value='Filho/enteado inválido'>Filho/enteado inválido</option>
                    <option <?= ($selectedValue=="Filho/enteado estudante menor de 24 anos" ? 'selected' : "") ?> value='Filho/enteado estudante menor de 24 anos'>Filho/enteado estudante menor de 24 anos</option>
                    <option <?= ($selectedValue=="Pai/Mãe" ? 'selected' : "") ?> value='Pai/Mãe'>Pai/Mãe</option>
                    <option <?= ($selectedValue=="Menor de 18 anos sob guarda judicial" ? 'selected' : "") ?> value='Menor de 18 anos sob guarda judicial'>Menor de 18 anos sob guarda judicial</option>
                    <option <?= ($selectedValue=="Outro" ? 'selected' : "") ?> value='Outro'>Outro</option>
                </optgroup>
            </select>
        </div>
    </div>

<?php } 

function render_file_upload_button($attr_name) { ?>
    <div class="form-group">
        <label for="upload" class="control-label">Enviar Arquivo (pdf, jpg, jpeg ou png. Max 10 MB):</label>
        <div class="input-group mb-sm">
            <span class="input-group-addon"><i class="fa fa-upload"></i></span>
            <input id="upload" class="form-control" name="<?=$attr_name?>" type="file" max="10485760" accept="application/pdf, image/jpeg, image/png, image/jpg" />
        </div>
    </div>
<?php }

?>