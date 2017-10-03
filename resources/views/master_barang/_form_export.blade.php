<br>
<div class="form-group{{ $errors->has('id_satuan') ? ' has-error' : '' }}">
		{!! Form::select('id_satuan', ['semua' => 'Semua Satuan'	]+App\SatuanBarang::pluck('nama_satuan_barang','id')->all(), $value = 'semua', ['class'=>'form-control js-selectize-reguler', 'placeholder' => 'Pilih Satuan']) !!}
		{!! $errors->first('id_satuan', '<p class="help-block">:message</p>') !!}
	
</div>

<div class="form-group{{ $errors->has('id_kategori') ? ' has-error' : '' }}">
		{!! Form::select('id_kategori', ['semua' => 'Semua Kategori'	]+App\KategoriBarang::pluck('nama_kategori_barang','id')->all(), $value = 'semua', ['class'=>'form-control js-selectize-reguler', 'placeholder' => 'Pilih Kategori']) !!}
		{!! $errors->first('id_kategori', '<p class="help-block">:message</p>') !!} 
</div>


<div class="form-group{{ $errors->has('kelontongan') ? ' has-error' : '' }}">
		{!! Form::select('kelontongan', ['semua' => 'Semua Kelontongan','kelontongan_biasa'=>'Kelontongan Biasa',
		'kelontongan_unik'=>'Kelontongan Unik'], $value = 'semua', ['class'=>'form-control js-selectize-reguler', 'placeholder' => 'Pilih Kelontongan']) !!}
		{!! $errors->first('kelontongan', '<p class="help-block">:message</p>') !!} 
</div>

<div class="form-group">
	
		{!! Form::submit('Download Excel', ['class'=>'btn btn-primary']) !!}
	
</div>