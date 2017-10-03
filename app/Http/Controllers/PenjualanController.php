<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB; 
use App\Penjualan;  
use App\TbsPenjualan;  
use App\TbsSubtotalPenjualan;  
use App\DetailPenjualan;
use App\EditTbsPenjualan;  
use App\Barang;  
use Session;
use Auth; 

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        //
        if ($request->ajax()) {
            # code...
            $penjualan = Penjualan::select(['id','no_faktur','keterangan','subtotal','user_buat','user_edit','created_at','updated_at']);
            return Datatables::of($penjualan)->addColumn('action', function($data){
            $detail_penjualan = DetailPenjualan::with(['barang'])->where('no_faktur',$data->no_faktur)->get();
                    return view('penjualan._action', [
                        'model'     => $data,
                        'data_detail_penjualan'     => $detail_penjualan,
                        'form_url'  => route('penjualan.destroy', $data->id),
                        'edit_url'  => route('penjualan.proses_form_edit', $data->id),
                        'confirm_message'   => 'Yakin Mau Menghapus Penjualan ?', 
                        ]);
                })->addColumn('subtotal', function($barang){ 
                    $subtotal = number_format($barang->subtotal,0,',','.'); 
                    return $subtotal;
                })->make(true);
        }
        $html = $htmlBuilder
        ->addColumn(['data' => 'no_faktur', 'name' => 'no_faktur', 'title' => 'No Faktur'])  
        ->addColumn(['data' => 'user_buat', 'name' => 'user_buat', 'title' => 'User Buat']) 
        ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => 'Waktu Buat'])   
        ->addColumn(['data' => 'user_edit', 'name' => 'user_edit', 'title' => 'User Edit'])  
        ->addColumn(['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Waktu Edit'])  
        ->addColumn(['data' => 'subtotal', 'name' => 'subtotal', 'title' => 'Subtotal'])  
        ->addColumn(['data' => 'keterangan', 'name' => 'keterangan', 'title' => 'keterangan'])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable'=>false]);

        return view('penjualan.index')->with(compact('html'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) { 
            $session_id = session()->getId();
            $tbs_penjualan = TbsPenjualan::with(['barang'])->where('session_id', $session_id)->get();
            return Datatables::of($tbs_penjualan)->addColumn('action', function($tbspenjualan){
                    return view('penjualan._hapus_barang', [
                        'model'     => $tbspenjualan,
                        'form_url'  => route('penjualan.proses_hapus_tbs_penjualan', $tbspenjualan->id_tbs_penjualans),   
                        'confirm_message'   => 'Yakin Mau Menghapus Barang ?'
                        ]);
                })->addColumn('total_harga', function($barang){ 
                    $total_harga = number_format($barang->total_harga,0,',','.'); 
                    return $total_harga;
                })->make(true);
        }

        $html = $htmlBuilder 
        ->addColumn(['data' => 'barang.nama_barang', 'name' => 'barang.nama_barang', 'title' => 'Barang', 'orderable' => false, 'searchable'=>false ]) 
        ->addColumn(['data' => 'jumlah_barang', 'name' => 'jumlah_barang', 'title' => 'Jumlah'])
        ->addColumn(['data' => 'total_harga', 'name' => 'total_harga', 'title' => 'Total'])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Hapus', 'orderable' => false, 'searchable'=>false]);

        $session_id = session()->getId();

        $subtotal_kosong = 0; 

            $tbs_penjualan = TbsPenjualan::select('total_harga')->where('session_id', $session_id);
            if ($tbs_penjualan->count() == 0 ) { 
              $subtotal = '0'; 
            } elseif ($tbs_penjualan->count() == 1) {
              $subtotal =  $subtotal_kosong += $tbs_penjualan->first()->total_harga;
            }else {
            $tbs_penjualans = TbsPenjualan::select('total_harga')->where('session_id', $session_id)->get();
              foreach ($tbs_penjualans as $data_tbs) {
                 $subtotal_kosong += $data_tbs->total_harga;
              }
              $subtotal = $subtotal_kosong;
            }

        return view('penjualan.create',['subtotal'=>$subtotal])->with(compact('html'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //PROSES SELESAI TRANSAKSI ITEM MASUK
    public function store(Request $request) {

        $session_id = session()->getId();
        $user = Auth::user()->id;
        $no_faktur = $this->no_faktur();

      //INSERT DETAIL ITEM MASUK
        $data_barang_penjualan = TbsPenjualan::where('session_id', $session_id);

        if ($data_barang_penjualan->count() == 0) {

           $pesan_alert = 
               '<div class="container-fluid"> 
                    <b>Belum ada Barang Yang Di inputkan</b>
                </div>';

        Session::flash("flash_notification", [
            "level"     => "danger",
            "message"   => $pesan_alert
        ]);

          
          return redirect()->back();
        }

        foreach ($data_barang_penjualan->get() as $data_tbs) {
 
            $barang = Barang::find($data_tbs->id_barang);
            $barang->jumlah_barang -= $data_tbs->jumlah_barang;
            $barang->save();
            $detail_penjualan = DetailPenjualan::create([
                'id_barang' =>$data_tbs->id_barang,              
                'no_faktur' => $no_faktur,
                'jumlah_barang' =>$data_tbs->jumlah_barang,
                'total_harga' =>$data_tbs->total_harga,
            ]);
        }

      //INSERT ITEM MASUK
        if ($request->keterangan == "") {
          $keterangan = "-";
        }
        else{
          $keterangan = $request->keterangan;
        }

        $penjualan = Penjualan::create([
            'no_faktur' => $no_faktur,
            'keterangan' =>$keterangan,
            'user_buat' => $user, 
            'user_edit' => $user, 
            'subtotal' => $request->subtotal, 
        ]);

        if (!$penjualan) {
          return back();
        }
        
        //HAPUS TBS ITEM MASUK
        $data_barang_penjualan->delete();

        $pesan_alert = 
               '<div class="container-fluid">
                    <div class="alert-icon">
                    <i class="material-icons">check</i>
                    </div>
                    <b>Sukses : Berhasil Melakukan Transaksi Penjualan Faktur "'.$no_faktur.'"</b>
                </div>';

        Session::flash("flash_notification", [
            "level"     => "success",
            "message"   => $pesan_alert
        ]);

        return redirect()->route('penjualan.index');
    }
 

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //MENAMPILKAN DATA DI TBS PENJUALAN
    public function edit(Request $request, Builder $htmlBuilder,$id)
    {   
        if ($request->ajax()) { 
            $session_id = session()->getId();
            $penjualan = Penjualan::find($id); 
            $tbs_penjualan = EditTbsPenjualan::with(['barang'])->where('no_faktur', $penjualan->no_faktur)->get(); 
            return Datatables::of($tbs_penjualan)->addColumn('action', function($tbspenjualan){
                    return view('penjualan._hapus_barang', [
                        'model'     => $tbspenjualan,
                        'form_url'  => route('penjualan.proses_hapus_edit_tbs_penjualan', $tbspenjualan->id_edit_tbs_penjualans),   
                        'confirm_message'   => 'Yakin Mau Menghapus Barang ?'
                        ]);
                })->addColumn('total_harga', function($barang){ 
                    $total_harga = number_format($barang->total_harga,0,',','.'); 
                    return $total_harga;
                })->make(true);
        }

        $html = $htmlBuilder 
        ->addColumn(['data' => 'barang.nama_barang', 'name' => 'barang.nama_barang', 'title' => 'Barang', 'orderable' => false, 'searchable'=>false ]) 
        ->addColumn(['data' => 'jumlah_barang', 'name' => 'jumlah_barang', 'title' => 'Jumlah'])
        ->addColumn(['data' => 'total_harga', 'name' => 'total_harga', 'title' => 'Total'])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Hapus', 'orderable' => false, 'searchable'=>false]);

        $session_id = session()->getId();
        $penjualan = Penjualan::find($id);

        $subtotal_kosong = 0; 

            $tbs_penjualan = EditTbsPenjualan::select('total_harga')->where('no_faktur', $penjualan->no_faktur);
            if ($tbs_penjualan->count() == 0 ) { 
              $subtotal = '0'; 
            } elseif ($tbs_penjualan->count() == 1) {
              $subtotal =  $subtotal_kosong += $tbs_penjualan->first()->total_harga;
            }else {
            $tbs_penjualans = EditTbsPenjualan::select('total_harga')->where('no_faktur', $penjualan->no_faktur)->get();
              foreach ($tbs_penjualans as $data_tbs) {
                 $subtotal_kosong += $data_tbs->total_harga;
              }
              $subtotal = $subtotal_kosong;
            }

        return view('penjualan.edit',['subtotal'=>$subtotal,'penjualan'=>$penjualan])->with(compact('html'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 
      $penjualan = Penjualan::find($id);
      EditTbsPenjualan::where('no_faktur',$penjualan->no_faktur)->delete(); 
      DetailPenjualan::where('no_faktur',$penjualan->no_faktur)->delete();
      Penjualan::destroy($id);

          $pesan_alert = 
               '<div class="container-fluid"> 
                    <b>Sukses : Berhasil Menghapus Produk</b>
                </div>';

            Session::flash("flash_notification", [
                "level"     => "success",
                "message"   => $pesan_alert
            ]);
        return back(); 
    }

    public function no_faktur(){
      //PROSES MEMBUAT NO. FAKTUR ITEM MASUK
        $tahun_sekarang = date('Y');
        $bulan_sekarang = date('m');
        $tahun_terakhir = substr($tahun_sekarang, 2);
      
      //mengecek jumlah karakter dari bulan sekarang
        $cek_jumlah_bulan = strlen($bulan_sekarang);

      //jika jumlah karakter dari bulannya sama dengan 1 maka di tambah 0 di depannya
        if ($cek_jumlah_bulan == 1) {
          $data_bulan_terakhir = "0".$bulan_sekarang;
         }
        else{
          $data_bulan_terakhir = $bulan_sekarang;
         }
      
      //ambil bulan dan no_faktur dari tanggal penjualan terakhir
         $penjualan = Penjualan::select([DB::raw('MONTH(created_at) bulan'), 'no_faktur'])->orderBy('id','DESC')->first();

         if ($penjualan != NULL) {
          $ambil_nomor = substr($penjualan->no_faktur, 0, -8);
          $bulan_akhir = $penjualan->bulan;
         }
         else{
          $ambil_nomor = 1;
          $bulan_akhir = 13;
         }
         
      /*jika bulan terakhir dari penjualan tidak sama dengan bulan sekarang, 
      maka nomor nya kembali mulai dari 1, jika tidak maka nomor terakhir ditambah dengan 1
      */
        if ($bulan_akhir != $bulan_sekarang) {
          $no_faktur = "1/IM/".$data_bulan_terakhir."/".$tahun_terakhir;
        }
        else {
          $nomor = 1 + $ambil_nomor ;
          $no_faktur = $nomor."/IM/".$data_bulan_terakhir."/".$tahun_terakhir;
        }

        return $no_faktur;
      //PROSES MEMBUAT NO. FAKTUR ITEM MASUK
    }


    public function proses_tambah_tbs_penjualan(Request $request)
    { 
        $this->validate($request, [
            'id_barang'     => 'required|numeric',
            'jumlah_barang' => 'required|numeric',
            ]);

        $session_id = session()->getId();

        $data_tbs = TbsPenjualan::select('id_barang')
        ->where('id_barang', $request->id_barang)
        ->where('session_id', $session_id)
        ->count();

        $data_barang = Barang::select('nama_barang')->where('id', $request->id_barang)->first();
        $pesan_alert = "Barang '".$data_barang->nama_barang."' Sudah Ada, Silakan Pilih Barang Lain !";


      //JIKA barang YG DIPILIH SUDAH ADA DI TBS
        if ($data_tbs > 0) {
            
            $pesan_alert = 
               '<div class="container-fluid"> 
                    <b>Barang "'.$data_barang->nama_barang.'" Sudah Ada, Silakan Pilih Barang Lain !</b>
                </div>';

            Session::flash("flash_notification", [
              "level"=>"warning",
              "message"=> $pesan_alert
            ]); 

            return back();
        }
        else{

           $pesan_alert = 
             '<div class="container-fluid"> 
                  <b>Berhasil Menambah Barang "'.$data_barang->nama_barang.'"</b>
              </div>';
            $jumlah = $request->jumlah_barang;
            $data = Barang::select('id','harga_barang')->where('id', $request->id_barang)->first();
            $total_harga = $jumlah *= $data->harga_barang;  
            $tbspenjualan = TbsPenjualan::create([
                'id_barang' =>$request->id_barang,            
                'session_id' => $session_id,
                'jumlah_barang' =>$request->jumlah_barang,
                'total_harga' =>$total_harga,
            ]);

            Session::flash("flash_notification", [
                "level"=>"success",
                "message"=> $pesan_alert
            ]);
            return back();

        }
    }

    public function proses_hapus_tbs_penjualan($id)
    { 
        if (!TbsPenjualan::destroy($id)) {
          $pesan_alert = 
               '<div class="container-fluid"> 
                    <b>Gagal Menghapus Barang</b>
                </div>';

            Session::flash("flash_notification", [
                "level"     => "danger",
                "message"   => $pesan_alert
            ]);
        return back();
        }
        else{
          $pesan_alert = 
               '<div class="container-fluid"> 
                    <b>Berhasil Menghapus Barang</b>
                </div>';

            Session::flash("flash_notification", [
                "level"     => "success",
                "message"   => $pesan_alert
            ]);
        return back();
        }
    }

    //PROSES BATAL Penjualan
    public function proses_hapus_semua_tbs_penjualan()
    { 
        $session_id = session()->getId();
        $data_tbs_penjualan = TbsPenjualan::where('session_id', $session_id)->delete(); 
        $pesan_alert = 
               '<div class="container-fluid"> 
                    <b>Berhasil Membatalkan Penjualan</b>
                </div>';

            Session::flash("flash_notification", [
                "level"     => "success",
                "message"   => $pesan_alert
            ]);
       return redirect()->back();
    }


    public function proses_form_edit($id)
    {
        //
        $session_id = session()->getId();
        $data_penjualan = Penjualan::find($id);  
        $data_barang_penjualan = DetailPenjualan::where('no_faktur', $data_penjualan->no_faktur);
        $hapus_semua_edit_tbs_penjualan = EditTbsPenjualan::where('no_faktur', $data_penjualan->no_faktur)->delete();
        
        foreach ($data_barang_penjualan->get() as $data_tbs) { 
            $detail_penjualan = EditTbsPenjualan::create([
                'id_barang' =>$data_tbs->id_barang,              
                'no_faktur' => $data_tbs->no_faktur,
                'jumlah_barang' =>$data_tbs->jumlah_barang,  
                'total_harga' =>$data_tbs->total_harga,          
                'session_id' => $session_id,
            ]);
        }

        return redirect()->route('penjualan.edit',$id);
    }

    //PROSES TAMBAH EDIT TBS ITEM MASUK
    public function proses_tambah_edit_tbs_penjualan(Request $request,$id)
    { 
        $this->validate($request, [
            'id_barang'     => 'required|max:11|numeric',
            'jumlah_barang' => 'required|max:8|numeric',
            ]);

        $data_penjualan = Penjualan::find($id);    
        $session_id = session()->getId();

        $data_tbs = EditTbsPenjualan::select('id_barang')
        ->where('id_barang', $request->id_barang)
        ->where('no_faktur', $data_penjualan->no_faktur) 
        ->count();

        $data_barang = Barang::select('nama_barang')->where('id', $request->id_barang)->first();
        $pesan_alert = "Produk '".$data_barang->nama_barang."' Sudah Ada, Silakan Pilih Produk Lain !";


      //JIKA PRODUK YG DIPILIH SUDAH ADA DI TBS
        if ($data_tbs > 0) {
            
            $pesan_alert = 
               '<div class="container-fluid"> 
                    <b>Produk "'.$data_barang->nama_barang.'" Sudah Ada, Silakan Pilih Produk Lain !</b>
                </div>';

            Session::flash("flash_notification", [
              "level"=>"warning",
              "message"=> $pesan_alert
            ]); 

            return back();
        }
        else{

           $pesan_alert = 
             '<div class="container-fluid"> 
                  <b>Berhasil Menambah Produk "'.$data_barang->nama_barang.'"</b>
              </div>';

            $jumlah = $request->jumlah_barang;
            $data = Barang::select('id','harga_barang')->where('id', $request->id_barang)->first();
            $total_harga = $jumlah *= $data->harga_barang;   

            $tbspenjualan = EditTbsPenjualan::create([
                'id_barang' =>$request->id_barang,    
                'no_faktur' =>$data_penjualan->no_faktur,                    
                'session_id' => $session_id,
                'jumlah_barang' =>$request->jumlah_barang,
                'total_harga' =>$total_harga,
            ]);

            Session::flash("flash_notification", [
                "level"=>"success",
                "message"=> $pesan_alert
            ]);
            return back();

        }
    }

    //PROSES HAPUS EDIT TBS PENJUALAN
    public function proses_hapus_edit_tbs_penjualan($id)
    { 
        if (!EditTbsPenjualan::destroy($id)) {
          $pesan_alert = 
               '<div class="container-fluid">
                    <div class="alert-icon">
                    <i class="material-icons">error</i>
                    </div>
                    <b>Gagal : Produk Sudah Terpakai Tidak Boleh Di Hapus</b>
                </div>';

            Session::flash("flash_notification", [
                "level"     => "danger",
                "message"   => $pesan_alert
            ]);
        return back();
        }
        else{
          $pesan_alert = 
               '<div class="container-fluid">
                    <div class="alert-icon">
                    <i class="material-icons">check</i>
                    </div>
                    <b>Sukses : Berhasil Menghapus Produk</b>
                </div>';

            Session::flash("flash_notification", [
                "level"     => "success",
                "message"   => $pesan_alert
            ]);
        return back();
        }
    }

    public function proses_edit_penjualan(Request $request, $id)
    {
        $data_penjualan = Penjualan::find($id);  
        $session_id = session()->getId();
        $user = Auth::user()->id; 

        $hapus_detail_tbs_penjualan = DetailPenjualan::where('no_faktur', $data_penjualan->no_faktur)->delete(); 

      //INSERT DETAIL ITEM MASUK
        $data_produk_penjualan = EditTbsPenjualan::where('no_faktur', $data_penjualan->no_faktur);

        if ($data_produk_penjualan->count() == 0) {

           $pesan_alert = 
               '<div class="container-fluid">
                    <div class="alert-icon">
                    <i class="material-icons">error</i>
                    </div>
                    <b>Gagal : Belum ada Produk Yang Di inputkan</b>
                </div>';

        Session::flash("flash_notification", [
            "level"     => "danger",
            "message"   => $pesan_alert
        ]);

          
          return redirect()->back();
        }
 
        foreach ($data_produk_penjualan->get() as $data_tbs) {
              $barang = Barang::find($data_tbs->id_barang);
              $barang->jumlah_barang -= $data_tbs->jumlah_barang;
              $barang->save();
              
            $detail_penjualan = DetailPenjualan::create([
                'id_barang' =>$data_tbs->id_barang,              
                'no_faktur' => $data_tbs->no_faktur,
                'jumlah_barang' =>$data_tbs->jumlah_barang,
                'total_harga' =>$data_tbs->total_harga,
            ]);
        }

      //INSERT ITEM MASUK
        if ($request->keterangan == "") {
          $keterangan = "-";
        }
        else{
          $keterangan = $request->keterangan;
        }

        $itemmasuk = Penjualan::find($id)->update([ 
            'keterangan' =>$keterangan, 
            'user_edit' => $user,
            'subtotal' => $request->subtotal, 
        ]);

        $hapus_edit_tbs_penjualan = EditTbsPenjualan::where('no_faktur', $data_penjualan->no_faktur)->delete(); 


        if (!$itemmasuk) {
          return back();
        }
         
        $pesan_alert = 
               '<div class="container-fluid">
                    <div class="alert-icon">
                    <i class="material-icons">check</i>
                    </div>
                    <b>Sukses : Berhasil Melakukan Edit Transaksi Item Masuk Faktur "'.$data_penjualan->no_faktur.'"</b>
                </div>';

        Session::flash("flash_notification", [
            "level"     => "success",
            "message"   => $pesan_alert
        ]);

        return redirect()->route('penjualan.index');
    }

    //PROSES BATAL EDIT ITEM MASUK
    public function proses_hapus_semua_edit_tbs_penjualan($id)
    {   
        //MENGAMBIL ID ITEM MASUK
        $data_penjualan = Penjualan::find($id); 
        //PROSES MENGHAPUS SEMUA EDTI TBS SESUAI NO FAKTUR YANG DI AMBIL 
        $data_tbs_penjualan = EditTbsPenjualan::where('no_faktur', $data_penjualan->no_faktur)->delete(); 
        $pesan_alert = 
               '<div class="container-fluid"> 
                    <b>Sukses : Berhasil Membatalkan Penjualan</b>
                </div>';

            Session::flash("flash_notification", [
                "level"     => "success",
                "message"   => $pesan_alert
            ]);
       return redirect()->route('penjualan.index');
    }
}
