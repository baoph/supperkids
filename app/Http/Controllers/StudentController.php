<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
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

        $students = $query->paginate(10)->withQueryString();
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
}
