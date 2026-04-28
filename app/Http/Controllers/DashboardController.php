<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalStudents = Student::count();
        $totalClasses = SchoolClass::count();
        $totalTeachers = Teacher::count();

        $monthlyRevenue = Payment::where('status', 'paid')
            ->whereYear('payment_date', now()->year)
            ->whereMonth('payment_date', now()->month)
            ->sum('paid_amount');

        $revenueByMonth = Payment::select(
            DB::raw('MONTH(payment_date) as month'),
            DB::raw('SUM(paid_amount) as total')
        )
            ->where('status', 'paid')
            ->whereYear('payment_date', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $chartLabels = $revenueByMonth->map(fn ($item) => 'Tháng ' . $item->month)->toArray();
        $chartData = $revenueByMonth->pluck('total')->toArray();

        $unpaidPayments = Payment::with('student')
            ->whereIn('status', ['unpaid', 'partial'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact(
            'totalStudents',
            'totalClasses',
            'totalTeachers',
            'monthlyRevenue',
            'chartLabels',
            'chartData',
            'unpaidPayments'
        ));
    }
}
