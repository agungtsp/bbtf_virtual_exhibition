$(document).on('click','#btn_login',function(){
	if ($('#form_login').parsley().validate()) {
		var btn = $(this);
		btn.text('loading...')
		btn.attr('disabled','disabled');
		$.ajax({
			url      : base_url+'login/check_login',
			data     : $('#form_login').serialize(),
			type     : 'post',
			dataType : 'json',
		}).done(function(ret){
			btn.text('Login')
			btn.removeAttr('disabled');
			if (ret.error==0) {
				clear_form_elements('#form_login');
				swal({
					text: ret.message,
					type: "success",
					icon: "success"
				}).then(function() {
					window.location = base_url;
				});
			} else {
				swalAlert(ret.message,'error');
			}
		}).fail(function(ret){
			btn.removeAttr('disabled');
			alert('error');
		});
	}
	return false;
});
$(document).on('click','#btn_forgot_password',function(){
	if ($('#form_forgot_password').parsley().validate()) {
		var btn = $(this);
		btn.text('loading...')
		btn.attr('disabled','disabled');
		$.ajax({
			url      : base_url+'login/forgot_password',
			data     : $('#form_forgot_password').serialize(),
			type     : 'post',
			dataType : 'json',
		}).done(function(ret){
			btn.text('Submit')
			btn.removeAttr('disabled');
			if (ret.error==0) {
				clear_form_elements('#form_forgot_password');
				swalAlert(ret.message);
			} else {
				swalAlert(ret.message,'error');
			}
		}).fail(function(ret){
			btn.removeAttr('disabled');
			alert('error');
		});
	}
	return false;
});

$(document).on('click','#btn_signup',function(){
	if ($('#form_signup').parsley().validate()) {
		var btn = $(this);
		btn.text('loading...')
		btn.attr('disabled','disabled');
		$.ajax({
			url      : base_url+'login/signup',
			data     : $('#form_signup').serialize(),
			type     : 'post',
			dataType : 'json',
		}).done(function(ret){
			btn.text('Submit')
			btn.removeAttr('disabled');
			if (ret.error==0) {
				clear_form_elements('#form_signup');
				swalAlert(ret.message);
				$('#login-tab').click();
			} else {
				swalAlert(ret.message,'error');
			}
		}).fail(function(ret){
			btn.removeAttr('disabled');
			alert('error');
		});
	}
	return false;
});