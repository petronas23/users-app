$(function() {
	$('.ajax-logout').on('click', function(e) {
		e.preventDefault();

		var token = $('#csrf-secret').data('key');

		$.ajax({
			method: 'POST',
			url: window.location.origin + '/sign-out',
			headers: {
				'X-CSRF-TOKEN': token
			},
			success: function(responseText, status, xhr) {
				window.location.href = window.location.origin + '/sign-in'
			},
			error: function(xhr, status, error) {
				console.log(xhr)
			}
		});
	});
	
	
	$('#loginForm').on('submit', function(event) {
		event.preventDefault();

		var form = $(this);
		var token = $('#csrf-secret').data('key');

		$.ajax({
			url: form.attr('action'),
			headers: {
				'X-CSRF-TOKEN': token
			},
			data: form.serialize(),
			type: form.attr('method'),
			success: function(response) {
				window.location.href = window.location.origin + '/profile/subusers'
			},
			error: function(response, xhr, message) {
				if(response.status == 422) {
					var errorMsg = '';
					$.each( response.responseJSON.errors, function( key, value ) {
						errorMsg += value[0] + '\n';
					});
					alert(errorMsg);
				}else{
					alert(response.responseJSON.message);
				}
			}
		});
	});


	$('#regForm').on('submit', function(event) {

		event.preventDefault();

		var form = $(this);
		var token = $('#csrf-secret').data('key');

		$.ajax({
			url: form.attr('action'),
			headers: {
				'X-CSRF-TOKEN': token
			},
			data: form.serialize(),
			type: form.attr('method'),
			success: function(response) {
				alert(response.message);
				window.location.href = window.location.origin + '/sign-in'
			},
			error: function(response, xhr, message) {
				if(response.status == 422) {
					var errorMsg = '';
					$.each( response.responseJSON.errors, function( key, value ) {
						errorMsg += value[0] + '\n';
					});
					alert(errorMsg);
				}else{
					alert(response.responseJSON.message);
				}
			}
		});
	});


	/**Profile JS */
	$('body').on("click", '.ajax-fanxy-modal', function() {
		var e = $(this);

		console.log(e.data('href'))

		$.fancybox.open({
			src  : e.data('href'),
			type : 'ajax',
			opts : {
				autoFocus: false,
				touch: false,
				clickSlide: false,
				afterShow : function( instance, current ) {
					// console.info( 'It\'s me, FanxyBox!' );
				}
			}
		});
	});

	$('body').on("click", '.ajax-btn-action', function() {
		var element = $(this);
		var token = $('#csrf-secret').data('key');

		var confirmMsg = element.data('confirm');
		if(typeof confirmMsg !== "undefined"){
			if(!confirm(confirmMsg)){
				return '';
			}
		}
		
		$.ajax({
			url: element.data('href'),
			headers: {
				'X-CSRF-TOKEN': token
			},
			data: element.data(),
			type: 'POST',
			success: function(response) {
				tableDT.ajax.reload();
				alert(response.message);
			},
			error: function(response, xhr, message) {
				if(response.status == 422) {
					var errorMsg = '';
					$.each( response.responseJSON.errors, function( key, value ) {
						errorMsg += value[0] + '\n';
					});
					alert(errorMsg);
				}else{
					alert(response.responseJSON.message);
				}
			}
		});
	});

	function prepareSocials(socials, id, type)
	{
		var socialsBlock = '<div class="social-auth-links text-center">'+
						'<p>- OR -</p>';
					
		for (var i = 0; i < socials.length; i++) {
			socialsBlock += '<a class="btn btn-git github-oauth-btn waves-effect waves-light" href="http://users-app.loc/social-auth/' + socials[i].type +'/'+ id +'/'+ type +'">'+ socials[i].name + '</a>';
		}
		socialsBlock += '</div>';

		$('.social-auth-links').replaceWith(socialsBlock)
	}

	function prepareSelect(subusers, user_name)
	{
		var select = '<div class="users-select text-center">'+
			'<p>- OR -</p>' +
		'<select name="cars" id="users">'+
			'<option data-parent="true" value="' + subusers[0]['user_id'] + '">' + user_name +'</option>';

		for (var i = 0; i < subusers.length; i++) {
			select += '<option value="' + subusers[i]['id'] + '">' + subusers[i]['name'] +'</option>';
		}

		select += '</select> </div>';

		$('.users-select').replaceWith(select)
	}

	var user={}
	$('.ajax-changeed-email').on("change",  function() {
		var email = $(this).val();
		var token = $('#csrf-secret').data('key');
		$.ajax({
			url: window.location.origin + '/check-user',
			headers: {
				'X-CSRF-TOKEN': token
			},
			data: {email: email},
			type: 'POST',
			success: function(response) {
				user = response
				$('#user_group').val(user.id);
				if (typeof user.user_auths !== "undefined") {
					prepareSocials(user.user_auths, user.id, 'user');
				}

				if (typeof user.subusers !== "undefined") {
					prepareSelect(user.subusers, user.name)
				}
			},
			error: function(response, xhr, message) {
				console.log(response)
			}
		});
	});

	function checkSelectedSocial()
	{
		
	}

	$('body').on("change", '.users-select',  function() {
		
		var id = $( this ).find(" option:selected" ).val();
		$('#user_group').val(id);
		$('#user_group').attr('name', 'subuser');

		if($( this ).find(" option:selected" ).data('parent')){
			$('#user_group').attr('name', 'user');
			if(user.user_auths.length > 0){
				prepareSocials(user.user_auths, user.id, 'user');
			}else{
				$('.social-auth-links').replaceWith('<div class="social-auth-links"> </div>')
			}
			
		}else{
			for (var i = 0; i < user.subusers.length; i++) {
				console.log(user.subusers[i]);
				if(user.subusers[i]['id'] == id){
					if(user.subusers[i]['user_auths'].length > 0){
						prepareSocials(user.subusers[i]['user_auths'], user.subusers[i]['id'], 'subuser');
					}else{
						$('.social-auth-links').replaceWith('<div class="social-auth-links"> </div>')
					}
					
				}
			}
		}

	});


});

