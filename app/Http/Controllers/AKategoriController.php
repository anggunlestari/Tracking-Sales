<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use DataTables;

class AKategoriController extends Controller
{
    public function index()
    {
        return view('admin.kategori.table');
    }

    public function indexData()
    {
        $kategori = Kategori::latest()->get();

        return DataTables::of($kategori)
            ->addColumn('actions', function ($data) {
                return  '
                <a href="/admin/kategori/' . $data->id . '/edit" class="d-inline">
                    <button class="btn btn-warning btn-sm"> <i class="far fa-edit text-white"></i></button>
                </a>
                <form action="/admin/kategori/' . $data->id . '" method="post" class="d-inline">
                    <input type="hidden" name="_method" value="delete">   
                    ' . @csrf_field() . '
                    <button type="submit" class="hapus btn btn-danger btn-sm"><i
                            class="far fa-trash-alt text-white"></i></button>
                </form>';
            })
            ->rawColumns(['actions'])
            ->toJson();
    }

    public function create()
    {
        return view('admin.kategori.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required',
            'deskripsi' => 'required',
        ]);

        Kategori::create($request->all());
        Alert::toast('Data Kategori Berhasil Di Tambahkan', 'success');

        return redirect('/admin/kategori/table');
    }

    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama_kategori' => 'required',
            'deskripsi' => 'required'
        ]);

        Kategori::where('id', $kategori->id)
            ->update([
                'nama_kategori' => $request->nama_kategori,
                'deskripsi' => $request->deskripsi
            ]);

        Alert::toast('Data Kategori Berhasil Di Edit', 'success');

        return redirect('/admin/kategori/table');
    }

    public function destroy(Kategori $kategori)
    {
        Kategori::destroy($kategori->id);

        Alert::toast('Data Kategori Berhasil Di Hapus', 'success');

        return redirect('/admin/kategori/table');
    }

    public function exportExcel()
    {
        $data = array(
            'kategori' => Kategori::latest()->get(),
        );

        return view('admin.kategori.exportExcel', $data);
    }

    public function exportPdf()
    {
        $data = array(
            'kategori' => Kategori::latest()->get(),
        );

        return view('admin.kategori.exportPdf', $data);
    }
}
