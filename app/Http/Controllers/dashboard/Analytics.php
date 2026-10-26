<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\ArahanModel;
use App\Models\AssessmentModel;

class Analytics extends Controller
{
  public function index()
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

    $totalArahan = $arahan->count();
    $totalDetail = $arahan->sum('dashboard_total');
    $totalSelesai = $arahan->sum('dashboard_selesai');
    $averageProgress = $totalDetail > 0
      ? round(($totalSelesai / $totalDetail) * 100, 2)
      : 0;

    $arahanStatusCounts = collect(['Selesai', 'Progress', 'Open', 'Belum Ada Detail'])
      ->mapWithKeys(fn (string $status) => [$status => $arahan->where('dashboard_status', $status)->count()]);
    $arahanProgress = $arahan->take(8)->map(fn (ArahanModel $item) => [
      'title' => $item->judul_arahan,
      'progress' => $item->dashboard_progress,
    ])->values();

    $assessments = AssessmentModel::with(['framework', 'details.kategori'])
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

    return view('content.dashboard.dashboards-analytics', compact(
      'arahan',
      'totalArahan',
      'totalDetail',
      'totalSelesai',
      'averageProgress',
      'arahanStatusCounts',
      'arahanProgress',
      'assessments',
      'totalAssessments',
      'averageAssessmentScore',
      'totalAssessmentQuestions',
      'totalAssessmentFulfilled',
      'fulfillmentCounts',
      'categoryScores',
      'assessmentTrend',
      'hasAssessmentTrend'
    ));
  }
}
