<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        return view('payments.index', compact('payments', 'status'));
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
            'invoice_code' => 'required|string|max:50|unique:payments,invoice_code',
            'amount' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'payment_due_date' => 'required|date',
            'payment_date' => 'nullable|date',
            'status' => 'required|in:unpaid,partial,paid',
            'note' => 'nullable|string',
        ]);

        if ($validated['paid_amount'] > $validated['amount']) {
            return back()->withErrors(['paid_amount' => 'Số tiền đã thu không được lớn hơn số tiền phải thu.'])->withInput();
        }

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
            'invoice_code' => 'required|string|max:50|unique:payments,invoice_code,' . $payment->id,
            'amount' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'payment_due_date' => 'required|date',
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
