/* Script usado para processar as ações da agenda */
// lista dos id das celulas
const listcelulas = ["a1","a2","a3","a4","a5","a6","a7","b1","b2","b3","b4","b5","b6","b7","c1","c2","c3","c4","c5","c6","c7",
                     "d1","d2","d3","d4","d5","d6","d7","e1","e2","e3","e4","e5","e6","e7","f1","f2","f3","f4","f5","f6","f7"];
const meses = ['Jan.', 'Fev.', 'Mar.', 'Abr.', 'Maio', 'Jun.', 'Jul.', 'Ago.', 'Set.', 'Out.', 'Nov.', 'Dez.'];
var d = new Date(); //obter o dia de hoje
// essas variaveis serão utilizadas para definir o dia com foco inicial
var focusDia = d.getDate();
var focusMes = d.getMonth();
var focusAno = d.getFullYear();
function reduzirMes() { // função definida para processar a redução de mes
  if(focusMes == 0) { focusMes = 11; focusAno--;
  } else { focusMes--; }
}
function aumentarMes() { // função definida para processar a aumento de mes
  if(focusMes == 11) { focusMes = 0; focusAno++;
  } else { focusMes++; }
}

async function setActDay(dia, mes, ano, celId){ // Essa função deixa em amarelo o dia que possui alguma atividade
    await $.ajax({ url: "ajax_dias_com_evento.php?dia="+dia+"&mes="+mes+"&ano="+ano, type: "GET", dataType: "json", data: 'json',
        success: function (data) {
            let currDay = new Date(ano, mes, dia);
            for(i=0;i<data.length;i++) {
                let datahorainicio = new Date(data[i].datahorainicio);
                let datahorafim = new Date(data[i].datahorafim);
                let datahorainiciotratada = new Date(datahorainicio.getFullYear(), datahorainicio.getMonth(), datahorainicio.getDate());
                let datahorafimtratada = new Date(datahorafim.getFullYear(), datahorafim.getMonth(), datahorafim.getDate());
                for (let cel in listcelulas) {
                    let datacel = new Date(focusAno, focusMes, $('#'+listcelulas[cel]).html());
                    if(datahorafimtratada >= datacel && datahorainiciotratada <= datacel && $("#"+listcelulas[cel]).hasClass("btn-primary")) {
                        $("#"+listcelulas[cel]).removeClass("btn-primary");
                        $("#"+listcelulas[cel]).addClass("btn-warning");
                        $("#"+listcelulas[cel]).attr("title", "Clique para visualizar o evento desse dia.");
                    }
                }
                if(datahorafimtratada >= currDay && datahorainiciotratada <= currDay) {
                    let autor = '';
                    let assas = $('#tkusr')[0];
                    for(opcao in assas) {
                        if(!Number.isInteger(parseInt(opcao))){ continue;
                        } else if(autor == '') {
                            let qwe = assas[opcao].value;
                            if(qwe == parseInt(data[i]['autor'])){ autor = assas[opcao].innerHTML; }                               
                        }
                    }

                    let row = '<tr><td><a href="edit_evento.php?id='+data[i]['id']+'" data-toggle="tooltip" data-placement="right" title="'+data[i]['descricao']+'">'+data[i]['titulo']+'</a></td><td>'+ (data[i]['anexo'].includes('anexo') ? '<a target="_blank" href="'+data[i]['anexo']+'"><i class="fa fa-file"></i></a>' : '-' )+'</td><td>'+data[i]['datahorainicio'][8]+data[i]['datahorainicio'][9]+'/'+data[i]['datahorainicio'][5]+data[i]['datahorainicio'][6]+' '+data[i]['datahorainicio'][11]+data[i]['datahorainicio'][12]+':'+data[i]['datahorainicio'][14]+data[i]['datahorainicio'][15]+'<br>'+data[i]['datahorafim'][8]+data[i]['datahorafim'][9]+'/'+data[i]['datahorafim'][5]+data[i]['datahorafim'][6]+' '+data[i]['datahorafim'][11]+data[i]['datahorafim'][12]+':'+data[i]['datahorafim'][14]+data[i]['datahorafim'][15]+'</td><td>'+autor+'</td></tr>';
                    if(data[i]['viz'][0] == 'SOMENTE EU') { $('#agenda-eu').append(row);
                    } else if(data[i]['viz'][0] == 'TODOS DA OM') { $('#agenda-bateria').append(row);
                    } else { $('#agenda-compartilhamento').append(row);
                    } 
                }
            }
        }
    });
}
    
function setFocusDay(value) { // essa função muda o dia do foco quando clicado (cor e lista de tarefas)
    focusDia = value.text;
    if(value.className.includes('m-1')){ reduzirMes();
    } else if(value.className.includes('m+1')){ aumentarMes(); } 
    loadCalendar();
    let head = '<tr><td>Título</td><td>Anexo</td><td>Início e Fim</td><td>Autor</td></tr>'; // limpa os cards de eventos
    $('#agenda-bateria').html(head);
    $('#agenda-eu').html(head);
    $('#agenda-compartilhamento').html(head);
}

async function loadCalendar(){
    for (let cel in listcelulas) {
    $("#"+listcelulas[cel]).removeClass();
    $("#"+listcelulas[cel]).addClass("btn btn-primary");
    $("#"+listcelulas[cel]).html('');
}

    let dia1 = new Date(focusAno, focusMes, 1);
    let diasemanadia1 = dia1.getDay();
    let primeirodia = new Date(year = focusAno, month = focusMes, day = 1);
    primeirodia.setDate(dia1.getDate() - diasemanadia1);
    for(let cel in listcelulas) {
        // insere o numero dos dias no calendário
        $("#"+listcelulas[cel]).html(primeirodia.getDate() < 10 ? '0' + primeirodia.getDate() : primeirodia.getDate());
        if((primeirodia.getMonth() < focusMes && primeirodia.getFullYear() == focusAno) || primeirodia.getFullYear() < focusAno) {
            // remove a cor dos dias que não são do mês atual
            $("#"+listcelulas[cel]).removeClass("btn-primary");
            $("#"+listcelulas[cel]).addClass("m-1");
            
        } else if((primeirodia.getMonth() > focusMes && primeirodia.getFullYear() == focusAno) || primeirodia.getFullYear() > focusAno) {
            // remove a cor dos dias que não são do mês atual
            $("#"+listcelulas[cel]).removeClass("btn-primary");
            $("#"+listcelulas[cel]).addClass("m+1");
        } else if(primeirodia.getDate() == focusDia && primeirodia.getMonth() == focusMes && primeirodia.getFullYear() == focusAno) {
            // muda a cor para outra em dastaque para o dia em foco inicial
            $("#"+listcelulas[cel]).removeClass("btn-primary");
            $("#"+listcelulas[cel]).removeClass("btn-warning");
            $("#"+listcelulas[cel]).addClass("btn-success");
        }
        primeirodia.setDate(primeirodia.getDate() + 1);
    }
    setActDay(focusDia, focusMes, focusAno);
    $("#mesatual").text(' '+meses[focusMes] + ' de ' + focusAno+' ');
}
let head = '<tr><td>Título</td><td>Anexo</td><td>Início e Fim</td><td>Autor</td></tr>'; // limpa os cards de eventos
$('#agenda-bateria').html(head);
$('#agenda-eu').html(head);
$('#agenda-compartilhamento').html(head);
loadCalendar();



      