@extends('layouts.app')

@section('content')

<!-- MODAL TOMBOL SELESAI -->
  <div class="modal" id="modal_selesai" role="dialog" data-backdrop="">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">
			 <b>Anda Yakin Ingin Menyelesaikan Penjualan Ini ?</b> 
		</h4>
        </div>

        {!! Form::open(['url' => route('penjualan.store'),'method' => 'post', 'class'=>'form-horizontal']) !!}
		{!! Form::hidden('subtotal',  $subtotal , ['class'=>'form-control','placeholder'=>'Subtotal','required','autocomplete'=>'off', 'id'=>'subtotal']) !!} 
	        <div class="modal-body">
	        	<textarea class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan" rows="5"></textarea>
	        </div>
	        <div class="modal-footer"> 
	    		<button type="submit"  id="btn-simpan-penjualan" class="btn btn-success">Simpan</button>
	    		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        </div>
	    {!! Form::close() !!}
      </div>
      
    </div>
  </div>
<!-- / MODAL TOMBOL SELESAI -->

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="{{ url('/home') }} ">Home</a></li> 
				<li><a href="{{ url('/penjualan') }}">Penjualan</a></li>
				<li class="active">Tambah Penjualan</li>
			</ul>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">Tambah Penjualan</h2> 
				</div>

				<div class="panel-body">
					<div class="row">
						
						<div class="col-md-8"> 
					          {!! Form::open(['url' => route('penjualan.proses_tambah_tbs_penjualan'),'method' => 'post', 'class'=>'form-horizontal']) !!}
						          <div class="form-group{{ $errors->has('id_barang') ? ' has-error' : '' }}">
										{!! Form::label('id_barang', 'Pilih barang', ['class'=>'col-md-3 control-label']) !!}
										<div class="col-md-4">
											{!! Form::select('id_barang', []+App\Barang::pluck('nama_barang','id')->all(), null, ['class'=>'form-control js-selectize-reguler','required', 'placeholder' => '--SILAKAN PILIH--', 'id'=>'pilih_barang']) !!}
											{!! $errors->first('id_barang', '<p class="help-block">:message</p>') !!}
										</div>
									</div>

									<div class="form-group{{ $errors->has('jumlah_barang') ? ' has-error' : '' }}">
										{!! Form::label('jumlah_barang', 'Jumlah barang', ['class'=>'col-md-3 control-label']) !!}
										<div class="col-md-4">
											{!! Form::text('jumlah_barang', 1, ['class'=>'form-control','placeholder'=>'Jumlah barang','required','autocomplete'=>'off', 'id'=>'jumlah_barang']) !!}
											{!! $errors->first('jumlah_barang', '<p class="help-block" id="eror_jumlah_barang">:message</p>') !!}
										</div>
									</div>
									<div class="col-md-4 col-md-offset-3">
							   <button type="submit" class="btn btn-success" id="btn-submit-barang-modal">Tambah Barang</button>
									</div>
							 {!! Form::close() !!} 
					        </div>
						<div class="col-md-4">
									<div class="col-md-6"> 
											{!! Form::text('subtotal',  $subtotal , ['class'=>'form-control','placeholder'=>'Subtotal','required','autocomplete'=>'off', 'id'=>'subtotal','readonly']) !!}
											{!! $errors->first('subtotal') !!}
									</div>		
							<!-- TOMBOL BATAL -->
							{!! Form::open(['url' => route('penjualan.proses_hapus_semua_tbs_penjualan'),'method' => 'post', 'class' => 'form-group js-confirm', 'data-confirm' => 'Apakah Anda Ingin Membatalkan Penjualan ?']) !!} 						       		
						    <!--- TOMBOL SELESAI -->
						       	<button type="button" class="btn btn-primary" id="btnSelesai" data-toggle="modal" data-target="#modal_selesai">Selesai</button>

						       	<button type="submit" class="btn btn-danger" id="btnBatal">Batal</button>

							{!! Form::close() !!}
						</div>

						</div> 
					<!--TOMBOL SELESAI & BATAL -->
						<div class="col-md-4">
								<div class="form-group col-md-3">
					       			 
					       			  	
								</div>
								<div class="form-group col-md-2">												       			   
					       			
								</div>										
						</div>

					</div>
					<!--TABEL TBS ITEM 	MASUK -->
					<div class="table-responsive">
			         {!! $html->table(['class'=>'table-striped table']) !!} 
					</div>
				</div><!-- / PANEL BODY -->

			</div>
		</div>
	</div>
	</div>
@endsection

@section('scripts')
	{!! $html->scripts() !!}

	<script type="text/javascript">
		$(document).ready(function(){
    		$("#kode_barcode").focus();
		});
	</script>

	<script type="text/javascript">
	// Konfirmasi Penghapusan
		$(document.body).on('submit', '.js-confirm', function () {
			var $btnHapus = $(this)
			var text = $btnHapus.data('confirm') ? $btnHapus.data('confirm') : 'Anda yakin melakukan tindakan ini ?'
			var pesan_konfirmasi = confirm(text);
			return pesan_konfirmasi;
		});  
	</script>

	<script type="text/javascript">
		$(document).ready(function(){

			var pesan_error = $("#eror_jumlah_barang").text();

			if (pesan_error != "") {				
				$("#modal_barang").modal('show');
				$("#jumlah_barang").focus();
			}
			else{
				$("#modal_barang").modal('hide');
			}
		});	
	</script>


 	<script type="text/javascript">
 	//TOMBOL CARI
 	shortcut.add("f1", function() {
        $("#cari_barang").click();
    })
    
 	//TOMBOL SUBMIT BARCODE
 	shortcut.add("f2", function() {
        $("#btnBarcode").click();
    })
    
 	//TOMBOL SELESAI
 	shortcut.add("f8", function() {
        $("#btnSelesai").click();
    })
    
 	//TOMBOL BATAL
 	shortcut.add("f10", function() {
        $("#btnBatal").click();
    })
 	</script>
<!-- js untuk tombol shortcut -->
@endsection