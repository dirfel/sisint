"use strict";

$(".select").select2({

  theme: "bootstrap",
  placeholder: "Escolha uma opção",
  allowClear: true,
});
$(".phone").inputmask({ mask: "99-9999-9999" });
$(".cellPhone").inputmask({
  mask: "99-99999-9999",
});
$(".dateBirth").inputmask({ mask: "99/99/9999", placeholder: "dd/mm/aaaa" });
$(".cpf").inputmask({ mask: "999.999.999-99" });
$(".idt").inputmask({ mask: "999999999-9" });
$(".placa").inputmask({ mask: "aaa-9*99" });
$(".fichaVtr").inputmask({ mask: "9999/99" });
$(".odometro").inputmask({ mask: "999.999" });
$(".date").datepicker({ clearBtn: true, orientation: "bottom" });
$(".time").timepicker({
  showMeridian: false,
  minuteStep: 1,
  defaultTime: false,
});
$(".maxLength").maxlength({ alwaysShow: true, placement: "top-left" });
$(".date-time-color").hover(
  function () {
    $(this).addClass("bg-info b-sm b-scale-6 color-dark");
  },
  function () {
    $(this).removeClass("bg-info b-sm b-scale-6 color-dark");
  }
);
