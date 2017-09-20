<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TbsPenjualan extends Model
{
    //
    protected $primaryKey = 'id_tbs_penjualans';
    protected $fillable = ['id_tbs_penjualans','session_id','id_barang','jumlah_barang'];
}
