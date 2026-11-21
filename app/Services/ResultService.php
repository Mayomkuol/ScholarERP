<?php

namespace App\Services;

use App\Models\ExamMark;
use App\Models\Grade;
use App\Models\StudentResult;

class ResultService
{
    /**
     * Generate overall results for an exam.
     */
    public function generate(int $examId): void
    {
        $studentIds = ExamMark::where('exam_id', $examId)
            ->distinct()
            ->pluck('student_id');

        foreach ($studentIds as $studentId) {

            $marks = ExamMark::where('exam_id', $examId)
                ->where('student_id', $studentId)
                ->get();

            if ($marks->isEmpty()) {
                continue;
            }

            $total = $marks->sum('marks');

            $meanScore = round($marks->avg('marks'), 2);

            $meanPoints = round($marks->avg('points'), 2);

            $grade = Grade::where('min_mark', '<=', $meanScore)
                ->where('max_mark', '>=', $meanScore)
                ->first();

            StudentResult::updateOrCreate(

                [
                    'exam_id' => $examId,
                    'student_id' => $studentId,
                ],

                [
                    'total_marks' => $total,
                    'mean_score' => $meanScore,
                    'mean_grade' => $grade?->grade,
                    'points' => $meanPoints,
                ]
            );
        }

        $this->calculatePosition($examId);
    }

    /**
     * Calculate class positions.
     * Equal totals receive the same rank.
     */
    private function calculatePosition(int $examId): void
    {
        $results = StudentResult::where('exam_id', $examId)
            ->orderByDesc('total_marks')
            ->orderByDesc('mean_score')
            ->get();

        $position = 1;
        $lastTotal = null;
        $lastPosition = 1;

        foreach ($results as $index => $result) {

            if ($lastTotal !== null && $result->total_marks == $lastTotal) {

                $result->position = $lastPosition;

            } else {

                $position = $index + 1;

                $result->position = $position;

                $lastPosition = $position;

                $lastTotal = $result->total_marks;
            }

            $result->save();
        }
    }
}