<div class="form-group{{ $errors->has('nama_barang') ? ' has-error' : '' }}">
	{!! Form::label('nama_barang', 'Nama Barang', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('nama_barang', null, ['class'=>'form-control','required','autocomplete'=>'off']) !!}
		{!! $errors->first('nama_barang', '<p class="help-block">:message</p>') !!}
	</div>  
</div> 

<div class="form-group{{ $errors->has('harga_barang') ? ' has-error' : '' }}">
	{!! Form::label('harga_barang', 'Harga Barang', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('harga_barang', null, ['class'=>'form-control','required','autocomplete'=>'off']) !!}
		{!! $errors->first('harga_barang', '<p class="help-block">:message</p>') !!}
	</div>  
</div>

<div class="form-group{{ $errors->has('id_satuan_barang') ? ' has-error' : '' }}">
	{!! Form::label('id_satuan_barang', 'Satuan Barang', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::select('id_satuan_barang', 
		[''=>'']+App\SatuanBarang::pluck('nama_satuan_barang','id')->all(),
		null, ['class'=>'form-control js-selectize-reguler', 'placeholder' => 'Silahkan Pilih']) !!}
		{!! $errors->first('id_satuan_barang', '<p class="help-block">:message</p>') !!}
	</div>
</div>

<div class="form-group{{ $errors->has('id_kategori_barang') ? ' has-error' : '' }}">
	{!! Form::label('id_kategori_barang', 'Kategori Barang', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::select('id_kategori_barang', 
		[''=>'']+App\KategoriBarang::pluck('nama_kategori_barang','id')->all(),
		null, ['class'=>'form-control js-selectize-reguler', 'placeholder' => 'Silahkan Pilih']) !!}
		{!! $errors->first('id_kategori_barang', '<p class="help-block">:message</p>') !!}
	</div>
</div>


<div class="form-group{{ $errors->has('kelontongan') ? ' has-error' : '' }}">
	{!! Form::label('kelontongan', 'Kelontongan', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::select('kelontongan', 
		['kelontongan_biasa'=>'Kelontongan Biasa',
		'kelontongan_unik'=>'Kelontongan Unik'], 
		null, ['class'=>'form-control js-selectize-reguler', 'placeholder' => 'Silahkan Pilih']) !!}
		{!! $errors->first('kelontongan', '<p class="help-block">:message</p>') !!}
	</div>
</div>

<div class="form-group{{ $errors->has('jumlah_barang') ? ' has-error' : '' }}">
	{!! Form::label('jumlah_barang', 'Jumlah Barang', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('jumlah_barang', null, ['class'=>'form-control','required','autocomplete'=>'off']) !!}
		{!! $errors->first('jumlah_barang', '<p class="help-block">:message</p>') !!}
	</div>  
</div>

<div class="form-group{{ $errors->has('keterangan') ? ' has-error' : '' }}">
	{!! Form::label('keterangan', 'Keterangan', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('keterangan', null, ['class'=>'form-control','autocomplete'=>'off']) !!}
		{!! $errors->first('keterangan', '<p class="help-block">:message</p>') !!}
	</div>  
</div>

<div class="form-group">
	<div class="col-md-4 col-md-offset-2">
		{!! Form::submit('Simpan', ['class'=>'btn btn-primary']) !!}
	</div>
</div>