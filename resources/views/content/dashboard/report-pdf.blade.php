<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Dashboard GCG {{ now()->format('Y-m-d') }}</title>
    <style>
        @page { margin: 20px 24px; }
        body { color: #2f3b4c; font-family: DejaVu Sans, sans-serif; font-size: 9px; }
        h1 { color: #3f51b5; font-size: 18px; margin: 0 0 4px; }
        h2 { border-bottom: 1px solid #d9dee3; font-size: 12px; margin: 20px 0 8px; padding-bottom: 5px; }
        p { margin: 0 0 5px; }
        table { border-collapse: collapse; width: 100%; }
        th { background: #f5f5f9; color: #566a7f; font-weight: bold; text-align: left; }
        th, td { border: 1px solid #d9dee3; padding: 6px; vertical-align: top; }
        .metrics td { width: 25%; }
        .metric-label { color: #697a8d; display: block; font-size: 8px; }
        .metric-value { color: #3f51b5; display: block; font-size: 14px; font-weight: bold; margin-top: 3px; }
        .muted { color: #697a8d; }
        .empty { color: #697a8d; font-style: italic; text-align: center; }
        .actions { margin-bottom: 16px; text-align: right; }
        .print-button { background: #dc3545; border: 0; border-radius: 4px; color: #fff; cursor: pointer; padding: 8px 14px; }
        @media print { .actions { display: none; } }
    </style>
</head>
<body>
    <div class="actions">
        <button type="button" class="print-button" onclick="window.print()">Simpan sebagai PDF</button>
    </div>
    <h1>Laporan Dashboard GCG</h1>
    <p class="muted">Diekspor pada {{ $dashboardExportData['generatedAt'] }}</p>

    <h2>Ringkasan</h2>
    <table class="metrics">
        <tr>
            @foreach($dashboardExportData['summary'] as $label => $value)
                <td>
                    <span class="metric-label">{{ $label }}</span>
                    <span class="metric-value">{{ is_numeric($value) ? number_format((float) $value, 2, ',', '.') : $value }}</span>
                </td>
                @if($loop->iteration % 4 === 0 && !$loop->last)
                    </tr><tr>
                @endif
            @endforeach
        </tr>
    </table>

    <h2>Ringkasan Arahan</h2>
    <table>
        <thead>
            <tr><th>Judul</th><th>Tanggal</th><th>Progress</th><th>Status</th><th>Selesai</th><th>Total</th></tr>
        </thead>
        <tbody>
            @forelse($arahan as $item)
                <tr>
                    <td>{{ $item->judul_arahan }}</td>
                    <td>{{ $item->tanggal_arahan?->format('d/m/Y') }}</td>
                    <td>{{ number_format($item->dashboard_progress, 2, ',', '.') }}%</td>
                    <td>{{ $item->dashboard_status }}</td>
                    <td>{{ $item->dashboard_selesai }}</td>
                    <td>{{ $item->dashboard_total }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty">Belum ada data Arahan.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Ringkasan Assessment</h2>
    <table>
        <thead>
            <tr><th>Framework</th><th>Tahun Assessment</th><th>Tahun Buku</th><th>Jenis</th><th>Progress</th><th>Status</th><th>Skor</th></tr>
        </thead>
        <tbody>
            @forelse($assessments as $item)
                <tr>
                    <td>{{ $item->framework?->nama_framework ?? 'Framework tidak tersedia' }}</td>
                    <td>{{ $item->tahun_asesmen }}</td>
                    <td>{{ $item->tahun_buku }}</td>
                    <td>{{ $item->jenis_asesmen }}</td>
                    <td>{{ number_format($item->dashboard_progress, 2, ',', '.') }}%</td>
                    <td>{{ $item->dashboard_status }}</td>
                    <td>{{ number_format((float) $item->skor, 2, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="empty">Belum ada data Assessment.</td></tr>
            @endforelse
        </tbody>
    </table>
    <script>
        window.addEventListener('load', function () {
            window.setTimeout(function () {
                window.print();
            }, 250);
        });
    </script>
</body>
</html>
