<div class="form-group{{ $errors->has('nama_satuan_barang') ? ' has-error' : '' }}">
	{!! Form::label('nama_satuan_barang', 'Nama Satuan Barang', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('nama_satuan_barang', null, ['class'=>'form-control','required','autocomplete'=>'off']) !!}
		{!! $errors->first('nama_satuan_barang', '<p class="help-block">:message</p>') !!}
	</div> 
		{!! Form::submit('Simpan', ['class'=>'btn btn-primary']) !!} 
</div>
