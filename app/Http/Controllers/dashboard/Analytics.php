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

    $assessments = AssessmentModel::with(['framework', 'details'])
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

    return view('content.dashboard.dashboards-analytics', compact(
      'arahan',
      'totalArahan',
      'totalDetail',
      'totalSelesai',
      'averageProgress',
      'assessments',
      'totalAssessments',
      'averageAssessmentScore',
      'totalAssessmentQuestions',
      'totalAssessmentFulfilled'
    ));
  }
}
