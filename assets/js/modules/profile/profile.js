jQuery(document).ready(function(){

	// binds form submission and fields to the validation engine
	jQuery("#form1").validationEngine();
});	
$('#save').click(function(){
	if( $("#form1").validationEngine('validate') == true){
		var param = $('#form1').serialize();
		var url   = this_controller+'/proses';
		$.ajax({
				url         : url,
				type        : "POST",
				data        : param,
				beforeSend  : function(){$('#save').html('Loading...');},
				success     : function(msg){
								if(msg == 'err_email'){
									$('#email').validationEngine('showPrompt', 'Email sudah digunakan oleh User lain','');
								} else {
									$.gritter.add({title:page_name, text: msg});
								}	
								$('#save').html('Save');
														   }
		});
	}
});
