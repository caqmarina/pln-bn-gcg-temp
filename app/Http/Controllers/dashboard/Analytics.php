<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\ArahanModel;
use App\Models\AssessmentModel;
use App\Models\MasterFrameworkModel;
use Illuminate\Http\Request;

class Analytics extends Controller
{
  private function dashboardData(Request $request): array
  {
    $arahan = ArahanModel::with('details')
      ->latest('tanggal_arahan')
      ->latest('id')
      ->get()
      ->map(function (ArahanModel $item) {
        $total = $item->details->count();
        $selesai = $item->details
          ->whereIn('status', ['Selesai', 'Selesai Berkelanjutan'])
          ->count();
        $progress = $total > 0 ? round(($selesai / $total) * 100, 2) : 0;

        $status = match (true) {
          $total === 0 => 'Belum Ada Detail',
          $selesai === $total => 'Selesai',
          $selesai > 0 => 'Progress',
          default => 'Open',
        };

        $item->dashboard_total = $total;
        $item->dashboard_selesai = $selesai;
        $item->dashboard_progress = $progress;
        $item->dashboard_status = $status;

        return $item;
      });

    $arahanStatuses = ['Selesai', 'Progress', 'Open', 'Belum Ada Detail'];
    $selectedArahanStatus = (string) $request->input('arahan_status', '');
    if (!in_array($selectedArahanStatus, $arahanStatuses, true)) {
      $selectedArahanStatus = '';
    }
    if ($selectedArahanStatus !== '') {
      $arahan = $arahan->where('dashboard_status', $selectedArahanStatus)->values();
    }

    $totalArahan = $arahan->count();
    $totalDetail = $arahan->sum('dashboard_total');
    $totalSelesai = $arahan->sum('dashboard_selesai');
    $averageProgress = $totalDetail > 0
      ? round(($totalSelesai / $totalDetail) * 100, 2)
      : 0;

    $arahanStatusCounts = collect($arahanStatuses)
      ->mapWithKeys(fn (string $status) => [$status => $arahan->where('dashboard_status', $status)->count()]);
    $arahanProgress = $arahan->take(8)->map(fn (ArahanModel $item) => [
      'title' => $item->judul_arahan,
      'progress' => $item->dashboard_progress,
    ])->values();

    $assessmentTypes = ['Self Assessment', 'Asesor'];
    $assessmentYears = AssessmentModel::query()
      ->whereNotNull('tahun_asesmen')
      ->distinct()
      ->orderByDesc('tahun_asesmen')
      ->pluck('tahun_asesmen')
      ->values();
    $assessmentBookYears = AssessmentModel::query()
      ->whereNotNull('tahun_buku')
      ->distinct()
      ->orderByDesc('tahun_buku')
      ->pluck('tahun_buku')
      ->values();
    $assessmentFrameworks = MasterFrameworkModel::query()
      ->orderBy('nama_framework')
      ->get(['id', 'nama_framework']);

    $selectedAssessmentBookYear = (string) $request->input('assessment_book_year', '');
    if (!in_array($selectedAssessmentBookYear, $assessmentBookYears->map(fn ($year) => (string) $year)->all(), true)) {
      $selectedAssessmentBookYear = '';
    }

    $selectedAssessmentYear = (string) $request->input('assessment_year', '');
    if (!in_array($selectedAssessmentYear, $assessmentYears->map(fn ($year) => (string) $year)->all(), true)) {
      $selectedAssessmentYear = '';
    }

    $selectedAssessmentType = (string) $request->input('assessment_type', '');
    if (!in_array($selectedAssessmentType, $assessmentTypes, true)) {
      $selectedAssessmentType = '';
    }

    $selectedAssessmentFramework = (string) $request->input('assessment_framework', '');
    if (!ctype_digit($selectedAssessmentFramework)) {
      $selectedAssessmentFramework = '';
    }

    $assessmentQuery = AssessmentModel::with(['framework', 'details.kategori']);
    if ($selectedAssessmentBookYear !== '') {
      $assessmentQuery->where('tahun_buku', $selectedAssessmentBookYear);
    }
    if ($selectedAssessmentYear !== '') {
      $assessmentQuery->where('tahun_asesmen', $selectedAssessmentYear);
    }
    if ($selectedAssessmentType !== '') {
      $assessmentQuery->where('jenis_asesmen', $selectedAssessmentType);
    }
    if ($selectedAssessmentFramework !== '') {
      $assessmentQuery->where('framework_id', (int) $selectedAssessmentFramework);
    }

    $assessments = $assessmentQuery
      ->latest('tahun_buku')
      ->latest('id')
      ->get()
      ->map(function (AssessmentModel $item) {
        $total = $item->details->count();
        $ya = $item->details->where('pemenuhan', 'Ya')->count();
        $na = $item->details->where('pemenuhan', 'N/A')->count();
        $terpenuhi = $ya + $na;
        $progress = $total > 0 ? round(($terpenuhi / $total) * 100, 2) : 0;

        $status = match (true) {
          $total === 0 => 'Belum Ada Detail',
          $terpenuhi === $total => 'Selesai',
          $terpenuhi > 0 => 'Progress',
          default => 'Open',
        };

        $item->dashboard_total = $total;
        $item->dashboard_ya = $ya;
        $item->dashboard_na = $na;
        $item->dashboard_progress = $progress;
        $item->dashboard_status = $status;

        return $item;
      });

    $totalAssessments = $assessments->count();
    $averageAssessmentScore = $totalAssessments > 0
      ? round((float) $assessments->avg('skor'), 2)
      : 0;
    $totalAssessmentQuestions = $assessments->sum('dashboard_total');
    $totalAssessmentFulfilled = $assessments->sum('dashboard_ya') + $assessments->sum('dashboard_na');

    $assessmentDetails = $assessments->flatMap(fn (AssessmentModel $assessment) => $assessment->details);
    $fulfillmentCounts = [
      'Ya' => $assessmentDetails->where('pemenuhan', 'Ya')->count(),
      'Tidak' => $assessmentDetails->where('pemenuhan', 'Tidak')->count(),
      'N/A' => $assessmentDetails->where('pemenuhan', 'N/A')->count(),
    ];

    // A category can occur in more than one assessment. Average its calculated
    // score so the chart remains comparable instead of increasing with volume.
    $categoryScores = $assessments
      ->flatMap(function (AssessmentModel $assessment) {
        return $assessment->details
          ->groupBy('kategori_id')
          ->map(function ($details) {
            $category = $details->first()->kategori;
            $total = $details->count();
            $fulfilled = $details->whereIn('pemenuhan', ['Ya', 'N/A'])->count();

            return [
              'id' => $category?->id,
              'name' => $category?->nama_kategori ?? 'Kategori tidak tersedia',
              'score' => $total > 0 ? round(($fulfilled / $total) * (float) ($category?->bobot ?? 0), 2) : 0,
              'maximum' => (float) ($category?->bobot ?? 0),
            ];
          })
          ->values();
      })
      ->groupBy('id')
      ->map(function ($categories) {
        $first = $categories->first();

        return [
          'name' => $first['name'],
          'score' => round($categories->avg('score'), 2),
          'maximum' => $first['maximum'],
        ];
      })
      ->values();

    $assessmentTrend = $assessments
      ->groupBy('tahun_asesmen')
      ->sortKeys()
      ->map(fn ($items, $year) => [
        'year' => (string) $year,
        'score' => round((float) $items->avg('skor'), 2),
      ])
      ->values();
    $hasAssessmentTrend = $assessmentTrend->count() > 1;

    $dashboardExportData = [
      'generatedAt' => now()->format('d/m/Y H:i'),
      'summary' => [
        'Total Arahan' => $totalArahan,
        'Total Detail Arahan' => $totalDetail,
        'Detail Arahan Selesai' => $totalSelesai,
        'Progress Arahan (%)' => $averageProgress,
        'Total Assessment' => $totalAssessments,
        'Rata-rata Skor Assessment' => $averageAssessmentScore,
        'Total Pertanyaan Assessment' => $totalAssessmentQuestions,
        'Pertanyaan Terpenuhi' => $totalAssessmentFulfilled,
      ],
      'arahan' => $arahan->map(fn (ArahanModel $item) => [
        'Judul' => $item->judul_arahan,
        'Tanggal' => $item->tanggal_arahan?->format('d/m/Y'),
        'Progress (%)' => $item->dashboard_progress,
        'Status' => $item->dashboard_status,
        'Selesai' => $item->dashboard_selesai,
        'Total' => $item->dashboard_total,
      ])->values(),
      'assessments' => $assessments->map(fn (AssessmentModel $item) => [
        'Framework' => $item->framework?->nama_framework ?? 'Framework tidak tersedia',
        'Tahun Assessment' => $item->tahun_asesmen,
        'Tahun Buku' => $item->tahun_buku,
        'Jenis' => $item->jenis_asesmen,
        'Progress (%)' => $item->dashboard_progress,
        'Status' => $item->dashboard_status,
        'Skor' => $item->skor,
      ])->values(),
      'fulfillment' => $fulfillmentCounts,
      'categoryScores' => $categoryScores,
      'assessmentTrend' => $assessmentTrend,
    ];

    return compact(
      'arahan',
      'totalArahan',
      'totalDetail',
      'totalSelesai',
      'averageProgress',
      'arahanStatusCounts',
      'arahanProgress',
      'arahanStatuses',
      'selectedArahanStatus',
      'assessmentTypes',
      'assessmentYears',
      'assessmentBookYears',
      'assessmentFrameworks',
      'selectedAssessmentBookYear',
      'selectedAssessmentYear',
      'selectedAssessmentType',
      'selectedAssessmentFramework',
      'assessments',
      'totalAssessments',
      'averageAssessmentScore',
      'totalAssessmentQuestions',
      'totalAssessmentFulfilled',
      'fulfillmentCounts',
      'categoryScores',
      'assessmentTrend',
      'hasAssessmentTrend',
      'dashboardExportData'
    );
  }

  public function index(Request $request)
  {
    return view('content.dashboard.dashboards-analytics', $this->dashboardData($request));
  }

  public function exportPdf(Request $request)
  {
    return view('content.dashboard.report-pdf', $this->dashboardData($request));
  }
}
