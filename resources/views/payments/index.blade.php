@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-wallet2 me-2 text-primary"></i>Quản lý học phí</h4>
        <p class="text-muted mb-0 small">Theo dõi thu học phí và công nợ</p>
    </div>
    <div class="d-flex gap-2 align-items-center">
        <form method="GET" class="d-flex gap-2">
            <select name="status" class="form-select form-select-sm" style="min-width:160px">
                <option value="">Tất cả trạng thái</option>
                <option value="unpaid" @selected($status === 'unpaid')>Chưa thu</option>
                <option value="partial" @selected($status === 'partial')>Thu một phần</option>
                <option value="paid" @selected($status === 'paid')>Đã thu</option>
            </select>
            <button class="btn btn-outline-primary btn-sm"><i class="bi bi-funnel me-1"></i>Lọc</button>
        </form>
        <a href="{{ route('payments.create') }}" class="btn btn-primary">
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
                            <span class="badge text-bg-{{ $payment->payment_method === 'cash' ? 'warning' : 'info' }}">
                                <i class="bi bi-{{ $payment->payment_method === 'cash' ? 'cash-stack' : 'bank' }} me-1"></i>
                                {{ $payment->payment_method === 'cash' ? 'Tiền mặt' : 'Chuyển khoản' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge text-bg-{{ $payment->status === 'paid' ? 'success' : ($payment->status === 'partial' ? 'warning' : 'danger') }}">
                                {{ $payment->status === 'paid' ? 'Đã thu' : ($payment->status === 'partial' ? 'Thu một phần' : 'Chưa thu') }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('payments.show', $payment) }}" class="btn btn-outline-info" title="Xem"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('payments.edit', $payment) }}" class="btn btn-outline-primary" title="Sửa"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa phiếu học phí này?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm" title="Xóa"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Chưa có dữ liệu học phí
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($payments->hasPages())
        <div class="card-body border-top">{{ $payments->links() }}</div>
    @endif
</div>
@endsection
