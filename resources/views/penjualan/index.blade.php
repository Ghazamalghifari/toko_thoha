@extends('layouts.app')
@section('content')
<div class="container"> 
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="{{ url('/home') }}">Home</a></li>
				<li class="active">Penjualan</li>
			</ul>
 
			
			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">Penjualan</h2>
				</div>

				<div class="panel-body"> 
					<p> <a class="btn btn-primary" href="{{ route('penjualan.create') }}">Tambah Penjualan</a> 
						  <button data-toggle="collapse" data-target="#export" id="button_export" class="btn btn-primary"> <span class="glyphicon glyphicon-export"></span> Export</button>
				<!-- MEMBUAT FILTER PENJADWALAN -->
					 <button data-toggle="collapse" data-target="#filter" id="button_filter" class="btn btn-primary"> <span class="glyphicon glyphicon-filter"></span> Filter</button> 
					 <a class="btn btn-primary" href="{{ route('penjualan.index') }}"> <span class="glyphicon glyphicon-remove"></span>  Hapus Filter</a> 
				<!-- //MEMBUAT FILTER PENJADWALAN --> </p> 
						  <br>
				<!-- MEMBUAT FILTER EXPORT PENJUALAN -->
					<div  id="export"   style="display:none;">
					{!! Form::open(['url' => route('penjualan.export_penjualan'),'method' => 'post', 'class'=>'form-inline']) !!}
					@include('penjualan._form_export')
					{!! Form::close() !!}
					</div> 
				<!-- //MEMBUAT FILTER EXPORT PENJUALAN -->
				<!-- MEMBUAT FILTER PENJUALAN -->
					<div  id="filter"   style="display:none;">
					{!! Form::open(['url' => route('penjualan.export_penjualan'),'method' => 'post', 'class'=>'form-inline']) !!}
					@include('penjualan._form_filter')
					{!! Form::close() !!}
					</div> 
				<!-- //MEMBUAT FILTER PENJUALAN -->
				<br> 
					<div class="table-responsive">
					{!! $html->table(['class'=>'table-striped table']) !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
{!! $html->scripts() !!}
<script type="text/javascript">
	// confirm delete
		$(document.body).on('submit', '.js-confirm', function () {
		var $el = $(this)
		var text = $el.data('confirm') ? $el.data('confirm') : 'Anda yakin melakukan tindakan ini\
	?'
		var c = confirm(text);
		return c;
	}); 
</script>

<script type="text/javascript">
$(document.body).on('click', '#button_filter', function () {
	$("#filter").show();
	$("#export").hide();
	});
$(document.body).on('click', '#button_export', function () {
	$("#export").show();
	$("#filter").hide();
	});  
</script>

<script type="text/javascript">  
$('.datepicker').datepicker({
    format: 'dd/mm/yyyy', 
    autoclose: true,
});  
</script>
@endsection
