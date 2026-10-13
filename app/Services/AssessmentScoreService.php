<?php

namespace App\Services;

use App\Models\AssessmentModel;
use Illuminate\Support\Collection;

class AssessmentScoreService
{
    /** Calculate ((Ya + N/A) / jumlah pertanyaan) * skor maksimal. */
    public function summarize(Collection $details): Collection
    {
        return $details->groupBy('kategori_id')->map(function (Collection $categoryDetails) {
            $questionCount = $categoryDetails->count();
            $category = $categoryDetails->first()->kategori;
            $na = $categoryDetails->where('pemenuhan', 'N/A')->count();
            $yes = $categoryDetails->where('pemenuhan', 'Ya')->count();
            $maximumScore = (float) $category->bobot;

            return [
                'kategori' => $category,
                'jumlah_pertanyaan' => $questionCount,
                'skor_maksimal' => $maximumScore,
                'na' => $na,
                'tidak' => $categoryDetails->where('pemenuhan', 'Tidak')->count(),
                'ya' => $yes,
                'total_skor' => round(
                    $questionCount === 0 ? 0 : (($yes + $na) / $questionCount) * $maximumScore,
                    2
                ),
            ];
        })->sortBy(fn (array $summary) => $summary['kategori']->id)->values();
    }

    public function recalculate(AssessmentModel $assessment): float
    {
        $summary = $this->summarize($assessment->details()->with('kategori')->get());
        $score = round((float) $summary->sum('total_skor'), 2);
        $assessment->update(['skor' => $score]);

        return $score;
    }
}
