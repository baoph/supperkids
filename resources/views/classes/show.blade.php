@extends('layouts.app')

@section('content')
<div class="row g-3">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Chi tiết lớp: {{ $class->name }}</h5></div>
            <div class="card-body">
                <p><strong>Môn học:</strong> {{ $class->subject }}</p>
                <p><strong>Lịch học:</strong> {{ $class->schedule ?? 'Chưa cập nhật' }}</p>
                <p><strong>Giáo viên:</strong> {{ $class->teacher->name ?? 'Chưa phân công' }}</p>
                <p><strong>Sĩ số:</strong> {{ $class->students->count() }}/{{ $class->capacity }}</p>
                <p><strong>Học phí:</strong> {{ number_format($class->tuition_fee, 0, ',', '.') }} đ</p>
                <p><strong>Trạng thái:</strong> {{ $class->status === 'open' ? 'Mở lớp' : 'Đóng lớp' }}</p>
                <a href="{{ route('classes.index') }}" class="btn btn-secondary btn-sm">Quay lại</a>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">Danh sách học sinh</div>
            <ul class="list-group list-group-flush">
                @forelse($class->students as $student)
                    <li class="list-group-item">{{ $student->name }}</li>
                @empty
                    <li class="list-group-item text-muted">Chưa có học sinh.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
