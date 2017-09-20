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
        ->addColumn(['data' => 'satuan_barang.nama_satuan_barang', 'name' => 'satuan_barang.nama_satuan_barang', 'title' => 'Satuan']) 
        ->addColumn(['data' => 'kategori_barang.nama_kategori_barang', 'name' => 'kategori_barang.nama_kategori_barang', 'title' => 'Kategori']) 
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
        //
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
