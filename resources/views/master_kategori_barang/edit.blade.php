@extends('layouts.app')

@section('content')
<div class="container">
		<div class="row">
			<div class="col-md-12">
				<ul class="breadcrumb">
					<li><a href="{{ url('/home') }} ">Home</a></li>
					<li><a href="{{ url('/admin/master-kategori-barang') }}">Kategori Barang</a></li>
					<li class="active">Edit Kategori Barang</li>
				</ul>

				<div class="panel panel-default">
					<div class="panel-heading">
						<h2 class="panel-title">Edit Kategori Barang</h2>
					</div>

					<div class="panel-body">
						{!! Form::model($master_kategori_barang, ['url' => route('master-kategori-barang.update', $master_kategori_barang->id), 'method' => 'put', 'files'=>'true','class'=>'form-horizontal']) !!}
						@include('master_kategori_barang._form')
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
</div>
@endsection
	