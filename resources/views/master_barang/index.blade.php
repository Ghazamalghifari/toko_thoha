@extends('layouts.app')
@section('content')
<div class="container"> 
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="{{ url('/home') }}">Home</a></li>
				<li class="active">Barang</li>
			</ul>
 
			<div class="panel panel-default">  
				<div class="panel-body"> 
				<div class="container">
				<div class="row">
					<div class="col-md-1" style="padding-left: 0%">
					<a class="btn btn-primary" href="{{ route('master-barang.create') }}">Tambah Barang</a>
				</div>
				
					<div class="col-md-1" style="padding-left: 3%"> 
						 <a class="btn btn-primary" href="{{ route('master-barang.index') }}">Semua </a> 
				 	</div>

					<div class="col-md-1"> 
					    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"> Satuan 
					    <span class="caret"></span></button>
					    <ul class="dropdown-menu"> 
					        @foreach($satuan_barang as $satuan_barangs)
							    <li><a href="{{ route('master-barang.filter_satuan_barang',$satuan_barangs->id) }}">{{ $satuan_barangs->nama_satuan_barang }}</a></li>
							@endforeach
					    </ul> 
					</div> 

					<div class="col-md-1"> 
					    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Kategori
					    <span class="caret"></span></button>
					    <ul class="dropdown-menu"> 
					        @foreach($kategori_barang as $kategori_barangs)
							    <li><a href="{{ route('master-barang.filter_kategori_barang',$kategori_barangs->id) }}">{{ $kategori_barangs->nama_kategori_barang }}</a></li>
							@endforeach
					    </ul> 
					</div> 

						<div class="col-md-1">
					<!-- MEMBUAT TOMBOL EXPORT EXCEL -->
						  <button data-toggle="collapse" data-target="#export" id="button_export" class="btn btn-primary"> <span class="glyphicon glyphicon-export"></span> Export</button> 
					<!-- //MEMBUAT TOMBOL EXPORT EXCEL -->
						</div> 
					<div class="col-md-2" style="padding-right: 10%%">
						  <div class="dropdown">
					    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Kelontongan
					    <span class="caret"></span></button>
					    <ul class="dropdown-menu">
					      <li><a href="{{ url('master-barang/filter-kelontongan-barang/1') }}">Kelontongan Biasa</a></li>
					      <li><a href="{{ url('master-barang/filter-kelontongan-barang/2') }}">Kelontongan Unik</a></li> 
					    </ul>
					  </div>  
					</div>

 					</div>
				</div>
			</div>
		</div>
			
			<div class="panel panel-default" id="export"   style="display:none;">
				<div class="panel-heading">
					<h2 class="panel-title">Export Barang</h2>
				</div>


				<!-- MEMBUAT FILTER EXPORT BARANG -->
					<div>
					{!! Form::open(['url' => route('master-barang.export_barang'),'method' => 'post', 'class'=>'form-inline']) !!}
					@include('master_barang._form_export')
					{!! Form::close() !!}
					</div> 
				<!-- //MEMBUAT FILTER EXPORT BARANG -->
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">Barang</h2>
				</div>

				<div class="panel-body"> 
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
	$(document.body).on('click', '#button_export', function () {
		$("#export").show(); 
		});  
</script>

@endsection
