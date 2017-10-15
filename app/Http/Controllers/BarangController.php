<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Barang; 
use App\KategoriBarang; 
use App\SatuanBarang; 
use Auth; 
use Excel;
use Session;

class BarangController extends Controller
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
            $master_barang = Barang::with(['satuan_barang','kategori_barang']);
            return Datatables::of($master_barang)->addColumn('action', function($barang){
                    return view('datatable._action', [
                        'model'     => $barang,
                        'form_url'  => route('master-barang.destroy', $barang->id),
                        'edit_url'  => route('master-barang.edit', $barang->id),
                        'confirm_message'   => 'Yakin Mau Menghapus Barang ' . $barang->nama_barang . '?', 
                        ]);
                })->addColumn('harga_barang', function($barang){ 
                    $harga_barang = number_format($barang->harga_barang,0,',','.'); 
                    return $harga_barang;
                })->addColumn('kelontongan', function($barang){ 
                   if ($barang->kelontongan == 'kelontongan_biasa') {
                      $kelontongan = "Kelontongan Biasa";
                   }elseif ($barang->kelontongan == 'kelontongan_unik') {
                       $kelontongan = "Kelontongan Unik";
                   }elseif ($barang->kelontongan == '') {
                       $kelontongan = "-";
                   }
                    return $kelontongan;

                })->make(true);
        }
        $html = $htmlBuilder
        ->addColumn(['data' => 'nama_barang', 'name' => 'nama_barang', 'title' => 'Nama']) 
        ->addColumn(['data' => 'harga_barang', 'name' => 'harga_barang', 'title' => 'Harga'])  
        ->addColumn(['data' => 'jumlah_barang', 'name' => 'jumlah_barang', 'title' => 'Jumlah']) 
        ->addColumn(['data' => 'satuan_barang.nama_satuan_barang', 'name' => 'satuan_barang.nama_satuan_barang', 'title' => 'Satuan'])   
        ->addColumn(['data' => 'kategori_barang.nama_kategori_barang', 'name' => 'kategori_barang.nama_kategori_barang', 'title' => 'Kategori'])  
        ->addColumn(['data' => 'kelontongan', 'name' => 'kelontongan', 'title' => 'Kelontongan']) 
        ->addColumn(['data' => 'keterangan', 'name' => 'keterangan', 'title' => 'Keterangan']) 
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable'=>false]);

        $kategori_barang = KategoriBarang::all();
        $satuan_barang = SatuanBarang::all();
        return view('master_barang.index',['kategori_barang'=>$kategori_barang,'satuan_barang'=>$satuan_barang])->with(compact('html'));
    }

    public function filter_satuan_barang(Request $request, Builder $htmlBuilder,$id)
    {
        //
        if ($request->ajax()) {
            # code...
            $master_barang = Barang::with(['satuan_barang','kategori_barang'])->where('id_satuan_barang',$id);
            return Datatables::of($master_barang)->addColumn('action', function($barang){
                    return view('datatable._action', [
                        'model'     => $barang,
                        'form_url'  => route('master-barang.destroy', $barang->id),
                        'edit_url'  => route('master-barang.edit', $barang->id),
                        'confirm_message'   => 'Yakin Mau Menghapus Barang ' . $barang->nama_barang . '?', 
                        ]);
                })->addColumn('harga_barang', function($barang){ 
                    $harga_barang = number_format($barang->harga_barang,0,',','.'); 
                    return $harga_barang;
                })->addColumn('kelontongan', function($barang){ 
                   if ($barang->kelontongan == 'kelontongan_biasa') {
                      $kelontongan = "Kelontongan Biasa";
                   }elseif ($barang->kelontongan == 'kelontongan_unik') {
                       $kelontongan = "Kelontongan Unik";
                   }elseif ($barang->kelontongan == '') {
                       $kelontongan = "-";
                   }
                    return $kelontongan;

                })->make(true);
        }
        $html = $htmlBuilder
        ->addColumn(['data' => 'nama_barang', 'name' => 'nama_barang', 'title' => 'Nama']) 
        ->addColumn(['data' => 'harga_barang', 'name' => 'harga_barang', 'title' => 'Harga'])  
        ->addColumn(['data' => 'jumlah_barang', 'name' => 'jumlah_barang', 'title' => 'Jumlah']) 
        ->addColumn(['data' => 'satuan_barang.nama_satuan_barang', 'name' => 'satuan_barang.nama_satuan_barang', 'title' => 'Satuan'])   
        ->addColumn(['data' => 'kategori_barang.nama_kategori_barang', 'name' => 'kategori_barang.nama_kategori_barang', 'title' => 'Kategori'])  
        ->addColumn(['data' => 'kelontongan', 'name' => 'kelontongan', 'title' => 'Kelontongan']) 
        ->addColumn(['data' => 'keterangan', 'name' => 'keterangan', 'title' => 'Keterangan']) 
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable'=>false]);
        
        $kategori_barang = KategoriBarang::all();
        $satuan_barang = SatuanBarang::all();
        return view('master_barang.index',['kategori_barang'=>$kategori_barang,'satuan_barang'=>$satuan_barang])->with(compact('html'));
    }

    public function filter_kategori_barang(Request $request, Builder $htmlBuilder,$id)
    {
        //
        if ($request->ajax()) {
            # code...
            $master_barang = Barang::with(['satuan_barang','kategori_barang'])->where('id_kategori_barang',$id);
            return Datatables::of($master_barang)->addColumn('action', function($barang){
                    return view('datatable._action', [
                        'model'     => $barang,
                        'form_url'  => route('master-barang.destroy', $barang->id),
                        'edit_url'  => route('master-barang.edit', $barang->id),
                        'confirm_message'   => 'Yakin Mau Menghapus Barang ' . $barang->nama_barang . '?', 
                        ]);
                })->addColumn('harga_barang', function($barang){ 
                    $harga_barang = number_format($barang->harga_barang,0,',','.'); 
                    return $harga_barang;
                })->addColumn('kelontongan', function($barang){ 
                   if ($barang->kelontongan == 'kelontongan_biasa') {
                      $kelontongan = "Kelontongan Biasa";
                   }elseif ($barang->kelontongan == 'kelontongan_unik') {
                       $kelontongan = "Kelontongan Unik";
                   }elseif ($barang->kelontongan == '') {
                       $kelontongan = "-";
                   }
                    return $kelontongan;

                })->make(true);
        }
        $html = $htmlBuilder
        ->addColumn(['data' => 'nama_barang', 'name' => 'nama_barang', 'title' => 'Nama']) 
        ->addColumn(['data' => 'harga_barang', 'name' => 'harga_barang', 'title' => 'Harga'])  
        ->addColumn(['data' => 'jumlah_barang', 'name' => 'jumlah_barang', 'title' => 'Jumlah']) 
        ->addColumn(['data' => 'satuan_barang.nama_satuan_barang', 'name' => 'satuan_barang.nama_satuan_barang', 'title' => 'Satuan'])   
        ->addColumn(['data' => 'kategori_barang.nama_kategori_barang', 'name' => 'kategori_barang.nama_kategori_barang', 'title' => 'Kategori'])  
        ->addColumn(['data' => 'kelontongan', 'name' => 'kelontongan', 'title' => 'Kelontongan']) 
        ->addColumn(['data' => 'keterangan', 'name' => 'keterangan', 'title' => 'Keterangan']) 
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable'=>false]);
        
        $kategori_barang = KategoriBarang::all();
        $satuan_barang = SatuanBarang::all();
        return view('master_barang.index',['kategori_barang'=>$kategori_barang,'satuan_barang'=>$satuan_barang])->with(compact('html'));
    }

    public function filter_kelontongan_barang(Request $request, Builder $htmlBuilder,$id)
    {
        //
        if ($request->ajax()) {
            # code...
            if ($id == 1) {
                $kelontongan = 'kelontongan_biasa';
            }elseif ($id == 2) {
                $kelontongan = 'kelontongan_unik';
            }
            $master_barang = Barang::with(['satuan_barang','kategori_barang'])->where('kelontongan',$kelontongan);
            return Datatables::of($master_barang)->addColumn('action', function($barang){
                    return view('datatable._action', [
                        'model'     => $barang,
                        'form_url'  => route('master-barang.destroy', $barang->id),
                        'edit_url'  => route('master-barang.edit', $barang->id),
                        'confirm_message'   => 'Yakin Mau Menghapus Barang ' . $barang->nama_barang . '?', 
                        ]);
                })->addColumn('harga_barang', function($barang){ 
                    $harga_barang = number_format($barang->harga_barang,0,',','.'); 
                    return $harga_barang;
                })->addColumn('kelontongan', function($barang){ 
                   if ($barang->kelontongan == 'kelontongan_biasa') {
                      $kelontongan = "Kelontongan Biasa";
                   }elseif ($barang->kelontongan == 'kelontongan_unik') {
                       $kelontongan = "Kelontongan Unik";
                   }elseif ($barang->kelontongan == '') {
                       $kelontongan = "-";
                   }
                    return $kelontongan;

                })->make(true);
        }
        $html = $htmlBuilder
        ->addColumn(['data' => 'nama_barang', 'name' => 'nama_barang', 'title' => 'Nama']) 
        ->addColumn(['data' => 'harga_barang', 'name' => 'harga_barang', 'title' => 'Harga'])  
        ->addColumn(['data' => 'jumlah_barang', 'name' => 'jumlah_barang', 'title' => 'Jumlah']) 
        ->addColumn(['data' => 'satuan_barang.nama_satuan_barang', 'name' => 'satuan_barang.nama_satuan_barang', 'title' => 'Satuan'])   
        ->addColumn(['data' => 'kategori_barang.nama_kategori_barang', 'name' => 'kategori_barang.nama_kategori_barang', 'title' => 'Kategori'])  
        ->addColumn(['data' => 'kelontongan', 'name' => 'kelontongan', 'title' => 'Kelontongan']) 
        ->addColumn(['data' => 'keterangan', 'name' => 'keterangan', 'title' => 'Keterangan']) 
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable'=>false]);
        
        $kategori_barang = KategoriBarang::all();
        $satuan_barang = SatuanBarang::all();
        return view('master_barang.index',['kategori_barang'=>$kategori_barang,'satuan_barang'=>$satuan_barang])->with(compact('html'));
    }
 

    public function export_barang(Request $request, Builder $htmlBuilder) {  
            $this->validate($request, [
          
                'id_satuan'     => 'required',
                'id_kategori'    => 'required',
                'kelontongan'    => 'required'
                ]);  

            if ($request->id_satuan == 'semua' && $request->id_kategori == 'semua' && $request->kelontongan == 'semua') {
                
                $barangs = Barang::with(['satuan_barang','kategori_barang'])->get();
 
      

            }
             elseif ($request->id_satuan != 'semua' && $request->id_kategori != 'semua' && $request->kelontongan != 'semua') {

                 $barangs = Barang::with(['satuan_barang','kategori_barang'])->where('id_satuan',$request->id_satuan)->where('id_kategori',$request->id_kategori)->where('kelontongan',$request->kelontongan)->groupBy('id')->get();
                 
                
            }

            elseif ($request->id_satuan == 'semua' && $request->id_kategori != 'semua' && $request->kelontongan == 'semua' ) {

                 $barangs = Barang::with(['satuan_barang','kategori_barang'])->where('id_kategori_barang',$request->id_kategori)->get();
      

            }
            elseif ($request->id_satuan != 'semua' && $request->id_kategori == 'semua' && $request->kelontongan == 'semua') {

                 $barangs = Barang::with(['satuan_barang','kategori_barang'])->where('id_satuan_barang',$request->id_satuan)->groupBy('id')->get();

            } 
             elseif ($request->id_satuan == 'semua' && $request->id_kategori == 'semua' && $request->kelontongan != 'semua') {

                 $barangs = Barang::with(['satuan_barang','kategori_barang'])->where('kelontongan',$request->kelontongan)->groupBy('id')->get();

            } 
            elseif ($request->id_satuan == 'semua' && $request->id_kategori != 'semua' && $request->kelontongan != 'semua') {

                 $barangs = Barang::with(['satuan_barang','kategori_barang'])->where('id_kategori_barang',$request->id_kategori)->where('kelontongan',$request->kelontongan)->groupBy('id')->get();

            } elseif ($request->id_satuan != 'semua' && $request->id_kategori == 'semua' && $request->kelontongan != 'semua') {

                 $barangs = Barang::with(['satuan_barang','kategori_barang'])->where('id_satuan_barang',$request->id_satuan)->where('kelontongan',$request->kelontongan)->groupBy('id')->get();

            } elseif ($request->id_satuan != 'semua' && $request->id_kategori != 'semua' && $request->kelontongan == 'semua') {

                 $barangs = Barang::with(['satuan_barang','kategori_barang'])->where('id_satuan_barang',$request->id_satuan)->where('id_kategori_barang',$request->id_kategori)->groupBy('id')->get();

            } 



    Excel::create('Data Barang', function($excel) use ($barangs ) {
      // Set property        
      $excel->sheet('Data Barang', function($sheet) use ($barangs) {
        $row = 1;
        $sheet->row($row, [

          'Nama',
          'Harga',
          'Jumlah',
          'Satuan',
          'Kategori',
          'Kelontongan',
          'Keterangan'

        ]);

             
        foreach ($barangs as $barang) {
            if ($barang->kelontongan == 'kelontongan_biasa') {
                      $kelontongan = "Kelontongan Biasa";
            }elseif ($barang->kelontongan == 'kelontongan_unik') {
                       $kelontongan = "Kelontongan Unik";
            }


             $sheet->row(++$row, [
            $barang->nama_barang,
            $barang->harga_barang,
            $barang->jumlah_barang,
            $barang->satuan_barang->nama_satuan_barang,
            $barang->kategori_barang->nama_kategori_barang,
            $kelontongan,
            $barang->keterangan
            ]); 


      }
      
      });

    })->export('xls');

        return redirect()->route('master-barang.index');
    }
    public function create()
    {
        //
        return view('master_barang.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
         $this->validate($request, [
            'nama_barang'     => 'required|unique:barangs,nama_barang',
            'harga_barang'     => 'required',
            'id_satuan_barang'     => 'required|exists:satuan_barangs,id',
            'id_kategori_barang'     => 'required|exists:kategori_barangs,id',
            'jumlah_barang'     => 'required', 
            ]);

            if ($request->keterangan == "") {
              $keterangan = "-";
            }
            else{
              $keterangan = $request->keterangan;
            }

         $barang = Barang::create([
            'nama_barang' =>$request->nama_barang,
            'harga_barang' =>$request->harga_barang,
            'id_satuan_barang' =>$request->id_satuan_barang,
            'id_kategori_barang' =>$request->id_kategori_barang,
            'kelontongan' =>$request->kelontongan,
            'jumlah_barang' =>$request->jumlah_barang,
            'keterangan' =>$keterangan, 
            ]);
 
         Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>"Berhasil Menambah Barang $request->nama_barang"
            ]);
        return redirect()->route('master-barang.index');
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
    public function edit($id)
    {
        //
        $master_barang = Barang::find($id); 
        return view('master_barang.edit')->with(compact('master_barang'));
    }

    /**P
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         $this->validate($request, [
            'nama_barang'     => 'required|unique:barangs,nama_barang,'. $id,
            'harga_barang'     => 'required',
            'id_satuan_barang'     => 'required|exists:satuan_barangs,id',
            'id_kategori_barang'     => 'required|exists:kategori_barangs,id',
            'jumlah_barang'     => 'required', 
            ]);

            if ($request->keterangan == "") {
              $keterangan = "-";
            }
            else{
              $keterangan = $request->keterangan;
            }

        Barang::where('id', $id)->update([
            'nama_barang' =>$request->nama_barang,
            'harga_barang' =>$request->harga_barang,
            'id_satuan_barang' =>$request->id_satuan_barang,
            'id_kategori_barang' =>$request->id_kategori_barang,
            'kelontongan' =>$request->kelontongan,
            'jumlah_barang' =>$request->jumlah_barang,
            'keterangan' =>$keterangan, 
            ]);

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>"Berhasil Mengubah Barang $request->nama_barang"
            ]);
        return redirect()->route('master-barang.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { 
        if (!Barang::destroy($id)) {
            return redirect()->back();
        }
        else{
            Session::flash("flash_notification", [
                "level"     => "danger",
                "message"   => "Barang Berhasil Di Hapus"
            ]);
        return redirect()->route('master-barang.index');
        }

    }
}
