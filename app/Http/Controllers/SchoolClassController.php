<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SchoolClassController extends Controller
{
    public function index(): View
    {
        $classes = SchoolClass::with(['teacher', 'students'])
            ->latest()
            ->paginate(10);

        return view('classes.index', compact('classes'));
    }

    public function create(): View
    {
        $teachers = Teacher::orderBy('name')->get();
        $students = Student::orderBy('name')->get();

        return view('classes.create', compact('teachers', 'students'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'schedule' => 'nullable|string|max:255',
            'teacher_id' => 'nullable|exists:teachers,id',
            'capacity' => 'required|integer|min:1',
            'tuition_fee' => 'required|numeric|min:0',
            'status' => 'required|in:open,closed',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        $studentIds = $validated['student_ids'] ?? [];
        unset($validated['student_ids']);

        $class = SchoolClass::create($validated);

        if (!empty($studentIds)) {
            $syncData = [];
            foreach ($studentIds as $studentId) {
                $syncData[$studentId] = ['enrolled_at' => now()->toDateString()];
            }
            $class->students()->sync($syncData);
        }

        return redirect()->route('classes.index')->with('success', 'Tạo lớp học thành công.');
    }

    public function show(SchoolClass $class): View
    {
        $class->load(['teacher', 'students', 'payments.student']);

        return view('classes.show', compact('class'));
    }

    public function edit(SchoolClass $class): View
    {
        $teachers = Teacher::orderBy('name')->get();
        $students = Student::orderBy('name')->get();
        $selectedStudents = $class->students()->pluck('students.id')->toArray();

        return view('classes.edit', compact('class', 'teachers', 'students', 'selectedStudents'));
    }

    public function update(Request $request, SchoolClass $class): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'schedule' => 'nullable|string|max:255',
            'teacher_id' => 'nullable|exists:teachers,id',
            'capacity' => 'required|integer|min:1',
            'tuition_fee' => 'required|numeric|min:0',
            'status' => 'required|in:open,closed',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        $studentIds = $validated['student_ids'] ?? [];
        unset($validated['student_ids']);

        $class->update($validated);

        $syncData = [];
        foreach ($studentIds as $studentId) {
            $syncData[$studentId] = ['enrolled_at' => now()->toDateString()];
        }
        $class->students()->sync($syncData);

        return redirect()->route('classes.index')->with('success', 'Cập nhật lớp học thành công.');
    }

    public function destroy(SchoolClass $class): RedirectResponse
    {
        $class->students()->detach();
        $class->delete();

        return redirect()->route('classes.index')->with('success', 'Xóa lớp học thành công.');
    }
}
