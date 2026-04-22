<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function index(): View
    {
        $teachers = Teacher::withCount('classes')->latest()->paginate(10);

        return view('teachers.index', compact('teachers'));
    }

    public function create(): View
    {
        return view('teachers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:teachers,email',
            'salary' => 'required|numeric|min:0',
            'teaching_schedule' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        Teacher::create($validated);

        return redirect()->route('teachers.index')->with('success', 'Tạo giáo viên thành công.');
    }

    public function show(Teacher $teacher): View
    {
        $teacher->load('classes.students');

        return view('teachers.show', compact('teacher'));
    }

    public function edit(Teacher $teacher): View
    {
        return view('teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:teachers,email,' . $teacher->id,
            'salary' => 'required|numeric|min:0',
            'teaching_schedule' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $teacher->update($validated);

        return redirect()->route('teachers.index')->with('success', 'Cập nhật giáo viên thành công.');
    }

    public function destroy(Teacher $teacher): RedirectResponse
    {
        $teacher->delete();

        return redirect()->route('teachers.index')->with('success', 'Xóa giáo viên thành công.');
    }
}
