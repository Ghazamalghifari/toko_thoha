<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    //
    protected $primaryKey = 'id_detail_penjualans';
    protected $fillable = ['id_detail_penjualans','no_faktur','id_barang','jumlah_barang'];
}
