@extends('layouts.app')

@section('content')
<div class="page-header mb-4">
    <div>
        <h4 class="page-title"><i class="bi bi-receipt me-2"></i>Chi tiết phiếu học phí</h4>
        <p class="page-subtitle">Mã hóa đơn: {{ $payment->invoice_number }}</p>
    </div>
    <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary px-4">
        <i class="bi bi-arrow-left me-1"></i> Quay lại
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-6">
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td class="text-muted" style="width:160px">Mã hóa đơn</td>
                            <td class="fw-semibold">{{ $payment->invoice_number }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Học sinh</td>
                            <td class="fw-semibold">{{ $payment->student->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Lớp</td>
                            <td>{{ $payment->schoolClass->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Giáo viên</td>
                            <td>{{ $payment->schoolClass->teacher->name ?? '—' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td class="text-muted" style="width:160px">Phải thu</td>
                            <td class="fw-semibold">{{ number_format($payment->amount, 0, ',', '.') }} đ</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Đã thu</td>
                            <td class="fw-semibold text-success">{{ number_format($payment->paid_amount, 0, ',', '.') }} đ</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Hình thức</td>
                            <td>
                                @if($payment->payment_method === 'cash')
                                    <span class="badge-soft-orange">Tiền mặt</span>
                                @else
                                    <span class="badge-soft-teal">Chuyển khoản</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Ngày thanh toán</td>
                            <td>{{ $payment->payment_date?->format('d/m/Y') ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Trạng thái</td>
                            <td>
                                @if($payment->status === 'paid')
                                    <span class="status-dot status-active"></span> Đã thu
                                @elseif($payment->status === 'partial')
                                    <span class="status-dot status-warning"></span> Thu một phần
                                @else
                                    <span class="status-dot status-inactive"></span> Chưa thu
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-12">
                <hr class="my-0">
                <div class="pt-3">
                    <span class="text-muted">Ghi chú:</span> {{ $payment->note ?? 'Không có' }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
