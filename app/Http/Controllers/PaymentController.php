<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();

        $payments = Payment::with(['student', 'schoolClass'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $studentIds = $payments->getCollection()->pluck('student_id')->unique()->filter()->values();

        $debtByStudent = [];

        if ($studentIds->isNotEmpty()) {
            $totalTuitionByStudent = DB::table('class_student')
                ->join('classes', 'classes.id', '=', 'class_student.class_id')
                ->selectRaw('class_student.student_id, COALESCE(SUM(classes.tuition_fee), 0) as total_tuition')
                ->whereIn('class_student.student_id', $studentIds)
                ->groupBy('class_student.student_id')
                ->pluck('total_tuition', 'class_student.student_id');

            $totalPaidByStudent = Payment::query()
                ->selectRaw('student_id, COALESCE(SUM(paid_amount), 0) as total_paid')
                ->whereIn('student_id', $studentIds)
                ->groupBy('student_id')
                ->pluck('total_paid', 'student_id');

            foreach ($studentIds as $studentId) {
                $totalTuition = (float) ($totalTuitionByStudent[$studentId] ?? 0);
                $totalPaid = (float) ($totalPaidByStudent[$studentId] ?? 0);
                $debtByStudent[$studentId] = max(0, $totalTuition - $totalPaid);
            }
        }

        return view('payments.index', compact('payments', 'status', 'debtByStudent'));
    }

    public function create(): View
    {
        $students = Student::orderBy('name')->get();
        $classes = SchoolClass::orderBy('name')->get();

        return view('payments.create', compact('students', 'classes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_id' => 'nullable|exists:classes,id',
            'amount' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,transfer',
            'payment_date' => 'nullable|date',
            'status' => 'required|in:unpaid,partial,paid',
            'note' => 'nullable|string',
        ]);

        if ($validated['paid_amount'] > $validated['amount']) {
            return back()->withErrors(['paid_amount' => 'Số tiền đã thu không được lớn hơn số tiền phải thu.'])->withInput();
        }

        $validated['invoice_number'] = Payment::generateInvoiceNumber();

        Payment::create($validated);

        return redirect()->route('payments.index')->with('success', 'Tạo phiếu học phí thành công.');
    }

    public function show(Payment $payment): View
    {
        $payment->load(['student', 'schoolClass.teacher']);

        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment): View
    {
        $students = Student::orderBy('name')->get();
        $classes = SchoolClass::orderBy('name')->get();

        return view('payments.edit', compact('payment', 'students', 'classes'));
    }

    public function update(Request $request, Payment $payment): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_id' => 'nullable|exists:classes,id',
            'amount' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,transfer',
            'payment_date' => 'nullable|date',
            'status' => 'required|in:unpaid,partial,paid',
            'note' => 'nullable|string',
        ]);

        if ($validated['paid_amount'] > $validated['amount']) {
            return back()->withErrors(['paid_amount' => 'Số tiền đã thu không được lớn hơn số tiền phải thu.'])->withInput();
        }

        $payment->update($validated);

        return redirect()->route('payments.index')->with('success', 'Cập nhật phiếu học phí thành công.');
    }

    public function destroy(Payment $payment): RedirectResponse
    {
        $payment->delete();

        return redirect()->route('payments.index')->with('success', 'Xóa phiếu học phí thành công.');
    }
}
