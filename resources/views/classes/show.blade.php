@extends('layouts.app')

@section('content')
<div class="page-header mb-4">
    <div>
        <h4 class="page-title"><i class="bi bi-collection me-2"></i>Chi tiết lớp: {{ $class->name }}</h4>
        <p class="page-subtitle">Thông tin lớp học và danh sách học sinh</p>
    </div>
    <a href="{{ route('classes.index') }}" class="btn btn-outline-secondary px-4">
        <i class="bi bi-arrow-left me-1"></i> Quay lại
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body">
                <h6 class="text-uppercase text-muted small fw-semibold mb-3">Thông tin lớp học</h6>
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td class="text-muted" style="width:140px">Môn học</td>
                            <td><span class="badge-soft-purple">{{ $class->subject }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Lịch học</td>
                            <td>{{ $class->schedule ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Giáo viên</td>
                            <td class="fw-semibold">{{ $class->teacher->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Sĩ số</td>
                            <td><span class="fw-semibold">{{ $class->students->count() }}</span> / {{ $class->capacity }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Học phí</td>
                            <td class="fw-semibold">{{ number_format($class->tuition_fee, 0, ',', '.') }} đ</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Trạng thái</td>
                            <td>
                                @if($class->status === 'open')
                                    <span class="status-dot status-active"></span> Mở lớp
                                @else
                                    <span class="status-dot status-inactive"></span> Đóng lớp
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h6 class="text-uppercase text-muted small fw-semibold mb-3">Danh sách học sinh</h6>
                <ul class="list-group list-group-flush">
                    @forelse($class->students as $student)
                        <li class="list-group-item px-0">{{ $student->name }}</li>
                    @empty
                        <div class="empty-state">
                            <i class="bi bi-people"></i>
                            <p>Chưa có học sinh</p>
                        </div>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
