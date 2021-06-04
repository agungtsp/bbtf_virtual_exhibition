$(document).ready(function(){
    $('.owl-index').owlCarousel({
        loop: true,
        margin: 0,
        items: 1,
        nav: false,
        dots: true,
        dotsData: true,
        autoplay: true,
    });
    $('.owl-platinum').owlCarousel({
        loop: true,
        margin: 0,
        items: 1,
        nav: false,
        autoplay: true,
    });
    $('.owl-logo').owlCarousel({
        loop: false,
        margin: 16,
        autoplay: true,
        nav: true,
        navText:["<div class='nav-btn prev-slide'></div>","<div class='nav-btn next-slide'></div>"],
        responsive: {
            0: {
                items:5
            },
            600: {
                items:10
            },
            1000: {
                items:12
            }
        }
    });
    $('[data-title="tooltip"]').tooltip();
});
function clear_form_elements(ele) {
    var kelas;
    $(ele).find(':input').each(function() {
      kelas = $(this).attr('class');
      if(!$(this).hasClass('no_clear')){ 
        switch(this.type) {
          case 'password':
          case 'select-multiple':
          case 'select-one':
            $("#"+$(this).attr('id')).val($("#"+$(this).attr('id')+" option:first").val());
            break;
          case 'text':
          case 'email':
          case 'file':
          case 'hidden':
          case 'textarea':
              $(this).val('');
              break;
          case 'checkbox':
          case 'radio':
            if ($(this).attr('name')!='choose_for' && $(this).attr('name')!='choose_for_checkbox') {
              $('[name="'+$(this).attr('name')+'"]').prop('checked', false);
            }
            break;
          case 'range':
            $(this).val($(this).attr('min')).change();
            break;
          case 'select':
            $(this).val('');
            $(this).selectpicker('val','').refresh();
            break;
        }       
      }
        
    });
  }
function swalAlert(text,type) {
    type     = type ? type : 'success';//success//info//warning//error
    swal('',text,type);
}
$( document ).ready(function() {
  $('#brochure, #compro').on('shown.bs.modal', function (event) {
    $('.pdf-viewer').each(function(i, obj) {
      $(this).attr("data", $(this).attr("data-src"));
    });
    $('.pdf-viewer iframe').each(function(i, obj) {
      $(this).attr("src", $(this).attr("data-src"));
    });
  });
  $("#modalVideo").on('shown.bs.modal', function (event) {
    $('.list-video video source').each(function(i, obj) {
      $(this).attr("src", $(this).attr("data-src"));
      $(this).parent()[0].load();
    });
  });
});