@csrf
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Tên lớp <span class="text-danger">*</span></label>
        <input type="text" name="name" value="{{ old('name', $class->name ?? '') }}" class="form-control @error('name') is-invalid @enderror" placeholder="VD: Lớp Toán nâng cao K5">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Môn học <span class="text-danger">*</span></label>
        <input type="text" name="subject" value="{{ old('subject', $class->subject ?? '') }}" class="form-control @error('subject') is-invalid @enderror" placeholder="VD: Toán, Tiếng Anh, Vẽ...">
        @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Lịch học</label>
        <input type="text" name="schedule" value="{{ old('schedule', $class->schedule ?? '') }}" class="form-control" placeholder="VD: T2, T4, T6 — 18:00-19:30">
    </div>
    <div class="col-md-6">
        <label class="form-label">Giáo viên phụ trách</label>
        <select name="teacher_id" class="form-select">
            <option value="">— Chọn giáo viên —</option>
            @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}" @selected(old('teacher_id', $class->teacher_id ?? '') == $teacher->id)>{{ $teacher->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Sĩ số tối đa <span class="text-danger">*</span></label>
        <input type="number" min="1" name="capacity" value="{{ old('capacity', $class->capacity ?? 20) }}" class="form-control @error('capacity') is-invalid @enderror" placeholder="20">
        @error('capacity') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Học phí <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="text" name="tuition_fee_display" value="{{ old('tuition_fee', $class->tuition_fee ?? 0) }}" class="form-control currency-input @error('tuition_fee') is-invalid @enderror" placeholder="VD: 1.500.000" data-target="tuition_fee">
            <span class="input-group-text">đ</span>
            <input type="hidden" name="tuition_fee" value="{{ old('tuition_fee', $class->tuition_fee ?? 0) }}">
            @error('tuition_fee') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
        <select name="status" class="form-select">
            <option value="open" @selected(old('status', $class->status ?? 'open') === 'open')>Mở lớp</option>
            <option value="closed" @selected(old('status', $class->status ?? 'open') === 'closed')>Đóng lớp</option>
        </select>
    </div>
    <div class="col-12">
        <label class="form-label">Học sinh đang theo học</label>
        <select name="student_ids[]" class="form-select" multiple size="6">
            @foreach($students as $student)
                <option value="{{ $student->id }}" @selected(in_array($student->id, old('student_ids', $selectedStudents ?? [])))>{{ $student->name }}</option>
            @endforeach
        </select>
        <small class="text-muted">Giữ Ctrl/Cmd để chọn nhiều học sinh.</small>
    </div>
</div>
<div class="mt-4 d-flex gap-2">
    <button class="btn btn-primary px-4">Lưu</button>
    <a href="{{ route('classes.index') }}" class="btn btn-outline-secondary px-4">Hủy</a>
</div>
