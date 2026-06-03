<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PaymentController extends Controller
{
    private array $methodLabels = [
        'cash' => 'Tiền mặt',
        'transfer' => 'Chuyển khoản',
    ];

    private array $statusLabels = [
        'unpaid' => 'Chưa thu',
        'partial' => 'Thu một phần',
        'paid' => 'Đã thu',
    ];

    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();

        $payments = Payment::with(['student', 'schoolClass'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(10);
            // ->get();

        // $studentIds = $payments->getCollection()->pluck('student_id')->unique()->filter()->values();

        // $debtByStudent = [];

        // if ($studentIds->isNotEmpty()) {
        //     $totalTuitionByStudent = DB::table('class_student')
        //         ->join('classes', 'classes.id', '=', 'class_student.class_id')
        //         ->selectRaw('class_student.student_id, COALESCE(SUM(classes.tuition_fee), 0) as total_tuition')
        //         ->whereIn('class_student.student_id', $studentIds)
        //         ->groupBy('class_student.student_id')
        //         ->pluck('total_tuition', 'class_student.student_id');

        //     $totalPaidByStudent = Payment::query()
        //         ->selectRaw('student_id, COALESCE(SUM(paid_amount), 0) as total_paid')
        //         ->whereIn('student_id', $studentIds)
        //         ->groupBy('student_id')
        //         ->pluck('total_paid', 'student_id');

        //     foreach ($studentIds as $studentId) {
        //         // dd($totalTuitionByStudent[3] );
        //         $totalTuition = (float) ($totalTuitionByStudent[$studentId] ?? 0);
        //         $totalPaid = (float) ($totalPaidByStudent[$studentId] ?? 0);
        //         $debtByStudent[$studentId] = max(0, $totalTuition - $totalPaid);
        //     }
        // }

        // return view('payments.index', compact('payments', 'status', 'debtByStudent'));
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

    private function excelHeaders(): array
    {
        return ['Mã học sinh', 'Tên học sinh', 'Lớp', 'Phải thu (đ)', 'Đã thu (đ)', 'Còn nợ (đ)', 'Hình thức', 'Ngày thanh toán', 'Trạng thái', 'Ghi chú'];
    }

    private function applyHeaderStyle(Spreadsheet $spreadsheet, int $columnCount): void
    {
        $sheet = $spreadsheet->getActiveSheet();
        $lastColumn = Coordinate::stringFromColumnIndex($columnCount);
        $sheet->getStyle("A1:{$lastColumn}1")->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle("A1:{$lastColumn}1")->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('7C5CFC');
        $sheet->getStyle("A1:{$lastColumn}1")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(22);
        for ($i = 1; $i <= $columnCount; $i++) {
            $sheet->getColumnDimensionByColumn($i)->setAutoSize(true);
        }
    }

    private function streamSpreadsheet(Spreadsheet $spreadsheet, string $filename): StreamedResponse
    {
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Export payments to Excel following the active status filter.
     */
    public function export(Request $request): StreamedResponse
    {
        $status = $request->string('status')->toString();

        $payments = Payment::with(['student', 'schoolClass'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Học phí');

        $headers = $this->excelHeaders();
        $sheet->fromArray($headers, null, 'A1');

        $row = 2;
        foreach ($payments as $payment) {
            $sheet->setCellValue("A{$row}", $payment->student_id);
            $sheet->setCellValue("B{$row}", $payment->student->name ?? '');
            $sheet->setCellValue("C{$row}", $payment->schoolClass->name ?? '');
            $sheet->setCellValue("D{$row}", (float) $payment->amount);
            $sheet->setCellValue("E{$row}", (float) $payment->paid_amount);
            $sheet->setCellValue("F{$row}", (float) ($payment->amount - $payment->paid_amount));
            $sheet->setCellValue("G{$row}", $this->methodLabels[$payment->payment_method] ?? $payment->payment_method);
            $sheet->setCellValue("H{$row}", $payment->payment_date ? $payment->payment_date->format('Y-m-d') : '');
            $sheet->setCellValue("I{$row}", $this->statusLabels[$payment->status] ?? $payment->status);
            $sheet->setCellValue("J{$row}", $payment->note);
            $row++;
        }

        $this->applyHeaderStyle($spreadsheet, count($headers));

        return $this->streamSpreadsheet($spreadsheet, 'hoc-phi-' . now()->format('Ymd_His') . '.xlsx');
    }

    /**
     * Download an empty Excel template with column headers.
     */
    public function template(): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Mẫu học phí');

        $headers = $this->excelHeaders();
        $sheet->fromArray($headers, null, 'A1');

        $sheet->fromArray(
            ['1', 'Nguyễn Văn A', 'Lớp Toán 1', '1.500.000', '1.500.000', 'Tiền mặt', '2026-06-01', 'Đã thu', 'Học phí tháng 6'],
            null,
            'A2'
        );

        $this->applyHeaderStyle($spreadsheet, count($headers));

        return $this->streamSpreadsheet($spreadsheet, 'mau-import-hoc-phi.xlsx');
    }

    /**
     * Import payments from an uploaded Excel file. Linking uses student_id (column A).
     */
    public function import(Request $request): RedirectResponse
    {
        if (! $request->hasFile('file')) {
            return redirect()->route('payments.index')->with('error', 'Vui lòng chọn file Excel để import.');
        }

        try {
            $spreadsheet = IOFactory::load($request->file('file')->getRealPath());
            $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            $methodByLabel = array_flip($this->methodLabels);
            $statusByLabel = array_flip($this->statusLabels);
            $classesByName = SchoolClass::all()->keyBy(fn ($c) => mb_strtolower(trim($c->name)));

            $count = 0;
            $first = true;
            foreach ($rows as $row) {
                if ($first) {
                    $first = false;
                    continue;
                }

                // Linking is done by student_id (column A).
                $studentId = (int) trim((string) ($row['A'] ?? ''));
                if ($studentId <= 0) {
                    continue;
                }

                $methodRaw = trim((string) ($row['F'] ?? ''));
                $method = $methodByLabel[$methodRaw] ?? (in_array($methodRaw, ['cash', 'transfer'], true) ? $methodRaw : 'cash');

                $statusRaw = trim((string) ($row['H'] ?? ''));
                $status = $statusByLabel[$statusRaw] ?? (in_array($statusRaw, ['unpaid', 'partial', 'paid'], true) ? $statusRaw : 'unpaid');

                $classId = null;
                $className = trim((string) ($row['C'] ?? ''));
                if ($className !== '') {
                    $cls = $classesByName->get(mb_strtolower($className));
                    $classId = $cls?->id;
                }

                $paymentDate = trim((string) ($row['G'] ?? ''));

                Payment::create([
                    'student_id' => $studentId,
                    'class_id' => $classId,
                    'amount' => $this->parseCurrency($row['D'] ?? 0),
                    'paid_amount' => $this->parseCurrency($row['E'] ?? 0),
                    'payment_method' => $method,
                    'payment_date' => $paymentDate !== '' ? $paymentDate : null,
                    'status' => $status,
                    'note' => trim((string) ($row['I'] ?? '')) ?: null,
                ]);

                $count++;
            }

            return redirect()->route('payments.index')->with('success', "Đã import thành công {$count} bản ghi.");
        } catch (\Throwable $e) {
            return redirect()->route('payments.index')->with('error', 'Import thất bại: ' . $e->getMessage());
        }
    }

    /**
     * Convert a VND-formatted string (e.g. "1.500.000") to an integer.
     */
    private function parseCurrency($value): int
    {
        return (int) preg_replace('/[^\d]/', '', (string) $value);
    }
}
