@extends('layouts.app')

@section('content')
<div class="page-header mb-4">
    <div>
        <h4 class="page-title"><i class="bi bi-person-badge me-2"></i>Chi tiết giáo viên</h4>
        <p class="page-subtitle">Thông tin chi tiết và lớp phụ trách</p>
    </div>
    <a href="{{ route('teachers.index') }}" class="btn btn-outline-secondary px-4">
        <i class="bi bi-arrow-left me-1"></i> Quay lại
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h6 class="text-uppercase text-muted small fw-semibold mb-3">Thông tin giáo viên</h6>
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td class="text-muted" style="width:140px">Họ tên</td>
                            <td class="fw-semibold">{{ $teacher->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Chuyên môn</td>
                            <td><span class="badge-soft-purple">{{ $teacher->specialization }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">SĐT</td>
                            <td>{{ $teacher->phone }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Email</td>
                            <td>{{ $teacher->email }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Lương</td>
                            <td class="fw-semibold">{{ number_format($teacher->salary, 0, ',', '.') }} đ</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Lịch dạy</td>
                            <td>{{ $teacher->teaching_schedule ?? '—' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h6 class="text-uppercase text-muted small fw-semibold mb-3">Lớp phụ trách</h6>
                <ul class="list-group list-group-flush">
                    @forelse($teacher->classes as $class)
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fw-semibold">{{ $class->name }}</span>
                                <span class="text-muted"> — {{ $class->subject }}</span>
                            </div>
                            <span class="badge-soft-teal">{{ $class->students->count() }} học sinh</span>
                        </li>
                    @empty
                        <div class="empty-state">
                            <i class="bi bi-collection"></i>
                            <p>Chưa phụ trách lớp nào</p>
                        </div>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
