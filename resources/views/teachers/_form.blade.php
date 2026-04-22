@csrf
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Họ tên *</label>
        <input type="text" name="name" value="{{ old('name', $teacher->name ?? '') }}" class="form-control @error('name') is-invalid @enderror">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Chuyên môn *</label>
        <input type="text" name="specialization" value="{{ old('specialization', $teacher->specialization ?? '') }}" class="form-control @error('specialization') is-invalid @enderror">
        @error('specialization') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">SĐT *</label>
        <input type="text" name="phone" value="{{ old('phone', $teacher->phone ?? '') }}" class="form-control @error('phone') is-invalid @enderror">
        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Email *</label>
        <input type="email" name="email" value="{{ old('email', $teacher->email ?? '') }}" class="form-control @error('email') is-invalid @enderror">
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Lương *</label>
        <input type="number" min="0" step="0.01" name="salary" value="{{ old('salary', $teacher->salary ?? 0) }}" class="form-control @error('salary') is-invalid @enderror">
        @error('salary') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Lịch dạy</label>
        <input type="text" name="teaching_schedule" value="{{ old('teaching_schedule', $teacher->teaching_schedule ?? '') }}" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">Trạng thái *</label>
        <select name="status" class="form-select">
            <option value="active" @selected(old('status', $teacher->status ?? 'active') === 'active')>Đang làm việc</option>
            <option value="inactive" @selected(old('status', $teacher->status ?? '') === 'inactive')>Ngưng</option>
        </select>
    </div>
</div>
<div class="mt-4 d-flex gap-2">
    <button class="btn btn-primary">Lưu</button>
    <a href="{{ route('teachers.index') }}" class="btn btn-secondary">Quay lại</a>
</div>
