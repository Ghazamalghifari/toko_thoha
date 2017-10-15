<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    //
     protected $fillable = ['id','no_faktur','keterangan','subtotal','user_buat','user_edit','created_at','updated_at']; 

   	public function user_buat(){
   		return $this->belongsTo('App\User','user_buat','id');
   	}     
   	public function user_edit(){
   		return $this->belongsTo('App\User','user_edit','id');
   	}     
}
