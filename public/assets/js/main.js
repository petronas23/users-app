$(function() {
	
	$('.ajax-logout').on('click', function(e) {
		e.preventDefault();
		console.log(123)
		$.ajax({
			method: 'POST',
			url: window.location.origin + '/sign-out',
			headers: {
				'X-CSRF-TOKEN': $('token[name="csrf-secret"]').data('key')
			},
			success: function(responseText, status, xhr) {
				window.location.href = window.location.origin + '/sign-in'
			},
			error: function(xhr, status, error) {
				console.log(xhr)
			}
		});
	});
	
});