<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArahanModel;

class ArahanController extends Controller
{
    public function index()
    {
        $data = ArahanModel::all();

        return view(
            'content.arahan.index',
            compact('data')
        );
    }

    public function create()
    {
        return view(
            'content.arahan.create'
        );
    }

    public function store(Request $request)
    {
        ArahanModel::create([

            'judul_arahan' => $request->judul_arahan,

            'tanggal_arahan' => $request->tanggal_arahan,

            'progress' => 0,

        ]);

        return redirect()->route(
            'arahan.index'
        );
    }

    public function edit($id)
    {
        $data = ArahanModel::findOrFail($id);

        return view(
            'content.arahan.edit',
            compact('data')
        );
    }

    public function update(Request $request, $id)
    {
        $data = ArahanModel::findOrFail($id);

        $data->update([

            'judul_arahan' => $request->judul_arahan,

            'tanggal_arahan' => $request->tanggal_arahan,

        ]);

        return redirect()->route(
            'arahan.index'
        );
    }

    public function destroy($id)
    {
        ArahanModel::destroy($id);

        return redirect()->route(
            'arahan.index'
        );
    }
}