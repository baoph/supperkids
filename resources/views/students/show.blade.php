@extends('layouts.app')

@section('content')
<div class="row g-3">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Thông tin học sinh</h5></div>
            <div class="card-body">
                <p><strong>Họ tên:</strong> {{ $student->name }}</p>
                <p><strong>Ngày sinh:</strong> {{ $student->date_of_birth?->format('d/m/Y') ?? 'N/A' }}</p>
                <p><strong>Giới tính:</strong> {{ $student->gender }}</p>
                <p><strong>Phụ huynh:</strong> {{ $student->parent_name }} - {{ $student->parent_phone }}</p>
                <p><strong>Email:</strong> {{ $student->email ?? 'N/A' }}</p>
                <p><strong>Địa chỉ:</strong> {{ $student->address ?? 'N/A' }}</p>
                <p><strong>Trạng thái:</strong> {{ $student->status }}</p>
                <a href="{{ route('students.index') }}" class="btn btn-secondary btn-sm">Quay lại</a>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white">Lớp đang học</div>
            <ul class="list-group list-group-flush">
                @forelse($student->classes as $class)
                    <li class="list-group-item">{{ $class->name }} ({{ $class->subject }})</li>
                @empty
                    <li class="list-group-item text-muted">Chưa có lớp.</li>
                @endforelse
            </ul>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">Lịch sử học phí</div>
            <ul class="list-group list-group-flush">
                @forelse($student->payments as $payment)
                    <li class="list-group-item">{{ $payment->invoice_code }} - {{ number_format($payment->paid_amount, 0, ',', '.') }} đ ({{ $payment->status }})</li>
                @empty
                    <li class="list-group-item text-muted">Chưa có thanh toán.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
