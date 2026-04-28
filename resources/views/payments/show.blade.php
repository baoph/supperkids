@extends('layouts.app')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><h5 class="mb-0">Chi tiết phiếu học phí</h5></div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6"><strong>Mã hóa đơn:</strong> {{ $payment->invoice_number }}</div>
            <div class="col-md-6"><strong>Học sinh:</strong> {{ $payment->student->name }}</div>
            <div class="col-md-6"><strong>Lớp:</strong> {{ $payment->schoolClass->name ?? '-' }}</div>
            <div class="col-md-6"><strong>Giáo viên:</strong> {{ $payment->schoolClass->teacher->name ?? '-' }}</div>
            <div class="col-md-6"><strong>Phải thu:</strong> {{ number_format($payment->amount, 0, ',', '.') }} đ</div>
            <div class="col-md-6"><strong>Đã thu:</strong> {{ number_format($payment->paid_amount, 0, ',', '.') }} đ</div>
            <div class="col-md-6"><strong>Hình thức thanh toán:</strong> {{ $payment->payment_method === 'cash' ? 'Tiền mặt' : 'Chuyển khoản' }}</div>
            <div class="col-md-6"><strong>Ngày thanh toán:</strong> {{ $payment->payment_date?->format('d/m/Y') ?? 'Chưa thanh toán' }}</div>
            <div class="col-md-6"><strong>Trạng thái:</strong> {{ $payment->status }}</div>
            <div class="col-12"><strong>Ghi chú:</strong> {{ $payment->note ?? 'Không có' }}</div>
        </div>
        <div class="mt-3">
            <a href="{{ route('payments.index') }}" class="btn btn-secondary btn-sm">Quay lại</a>
        </div>
    </div>
</div>
@endsection
