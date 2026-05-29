@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-person-lines-fill me-2 text-primary"></i>{{ $student->name }}</h4>
        <p class="text-muted mb-0 small">Chi tiết thông tin học sinh</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('students.edit', $student) }}" class="btn btn-outline-primary btn-sm px-3"><i class="bi bi-pencil me-1"></i> Sửa</a>
        <a href="{{ route('students.index') }}" class="btn btn-outline-secondary btn-sm px-3"><i class="bi bi-arrow-left me-1"></i> Quay lại</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white"><i class="bi bi-info-circle me-2"></i>Thông tin cá nhân</div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><td class="text-muted" style="width:140px">Họ tên</td><td class="fw-semibold">{{ $student->name }}</td></tr>
                    <tr><td class="text-muted">CCCD</td><td>{!! $student->cccd ?? '<span class="fst-italic text-muted">Chưa cập nhật</span>' !!}</td></tr>
                    <tr><td class="text-muted">Phụ huynh</td><td>{{ $student->parent_name }}</td></tr>
                    <tr><td class="text-muted">SĐT phụ huynh</td><td>{{ $student->parent_phone }}</td></tr>
                    <tr><td class="text-muted">Email</td><td>{{ $student->email ?? 'N/A' }}</td></tr>
                    <tr><td class="text-muted">Địa chỉ</td><td>{{ $student->address ?? 'N/A' }}</td></tr>
                    <tr>
                        <td class="text-muted">Trạng thái</td>
                        <td>
                            <span class="badge text-bg-{{ $student->status === 'studying' ? 'success' : ($student->status === 'new' ? 'info' : 'secondary') }}">
                                {{ $student->status === 'studying' ? 'Đang học' : ($student->status === 'new' ? 'Mới' : 'Nghỉ học') }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card mb-3">
            <div class="card-header bg-white"><i class="bi bi-collection me-2"></i>Lớp đang học</div>
            <ul class="list-group list-group-flush">
                @forelse($student->classes as $class)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-semibold">{{ $class->name }}</span>
                            <small class="text-muted ms-2">{{ $class->subject }}</small>
                        </div>
                        @if($class->teacher)
                            <small class="text-muted"><i class="bi bi-person me-1"></i>{{ $class->teacher->name }}</small>
                        @endif
                    </li>
                @empty
                    <li class="list-group-item text-center text-muted py-4">
                        <i class="bi bi-collection d-block fs-4 mb-1"></i>Chưa có lớp
                    </li>
                @endforelse
            </ul>
        </div>
        <div class="card">
            <div class="card-header bg-white"><i class="bi bi-receipt me-2"></i>Lịch sử học phí</div>
            <ul class="list-group list-group-flush">
                @forelse($student->payments as $payment)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-semibold">{{ $payment->invoice_number }}</span>
                            <span class="badge text-bg-{{ $payment->status === 'paid' ? 'success' : ($payment->status === 'partial' ? 'warning' : 'danger') }} ms-2">{{ $payment->status }}</span>
                        </div>
                        <span class="fw-semibold">{{ number_format($payment->paid_amount, 0, ',', '.') }} đ</span>
                    </li>
                @empty
                    <li class="list-group-item text-center text-muted py-4">
                        <i class="bi bi-receipt d-block fs-4 mb-1"></i>Chưa có thanh toán
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
