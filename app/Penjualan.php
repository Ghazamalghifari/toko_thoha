<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    //
     protected $fillable = ['id','no_faktur','keterangan','subtotal','user_buat'];
}