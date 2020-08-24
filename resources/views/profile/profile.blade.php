@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>{!! $page !!}</h1>
@stop

@section('content')
<section class="content">
	<div class="container-fluid">
        <div class="row">
			<div class="col-12">
				<div class="card">
				<div class="card-header">
					<div class="btn-group float-right">
						<div class="box-tools">
							<button data-href="{!! url('/profile/subusers/ajax-add-subuser-modal') !!}" type="button" class="btn btn-success ajax-fanxy-modal">
								<i class="fa fa-plus"></i>
							</button>
						</div>
					</div>
				</div>
				  <!-- /.card-header -->
				<div class="card-body">
					
					<div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
						<div class="row">
							<div class="col-sm-12">
								<table id="subusers-dt" class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
									<thead>
                                        <tr>
                                            @foreach ($cols as $col)
                                                <th> {!! $col !!} </th>
                                            @endforeach
                                        </tr>
									</thead>
								  <tbody>

								  </tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				</div>
			</div>
        </div>
	</div>
</section>
@stop

@section('css')
   
@stop

@section('js')
 <script type="text/javascript">
   tableDT = $('#subusers-dt').DataTable({
		serverSide: true,
		pageLength: 5,
		lengthMenu: [ 5, 10, 25, 50 ],
		searching: false,
		autoWidth: false,
		reload: true,
		bLengthChange: true,
		order: [[0, "desc"]],
		pagingType: "simple_numbers",
		"initComplete": function(settings, json) {

 		},
		fnDrawCallback: function(oSettings) {
		    var pagination = $(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
		    pagination.toggle(this.api().page.info().pages > 0);
		},
		ajax: function (data, callback, settings) {
			
			// if (!dtFilter) {
			// 	dtFilter = $('.dt-filter').dtFilters({
			// 		container: '.dt-filter-container',
			// 		onDelete: function(){
			// 			if ($('.filter-plugin-list').children().length === 0)
			// 			 	$('.dt-filter-container').hide();
			// 		},
			// 		callBack: function () {
			// 			table.draw();
			// 		}
			// 	});
			// }

			// data.filters = dtFilter.getDTFilter();

			// data.filters.forEach(function(x, index) {
			// 	if(x.name == 'start_date' || x.name == 'end_date') {
			// 		data.filters[index].value = $('[name="' + x.name + '"]').data('datepicker').getFormattedDate('dd.mm.yyyy');
			// 	}
			// });

			$.ajax({
				dataType: "JSON",
				type: "POST",
				headers: {
					'X-CSRF-TOKEN': $('#csrf-secret').data('key')
				},
				data: data,
				url: window.location.origin + '/profile/subusers',
				success: function (data, textStatus, jqXHR) {
					callback(data, textStatus, jqXHR);
				}
			});
		},
		columns: [
            {
				data: 'id',
				className: 'va-m ta-c',
				visible: false,
				searchable: false
			},
			{
				data: "name",
				className: 'va-m ta-c',
				sortable: false,
			},
			{
				data: "created_at",
				className: 'va-m ta-c',
				sortable: false,
			},
			{
				data: "user_id",
				className: 'va-m ta-c',
				sortable: false,
				render: function ( data, type, row ) {
					return row.name;
				}
			},
        ],
	});

</script>
@stop
<!-- da -->