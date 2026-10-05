<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ArahanModel;
use App\Models\ArahanDetailModel;
use Illuminate\Support\Facades\File;

class ArahanDetailController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST DETAIL
    |--------------------------------------------------------------------------
    */

    public function index($arahan_id)
    {
        $arahan = ArahanModel::findOrFail($arahan_id);

        $data = ArahanDetailModel::where(
            'arahan_id',
            $arahan_id
        )->get();

        return view(
            'content.arahan_detail.index',
            compact(
                'arahan',
                'data'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FORM CREATE
    |--------------------------------------------------------------------------
    */

    public function create($arahan_id)
    {
        $arahan = ArahanModel::findOrFail($arahan_id);

        return view(
            'content.arahan_detail.create',
            compact('arahan')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        ArahanDetailModel::create([

            'arahan_id' => $request->arahan_id,

            'aspek' => $request->aspek,

            'arahan' => $request->arahan,

            'tindak_lanjut' => $request->tindak_lanjut,

            'status' => $request->status,

        ]);

        return redirect()->route(
            'arahan_detail.index',
            $request->arahan_id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FORM EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $data = ArahanDetailModel::findOrFail($id);

        return view(
            'content.arahan_detail.edit',
            compact('data')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $data = ArahanDetailModel::findOrFail($id);

        $fileName = $data->eviden;

        if ($request->hasFile('eviden')) {

            if (
                $fileName &&
                file_exists(
                    public_path(
                        'uploads/arahan/' .
                        $fileName
                    )
                )
            ) {

                unlink(
                    public_path(
                        'uploads/arahan/' .
                        $fileName
                    )
                );
            }

            $file = $request->file('eviden');

            $fileName = time() . '_' . $file->getClientOriginalName(); 

            $file->move(
                public_path('uploads/arahan/'),
                $fileName
            );
        }

        $data->update([

            'aspek' => $request->aspek,

            'arahan' => $request->arahan,

            'tindak_lanjut' =>
                $request->tindak_lanjut,

            'status' =>
                $request->status,

            'eviden' =>
                $fileName

        ]);

        /*
        |--------------------------------------------------------------------------
        | UPDATE PROGRESS
        |--------------------------------------------------------------------------
        */

                $total = ArahanDetailModel::where(
                'arahan_id',
                $data->arahan_id
            )->count();

            $done = ArahanDetailModel::where(
                'arahan_id',
                $data->arahan_id
            )
            ->whereIn('status', [
                'Selesai',
                'Selesai Berkelanjutan'
            ])
            ->count();

            $progress = 0;

            if ($total > 0) {

                $progress = round(
                    ($done / $total) * 100,
                    2
                );
            }

            ArahanModel::where(
                'id',
                $data->arahan_id
            )->update([
                'progress' => $progress
            ]);

            return redirect()->route(
                'arahan_detail.index',
                $data->arahan_id
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $data = ArahanDetailModel::findOrFail($id);

        if (
            $data->eviden &&
            file_exists(
                public_path(
                    'uploads/arahan/' .
                    $data->eviden
                )
            )
        ) {
            unlink(
                public_path(
                    'uploads/arahan/' .
                    $data->eviden
                )
            );
        }

        $arahan_id = $data->arahan_id;

        $data->delete();

        return redirect()->route(
            'arahan_detail.index',
            $arahan_id
        );
    }
}