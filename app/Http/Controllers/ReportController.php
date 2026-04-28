<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $period = $request->input('period', 'month');
        $year = (int) $request->input('year', now()->year);
        $month = (int) $request->input('month', now()->month);
        $quarter = (int) $request->input('quarter', 1);

        $paymentBaseQuery = Payment::query();

        if ($period === 'month') {
            $paymentBaseQuery->whereYear('created_at', $year)->whereMonth('created_at', $month);
        } elseif ($period === 'quarter') {
            $startMonth = ($quarter - 1) * 3 + 1;
            $endMonth = $startMonth + 2;
            $paymentBaseQuery->whereYear('created_at', $year)->whereBetween(DB::raw('MONTH(created_at)'), [$startMonth, $endMonth]);
        } else {
            $paymentBaseQuery->whereYear('created_at', $year);
        }

        $revenueReport = (clone $paymentBaseQuery)
            ->selectRaw('SUM(amount) as expected_revenue, SUM(paid_amount) as collected_revenue')
            ->first();

        $feeStatusReport = (clone $paymentBaseQuery)
            ->selectRaw("status, COUNT(*) as total_records, SUM(amount - paid_amount) as outstanding")
            ->groupBy('status')
            ->get();

        $studentReport = Student::selectRaw('status, COUNT(*) as total')->groupBy('status')->get();

        $teacherPerformance = Teacher::withCount('classes')
            ->withSum('classes as total_tuition', 'tuition_fee')
            ->orderByDesc('classes_count')
            ->get();

        return view('reports.index', compact(
            'period',
            'year',
            'month',
            'quarter',
            'revenueReport',
            'feeStatusReport',
            'studentReport',
            'teacherPerformance'
        ));
    }
}
