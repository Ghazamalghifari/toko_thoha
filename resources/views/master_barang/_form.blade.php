<div class="form-group{{ $errors->has('nama_barang') ? ' has-error' : '' }}">
	{!! Form::label('nama_barang', 'Nama Barang', ['class'=>'col-md-3 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('nama_barang', null, ['class'=>'form-control','required','autocomplete'=>'off']) !!}
		{!! $errors->first('nama_barang', '<p class="help-block">:message</p>') !!}
	</div> 
		{!! Form::submit('Simpan', ['class'=>'btn btn-primary']) !!} 
</div>
