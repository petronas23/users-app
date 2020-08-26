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
				window.location.href = window.location.origin + '/subusers'
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

});

