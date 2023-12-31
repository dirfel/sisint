

$(function(){

    "use strict";

    //MAX LENGTH
    // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    //input maxlenth
    $('#inputMaxLength').maxlength({
        alwaysShow: true,
        placement: 'top-right-inside'
    });
	$('#inputMaxLength2').maxlength({
        alwaysShow: true,
        placement: 'top-right-inside'
    });
	$('#inputMaxLength3').maxlength({
        alwaysShow: true,
        placement: 'top-right-inside'
    });
	$('#inputMaxLength4').maxlength({
        alwaysShow: true,
        placement: 'top-right-inside'
    });
	$('#inputMaxLength5').maxlength({
        alwaysShow: true,
        placement: 'top-right-inside'
    });
	$('#inputMaxLength6').maxlength({
        alwaysShow: true,
        placement: 'top-right-inside'
    });
    //textarea maxlenth
    $('#textareaMaxLength').maxlength({
        alwaysShow: true,
        placement: 'top-right-inside'

    });

    //SEARCHING & TAGS SELECTS
    // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    //Set bootstrap theme
    $.fn.select2.defaults.set( "theme", "bootstrap" );

    //Select2 basic example
    $("select").select2({
        placeholder: "Escolha uma opção",
        allowClear: false,
    });

    //MASKED INPUT
    // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    //Date mask example
    $("#date-mask").inputmask("date");

    //Currency mask example
    $("#currency-mask").inputmask("currency");

    //License car example
    $("#license-mask").inputmask("[9-]AAA-999");

    //Country phone example
    $("#phone-mask").inputmask("phone", {
        onKeyValidation: function () {
            var city = $(this).inputmask("getmetadata")["cd"];
            if(city){
                $('#country-name').html(city);
            }
        }
    });

    //DATE PICKERS
    // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    //Default datepicker example
    $('#default-datepicker').datepicker();

    //Range datepicker example
    $('#range-datepicker').datepicker({
        format: "dd/mm/yy",
        weekStart: 1,
        todayBtn: "linked",
        todayHighlight: true
    });

    //TIME PICKERS
    // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    //Default timepicker example
    $('#default-timepicker').timepicker();

    //24 hours timepicker example
    $('#24-timepicker').timepicker({
        "showMeridian": false
    });

    //COLOR PICKERS
    // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    //HEX colorpicker example
    $('#hex-colorpicker').colorpicker({
        format: "hex"
    });

    //RGBA colorpicker example
    $('#rgba-colorpicker').colorpicker({
        format: "rgba"
    });

    //All Formats colorpicker example
    $('#all-formats-colorpicker').colorpicker({});

    //Component colorpicker example
    $('#component-colorpicker').colorpicker({});

});


