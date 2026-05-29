@csrf
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Họ tên <span class="text-danger">*</span></label>
        <input type="text" name="name" value="{{ old('name', $student->name ?? '') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Nhập họ và tên học sinh">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">CCCD</label>
        <input type="text" name="cccd" value="{{ old('cccd', $student->cccd ?? '') }}" class="form-control @error('cccd') is-invalid @enderror" placeholder="Số căn cước công dân" maxlength="20">
        @error('cccd') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Phụ huynh <span class="text-danger">*</span></label>
        <input type="text" name="parent_name" value="{{ old('parent_name', $student->parent_name ?? '') }}" class="form-control @error('parent_name') is-invalid @enderror" placeholder="Tên phụ huynh">
        @error('parent_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">SĐT phụ huynh <span class="text-danger">*</span></label>
        <input type="text" name="parent_phone" value="{{ old('parent_phone', $student->parent_phone ?? '') }}" class="form-control @error('parent_phone') is-invalid @enderror" placeholder="Số điện thoại phụ huynh">
        @error('parent_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email', $student->email ?? '') }}" class="form-control" placeholder="Email liên hệ">
    </div>
    <div class="col-md-6">
        <label class="form-label">Địa chỉ</label>
        <input type="text" name="address" value="{{ old('address', $student->address ?? '') }}" class="form-control" placeholder="Địa chỉ">
    </div>
    <div class="col-md-6">
        <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
        <select name="status" class="form-select">
            <option value="new" @selected(old('status', $student->status ?? 'new') === 'new')>Mới</option>
            <option value="studying" @selected(old('status', $student->status ?? '') === 'studying')>Đang học</option>
            <option value="inactive" @selected(old('status', $student->status ?? '') === 'inactive')>Nghỉ học</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Lớp đang học</label>
        <select name="class_ids[]" class="form-select" multiple size="4">
            @foreach($classes as $class)
                <option value="{{ $class->id }}" @selected(in_array($class->id, old('class_ids', $selectedClasses ?? [])))>{{ $class->name }}</option>
            @endforeach
        </select>
        <small style="color:#9ca3af;">Giữ Ctrl để chọn nhiều lớp</small>
    </div>
</div>
<div class="mt-4 d-flex gap-2">
    <button class="btn btn-primary px-4">Lưu</button>
    <a href="{{ route('students.index') }}" class="btn btn-outline-secondary px-4">Hủy</a>
</div>
