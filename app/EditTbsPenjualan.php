<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EditTbsPenjualan extends Model
{
    //
    protected $primaryKey = 'id_edit_tbs_penjualans';
    protected $fillable = ['id_edit_tbs_penjualans','no_faktur','id_barang','jumlah_barang','total_harga'];
    
   	public function barang(){
   		return $this->belongsTo('App\Barang','id_barang','id');
   	}
}
