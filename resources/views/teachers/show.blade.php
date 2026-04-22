@extends('layouts.app')

@section('content')
<div class="row g-3">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Thông tin giáo viên</h5></div>
            <div class="card-body">
                <p><strong>Họ tên:</strong> {{ $teacher->name }}</p>
                <p><strong>Chuyên môn:</strong> {{ $teacher->specialization }}</p>
                <p><strong>SĐT:</strong> {{ $teacher->phone }}</p>
                <p><strong>Email:</strong> {{ $teacher->email }}</p>
                <p><strong>Lương:</strong> {{ number_format($teacher->salary, 0, ',', '.') }} đ</p>
                <p><strong>Lịch dạy:</strong> {{ $teacher->teaching_schedule ?? 'Chưa cập nhật' }}</p>
                <a href="{{ route('teachers.index') }}" class="btn btn-secondary btn-sm">Quay lại</a>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">Lớp phụ trách</div>
            <ul class="list-group list-group-flush">
                @forelse($teacher->classes as $class)
                    <li class="list-group-item">{{ $class->name }} - {{ $class->subject }} ({{ $class->students->count() }} học sinh)</li>
                @empty
                    <li class="list-group-item text-muted">Chưa phụ trách lớp nào.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
