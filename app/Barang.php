<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = ['id','nama_barang','harga_barang','id_satuan_barang','id_kategori_barang','kelontongan','jumlah_barang','created_at'];
  
   	public function satuan_barang(){
   		return $this->belongsTo('App\SatuanBarang','id_satuan_barang','id');
   	}

   	public function kategori_barang(){
   		return $this->belongsTo('App\KategoriBarang','id_kategori_barang','id');
   	}
}
