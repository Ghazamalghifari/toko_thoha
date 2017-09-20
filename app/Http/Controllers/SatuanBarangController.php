<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\SatuanBarang; 
use App\Barang; 
use Auth; 
use Session;

class SatuanBarangController extends Controller
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
            $master_satuan_barang = SatuanBarang::select(['id','nama_satuan_barang']);
            return Datatables::of($master_satuan_barang)->addColumn('action', function($satuan_barang){
                    return view('datatable._action', [
                        'model'     => $satuan_barang,
                        'form_url'  => route('master-satuan-barang.destroy', $satuan_barang->id),
                        'edit_url'  => route('master-satuan-barang.edit', $satuan_barang->id),
                        'confirm_message'   => 'Yakin Mau Menghapus Satuan Barang ' . $satuan_barang->nama_satuan_barang . '?', 
                        ]);
                })->make(true);
        }
        $html = $htmlBuilder
        ->addColumn(['data' => 'nama_satuan_barang', 'name' => 'nama_satuan_barang', 'title' => 'Nama Satuan Barang']) 
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable'=>false]);

        return view('master_satuan_barang.index')->with(compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('master_satuan_barang.create');
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
            'nama_satuan_barang'     => 'required|unique:satuan_barangs,nama_satuan_barang,'
            ]);

         $satuan = SatuanBarang::create([
            'nama_satuan_barang' =>$request->nama_satuan_barang
            ]);

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>"Berhasil Menambah Satuan Barang $request->nama_satuan_barang"
            ]);
        return redirect()->route('master-satuan-barang.index');
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
        $master_satuan_barang = SatuanBarang::find($id);
        return view('master_satuan_barang.edit')->with(compact('master_satuan_barang')); 
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
         $this->validate($request, [
            'nama_satuan_barang'     => 'required|unique:satuan_barangs,nama_satuan_barang,'. $id,
            ]);

        SatuanBarang::where('id', $id)->update([
            'nama_satuan_barang' =>$request->nama_satuan_barang
            ]);

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>"Berhasil Mengubah Satuan Barang $request->nama_satuan_barang"
            ]);

        return redirect()->route('master-satuan-barang.index');
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
        $barang = Barang::where('id_satuan_barang',$id); 
 
        if ($barang->count() > 0) {
        // menyiapkan pesan error
        Session:: flash("flash_notification", [
            "level"=>"danger",
            "message"=>"Satuan Barang Tidak Bisa Di Hapus Karena Masih Memiliki Barang"
            ]);

        return redirect()->route('master-satuan-barang.index');  
        }  
        else{

        SatuanBarang::destroy($id);
        Session:: flash("flash_notification", [
            "level"=>"danger",
            "message"=>"Barang Berhasil Di Hapus"
            ]);
        return redirect()->route('master-satuan-barang.index');
        }
    }
}
