<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Barang; 
use Auth; 
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
                })->make(true);
        }
        $html = $htmlBuilder
        ->addColumn(['data' => 'nama_barang', 'name' => 'nama_barang', 'title' => 'Nama']) 
        ->addColumn(['data' => 'harga_barang', 'name' => 'harga_barang', 'title' => 'Nama Barang'])  
        ->addColumn(['data' => 'kelontongan', 'name' => 'kelontongan', 'title' => 'Kelontongan']) 
        ->addColumn(['data' => 'jumlah_barang', 'name' => 'jumlah_barang', 'title' => 'Jumlah']) 
        ->addColumn(['data' => 'keterangan', 'name' => 'keterangan', 'title' => 'Keterangan']) 
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable'=>false]);

        return view('master_barang.index')->with(compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
            'kelontongan'     => 'required',
            'jumlah_barang'     => 'required',
            'keterangan'     => 'required',
            ]);


         $barang = Barang::create([
            'nama_barang' =>$request->nama_barang,
            'harga_barang' =>$request->harga_barang,
            'id_satuan_barang' =>$request->id_satuan_barang,
            'id_kategori_barang' =>$request->id_kategori_barang,
            'kelontongan' =>$request->kelontongan,
            'jumlah_barang' =>$request->jumlah_barang,
            'keterangan' =>$request->keterangan, 
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
    }
}
