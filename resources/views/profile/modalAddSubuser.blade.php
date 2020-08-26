
  <div class="container-fluid" style="max-width: 800px;">
	<div class="row">
	  <!-- left column -->
	<div class="col-md-12">
		<!-- general form elements -->
		<div class="card card-primary">
		  <div class="card-header">
			<h3 class="card-title">{!! $title !!}</h3>
		  </div>
		  <!-- /.card-header -->
		  <!-- form start -->
		  <form role="form" method="POST" action="{!! $action !!}" class="ajax-form-submit">
			<div class="card-body">
				<div class="form-group">
					<label for="exampleInputEmail1">Name</label>
					<input type="text" name="subuser_name" class="form-control " value="{!! (isset($subuser['name'])) ? $subuser['name'] : '' !!}" placeholder="Enter name">
					@if(isset($subuser))
					<input type="hidden" name="subuser_id" class="form-control " value="{!! $subuser['id'] !!}" placeholder="Enter name">
					@endif
				</div>
			</div>

			<div class="card-footer">
			  <button type="submit" class="btn btn-primary">Submit</button>
			</div>
		  </form>
		</div>
		<!-- /.card -->
	
	  </div>
	
	</div>
	<!-- /.row -->
  </div>
  
  <script>
	$('.ajax-form-submit').on("submit", function(e) {
		e.preventDefault();

		var form = $(this);
		var token = $('#csrf-secret').data('key');
	
		$.ajax({
			method: form.attr('method'),
			url: form.attr('action'),
			data: form.serialize(),
			headers: {
				'X-CSRF-TOKEN': token
			},
			success: function(response, status, xhr) {
				$.fancybox.close();
				tableDT.ajax.reload();
				alert(response.message);
			},
			error: function(response, status, error) {
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
  </script>