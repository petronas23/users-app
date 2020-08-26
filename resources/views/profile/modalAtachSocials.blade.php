
  <div class="container-fluid" style="max-width: 800px;">
	<div class="row">
	  <!-- left column -->
	<div class="col-md-12">
		<!-- general form elements -->
		<div class="card card-primary">
		  <div class="card-header">
			<h3 class="card-title"> Attach Authentications</h3>
		  </div>
		  <!-- /.card-header -->
		  <!-- form start -->
		  <form role="form" method="POST" action="{!! url('/profile/subusers/ajax-attach-socials') !!}" class="ajax-form-submit">
			<div class="card-body">
            <div class="row">
                    <div class="col-sm-4">
                      <!-- checkbox -->
                      <div class="form-group">
                        <input type="hidden" value="{!! $id_subuser !!}" name="id_subuser">
                        @foreach($socials as $key => $social)
                            @if(($key & 1))
                            <div class="form-check">
                                <input name="{!! $social['type'] !!}" class="form-check-input" type="checkbox" {!! (isset($userSocialIds[$social['id']])) ? 'checked=""' : '' !!}>
                                <label class="form-check-label">{!! $social['name'] !!}</label>
                            </div>
                            @endif
                        @endforeach

                      </div>
                    </div> 
                    <div class="col-sm-4">
                      <!-- radio -->
                      <div class="form-group">

                    
                      @foreach($socials as $s_key => $s_social)
                            @if(!($s_key & 1))
                            <div class="form-check">
                                <input name="{!! $s_social['type'] !!}"  class="form-check-input" type="checkbox" {!! (isset($userSocialIds[$s_social['id']])) ? 'checked=""' : '' !!} >
                                <label class="form-check-label">{!! $s_social['name'] !!}</label>
                            </div>
                            @endif
                        @endforeach
                     
                      </div>
                    </div>
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