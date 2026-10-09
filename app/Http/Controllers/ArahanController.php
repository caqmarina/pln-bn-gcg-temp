<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArahanModel;
use App\Models\ArahanDetailModel;
use Illuminate\Support\Facades\DB;

class ArahanController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $data = ArahanModel::latest()->get();

        return view(
            'content.arahan.index',
            compact('data', 'search')
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

    /**
     * Save recommendation rows extracted in the browser from the ACGS workbook.
     * The workbook is read client-side so uploaded assessment material is not
     * retained on the server as a separate file.
     */
    public function import(Request $request)
    {
        $validated = $request->validate([
            'judul_arahan' => ['required', 'string', 'max:255'],
            'tanggal_arahan' => ['required', 'date'],
            // The spreadsheet is parsed in the browser. Use the filename
            // extension here because this PHP installation does not include
            // the fileinfo extension required for MIME detection.
            'file' => ['required', 'file', 'extensions:xlsx,xls', 'max:10240'],
            'rows' => ['required', 'string'],
        ]);

        $rows = json_decode($validated['rows'], true);

        if (!is_array($rows) || count($rows) === 0 || count($rows) > 1000) {
            return back()->withErrors(['import' => 'File tidak memiliki baris pertanyaan yang dapat diimpor.'])->withInput();
        }

        $details = [];
        foreach ($rows as $row) {
            if (!is_array($row) || empty(trim((string) ($row['arahan'] ?? '')))) {
                continue;
            }

            $details[] = [
                'aspek' => mb_substr(trim((string) ($row['aspek'] ?? 'Tanpa kode')), 0, 255),
                'source_level' => in_array($row['level'] ?? null, ['Level 1', 'Level 2'], true)
                    ? $row['level']
                    : null,
                'source_section' => mb_substr(trim((string) ($row['section'] ?? '')), 0, 40) ?: null,
                'arahan' => trim((string) ($row['arahan'] ?? '')),
                'tindak_lanjut' => trim((string) ($row['rekomendasi'] ?? '')),
                'status' => strtoupper(trim((string) ($row['status'] ?? ''))) === 'YES' ? 'Done' : 'Open',
            ];
        }

        if ($details === []) {
            return back()->withErrors(['import' => 'File tidak memiliki baris pertanyaan yang dapat diimpor.'])->withInput();
        }

        $imported = DB::transaction(function () use ($validated, $details) {
            $arahan = ArahanModel::create([
                'judul_arahan' => $validated['judul_arahan'],
                'tanggal_arahan' => $validated['tanggal_arahan'],
                'progress' => 0,
            ]);

            $records = array_map(function ($detail) use ($arahan) {
                return [
                    'arahan_id' => $arahan->id,
                    ...$detail,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $details);

            ArahanDetailModel::insert($records);

            return count($records);
        });

        return redirect()->route('arahan.index')->with('success', "Berhasil mengimpor {$imported} detail arahan.");
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
