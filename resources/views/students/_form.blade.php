@csrf
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Họ tên *</label>
        <input type="text" name="name" value="{{ old('name', $student->name ?? '') }}" class="form-control @error('name') is-invalid @enderror">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">Ngày sinh</label>
        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', isset($student->date_of_birth) ? $student->date_of_birth->format('Y-m-d') : '') }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label">Giới tính *</label>
        <select name="gender" class="form-select">
            <option value="male" @selected(old('gender', $student->gender ?? 'male') === 'male')>Nam</option>
            <option value="female" @selected(old('gender', $student->gender ?? '') === 'female')>Nữ</option>
            <option value="other" @selected(old('gender', $student->gender ?? '') === 'other')>Khác</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Phụ huynh *</label>
        <input type="text" name="parent_name" value="{{ old('parent_name', $student->parent_name ?? '') }}" class="form-control @error('parent_name') is-invalid @enderror">
        @error('parent_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">SĐT phụ huynh *</label>
        <input type="text" name="parent_phone" value="{{ old('parent_phone', $student->parent_phone ?? '') }}" class="form-control @error('parent_phone') is-invalid @enderror">
        @error('parent_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email', $student->email ?? '') }}" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">Địa chỉ</label>
        <input type="text" name="address" value="{{ old('address', $student->address ?? '') }}" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">Trạng thái *</label>
        <select name="status" class="form-select">
            <option value="new" @selected(old('status', $student->status ?? 'new') === 'new')>Mới</option>
            <option value="studying" @selected(old('status', $student->status ?? '') === 'studying')>Đang học</option>
            <option value="inactive" @selected(old('status', $student->status ?? '') === 'inactive')>Nghỉ học</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Lớp đang học</label>
        <select name="class_ids[]" class="form-select" multiple size="5">
            @foreach($classes as $class)
                <option value="{{ $class->id }}" @selected(in_array($class->id, old('class_ids', $selectedClasses ?? [])))>{{ $class->name }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="mt-4 d-flex gap-2">
    <button class="btn btn-primary">Lưu</button>
    <a href="{{ route('students.index') }}" class="btn btn-secondary">Quay lại</a>
</div>
