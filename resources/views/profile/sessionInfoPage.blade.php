@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Session info page</h1>
@stop

@section('content')
<section class="content">
	<div class="container-fluid">
        <div class="row">
			<div class="col-12">
                @dump(Session::all())
			</div>
        </div>
	</div>
</section>
@stop

<!-- da -->