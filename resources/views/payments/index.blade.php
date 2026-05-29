@extends('layouts.app')

@section('content')
<div class="page-header mb-4">
    <div>
        <h4 class="page-title"><i class="bi bi-wallet2 me-2"></i>Quản lý học phí</h4>
        <p class="page-subtitle">Theo dõi thu học phí và công nợ</p>
    </div>
    <div class="d-flex gap-2 align-items-center flex-wrap">
        <form method="GET" class="d-flex gap-2">
            <select name="status" class="form-select form-select-sm" style="min-width:160px">
                <option value="">Tất cả trạng thái</option>
                <option value="unpaid" @selected($status === 'unpaid')>Chưa thu</option>
                <option value="partial" @selected($status === 'partial')>Thu một phần</option>
                <option value="paid" @selected($status === 'paid')>Đã thu</option>
            </select>
            <button class="btn btn-outline-primary btn-sm"><i class="bi bi-funnel me-1"></i>Lọc</button>
        </form>
        <a href="{{ route('payments.create') }}" class="btn btn-primary px-4">
            <i class="bi bi-plus-lg me-1"></i> Thêm học phí
        </a>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Hóa đơn</th>
                    <th>Học sinh</th>
                    <th>Lớp</th>
                    <th>Phải thu</th>
                    <th>Đã thu</th>
                    <th>Còn nợ</th>
                    <th>Hình thức</th>
                    <th>Trạng thái</th>
                    <th class="text-end">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <td class="fw-semibold">{{ $payment->invoice_number }}</td>
                        <td>{{ $payment->student->name }}</td>
                        <td>{{ $payment->schoolClass->name ?? '—' }}</td>
                        <td>{{ number_format($payment->amount, 0, ',', '.') }} đ</td>
                        <td class="text-success fw-semibold">{{ number_format($payment->paid_amount, 0, ',', '.') }} đ</td>
                        <td class="text-danger fw-semibold">{{ number_format($debtByStudent[$payment->student_id] ?? 0, 0, ',', '.') }} đ</td>
                        <td>
                            @if($payment->payment_method === 'cash')
                                <span class="badge-soft-orange"><i class="bi bi-cash-stack me-1"></i>Tiền mặt</span>
                            @else
                                <span class="badge-soft-teal"><i class="bi bi-bank me-1"></i>Chuyển khoản</span>
                            @endif
                        </td>
                        <td>
                            @if($payment->status === 'paid')
                                <span class="status-dot status-active"></span> Đã thu
                            @elseif($payment->status === 'partial')
                                <span class="status-dot status-warning"></span> Thu một phần
                            @else
                                <span class="status-dot status-inactive"></span> Chưa thu
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="btn-group-actions">
                                <a href="{{ route('payments.show', $payment) }}" class="btn btn-sm btn-light" title="Xem"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('payments.edit', $payment) }}" class="btn btn-sm btn-light" title="Sửa"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa phiếu học phí này?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-action-danger" title="Xóa"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <i class="bi bi-wallet2"></i>
                                <p>Chưa có dữ liệu học phí</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($payments->hasPages())
        <div class="card-body border-top d-flex justify-content-between align-items-center">
            <span class="showing-text">Hiển thị {{ $payments->firstItem() }}–{{ $payments->lastItem() }} / {{ $payments->total() }} phiếu</span>
            {{ $payments->links() }}
        </div>
    @endif
</div>
@endsection
