@extends('layouts.app')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="page-title">{{ $student->name }}</h4>
        <p class="page-subtitle">Chi tiết thông tin học sinh</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('students.edit', $student) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil me-1"></i> Sửa</a>
        <a href="{{ route('students.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Quay lại</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-person me-2"></i>Thông tin cá nhân</div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><td style="width:130px;color:#9ca3af;">Họ tên</td><td class="fw-semibold">{{ $student->name }}</td></tr>
                    <tr><td style="color:#9ca3af;">CCCD</td><td>{!! $student->cccd ?? '<span style="color:#d1d5db;font-style:italic;">Chưa cập nhật</span>' !!}</td></tr>
                    <tr><td style="color:#9ca3af;">Phụ huynh</td><td>{{ $student->parent_name }}</td></tr>
                    <tr><td style="color:#9ca3af;">SĐT</td><td>{{ $student->parent_phone }}</td></tr>
                    <tr><td style="color:#9ca3af;">Email</td><td>{{ $student->email ?? 'N/A' }}</td></tr>
                    <tr><td style="color:#9ca3af;">Địa chỉ</td><td>{{ $student->address ?? 'N/A' }}</td></tr>
                    <tr>
                        <td style="color:#9ca3af;">Trạng thái</td>
                        <td>
                            <span class="status-dot status-{{ $student->status === 'studying' ? 'active' : ($student->status === 'new' ? 'new' : 'inactive') }}">
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
            <div class="card-header"><i class="bi bi-collection me-2"></i>Lớp đang học</div>
            <ul class="list-group list-group-flush">
                @forelse($student->classes as $class)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-semibold">{{ $class->name }}</span>
                            <span class="badge badge-soft-teal ms-2">{{ $class->subject }}</span>
                        </div>
                        @if($class->teacher)
                            <small style="color:#9ca3af;"><i class="bi bi-person me-1"></i>{{ $class->teacher->name }}</small>
                        @endif
                    </li>
                @empty
                    <li class="list-group-item text-center py-4" style="color:#d1d5db;">
                        <i class="bi bi-collection d-block mb-1" style="font-size:1.5rem;"></i>Chưa có lớp
                    </li>
                @endforelse
            </ul>
        </div>
        <div class="card">
            <div class="card-header"><i class="bi bi-receipt me-2"></i>Lịch sử học phí</div>
            <ul class="list-group list-group-flush">
                @forelse($student->payments as $payment)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-semibold">{{ $payment->invoice_number }}</span>
                            <span class="status-dot ms-2 status-{{ $payment->status }}">{{ $payment->status === 'paid' ? 'Đã thu' : ($payment->status === 'partial' ? 'Một phần' : 'Chưa thu') }}</span>
                        </div>
                        <span class="fw-semibold">{{ number_format($payment->paid_amount, 0, ',', '.') }} đ</span>
                    </li>
                @empty
                    <li class="list-group-item text-center py-4" style="color:#d1d5db;">
                        <i class="bi bi-receipt d-block mb-1" style="font-size:1.5rem;"></i>Chưa có thanh toán
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
