<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentController extends Controller
{
    /**
     * Status code => label map used for Excel import/export.
     */
    private array $statusLabels = [
        'new' => 'Mới',
        'studying' => 'Đang học',
        'inactive' => 'Nghỉ học',
    ];

    private function buildFilteredQuery(Request $request)
    {
        $query = Student::with('classes')->latest();

        // Search by name or phone
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('parent_phone', 'like', "%{$search}%");
            });
        }

        // Filter by class
        if ($classId = $request->input('class_id')) {
            $query->whereHas('classes', function ($q) use ($classId) {
                $q->where('classes.id', $classId);
            });
        }

        return $query;
    }

    public function index(Request $request): View
    {
        $students = $this->buildFilteredQuery($request)->paginate(10)->withQueryString();
        $classes = SchoolClass::orderBy('name')->get();

        return view('students.index', compact('students', 'classes'));
    }

    public function create(): View
    {
        $classes = SchoolClass::orderBy('name')->get();

        return view('students.create', compact('classes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cccd' => 'nullable|string|max:20',
            'parent_phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'status' => 'required|in:new,studying,inactive',
            'class_ids' => 'nullable|array',
            'class_ids.*' => 'exists:classes,id',
        ]);

        $classIds = $validated['class_ids'] ?? [];
        unset($validated['class_ids']);

        $student = Student::create($validated);

        $syncData = [];
        foreach ($classIds as $classId) {
            $syncData[$classId] = ['enrolled_at' => now()->toDateString()];
        }
        $student->classes()->sync($syncData);

        return redirect()->route('students.index')->with('success', 'Tạo học sinh thành công.');
    }

    public function show(Student $student): View
    {
        $student->load(['classes.teacher', 'payments.class']);

        return view('students.show', compact('student'));
    }

    public function edit(Student $student): View
    {
        $classes = SchoolClass::orderBy('name')->get();
        $selectedClasses = $student->classes()->pluck('classes.id')->toArray();

        return view('students.edit', compact('student', 'classes', 'selectedClasses'));
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cccd' => 'nullable|string|max:20',
            'parent_phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'status' => 'required|in:new,studying,inactive',
            'class_ids' => 'nullable|array',
            'class_ids.*' => 'exists:classes,id',
        ]);

        $classIds = $validated['class_ids'] ?? [];
        unset($validated['class_ids']);

        $student->update($validated);

        $syncData = [];
        foreach ($classIds as $classId) {
            $syncData[$classId] = ['enrolled_at' => now()->toDateString()];
        }
        $student->classes()->sync($syncData);

        return redirect()->route('students.index')->with('success', 'Cập nhật học sinh thành công.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $student->classes()->detach();
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Xóa học sinh thành công.');
    }

    /**
     * Column headers used in both export and template.
     */
    private function excelHeaders(): array
    {
        return ['Họ tên', 'CCCD', 'SĐT phụ huynh', 'Email', 'Địa chỉ', 'Trạng thái', 'Lớp đang học'];
    }

    private function applyHeaderStyle(Spreadsheet $spreadsheet, int $columnCount): void
    {
        $sheet = $spreadsheet->getActiveSheet();
        $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnCount);
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
     * Export students to Excel following the active filters/search.
     */
    public function export(Request $request): StreamedResponse
    {
        $students = $this->buildFilteredQuery($request)->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Học sinh');

        $headers = $this->excelHeaders();
        $sheet->fromArray($headers, null, 'A1');

        $row = 2;
        foreach ($students as $student) {
            $sheet->setCellValue("A{$row}", $student->name);
            $sheet->setCellValueExplicit("B{$row}", (string) $student->cccd, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("C{$row}", (string) $student->parent_phone, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue("D{$row}", $student->email);
            $sheet->setCellValue("E{$row}", $student->address);
            $sheet->setCellValue("F{$row}", $this->statusLabels[$student->status] ?? $student->status);
            $sheet->setCellValue("G{$row}", $student->classes->pluck('name')->implode(', '));
            $row++;
        }

        $this->applyHeaderStyle($spreadsheet, count($headers));

        return $this->streamSpreadsheet($spreadsheet, 'hoc-sinh-' . now()->format('Ymd_His') . '.xlsx');
    }

    /**
     * Download an empty Excel template with column headers.
     */
    public function template(): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Mẫu học sinh');

        $headers = $this->excelHeaders();
        $sheet->fromArray($headers, null, 'A1');

        // Example row to guide the user.
        $sheet->fromArray(
            ['Nguyễn Văn A', '012345678901', '0901234567', 'email@example.com', 'Hà Nội', 'Đang học', 'Lớp Toán 1, Lớp Văn 2'],
            null,
            'A2'
        );

        $this->applyHeaderStyle($spreadsheet, count($headers));

        return $this->streamSpreadsheet($spreadsheet, 'mau-import-hoc-sinh.xlsx');
    }

    /**
     * Import students from an uploaded Excel file. No validation (per requirement).
     */
    public function import(Request $request): RedirectResponse
    {
        if (! $request->hasFile('file')) {
            return redirect()->route('students.index')->with('error', 'Vui lòng chọn file Excel để import.');
        }

        try {
            $spreadsheet = IOFactory::load($request->file('file')->getRealPath());
            $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            // Map status labels back to codes.
            $statusByLabel = array_flip($this->statusLabels);
            $classesByName = SchoolClass::all()->keyBy(fn ($c) => mb_strtolower(trim($c->name)));

            $count = 0;
            $first = true;
            foreach ($rows as $row) {
                // Skip header row.
                if ($first) {
                    $first = false;
                    continue;
                }

                $name = trim((string) ($row['A'] ?? ''));
                if ($name === '') {
                    continue; // skip empty lines
                }

                $statusRaw = trim((string) ($row['F'] ?? ''));
                $status = $statusByLabel[$statusRaw] ?? (in_array($statusRaw, ['new', 'studying', 'inactive'], true) ? $statusRaw : 'new');

                $student = Student::create([
                    'name' => $name,
                    'cccd' => trim((string) ($row['B'] ?? '')) ?: null,
                    'parent_phone' => trim((string) ($row['C'] ?? '')) ?: null,
                    'email' => trim((string) ($row['D'] ?? '')) ?: null,
                    'address' => trim((string) ($row['E'] ?? '')) ?: null,
                    'status' => $status,
                ]);

                // Resolve class names to ids.
                $classNames = array_filter(array_map('trim', explode(',', (string) ($row['G'] ?? ''))));
                $syncData = [];
                foreach ($classNames as $className) {
                    $cls = $classesByName->get(mb_strtolower($className));
                    if ($cls) {
                        $syncData[$cls->id] = ['enrolled_at' => now()->toDateString()];
                    }
                }
                if (! empty($syncData)) {
                    $student->classes()->sync($syncData);
                }

                $count++;
            }

            return redirect()->route('students.index')->with('success', "Đã import thành công {$count} bản ghi.");
        } catch (\Throwable $e) {
            return redirect()->route('students.index')->with('error', 'Import thất bại: ' . $e->getMessage());
        }
    }
}
