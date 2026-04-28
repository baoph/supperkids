@extends('layouts.app')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0">Quản lý học phí</h5>
        <div class="d-flex gap-2">
            <form method="GET" class="d-flex gap-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="unpaid" @selected($status === 'unpaid')>Chưa thu</option>
                    <option value="partial" @selected($status === 'partial')>Thu một phần</option>
                    <option value="paid" @selected($status === 'paid')>Đã thu</option>
                </select>
                <button class="btn btn-outline-primary btn-sm">Lọc</button>
            </form>
            <a href="{{ route('payments.create') }}" class="btn btn-primary btn-sm">+ Thêm học phí</a>
        </div>
    </div>
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
                        <td>{{ $payment->invoice_number }}</td>
                        <td>{{ $payment->student->name }}</td>
                        <td>{{ $payment->schoolClass->name ?? '-' }}</td>
                        <td>{{ number_format($payment->amount, 0, ',', '.') }} VNĐ</td>
                        <td>{{ number_format($payment->paid_amount, 0, ',', '.') }} VNĐ</td>
                        <td>{{ number_format($debtByStudent[$payment->student_id] ?? 0, 0, ',', '.') }} VNĐ</td>
                        <td>{{ $payment->payment_method === 'cash' ? 'Tiền mặt' : 'Chuyển khoản' }}</td>
                        <td>{{ $payment->status }}</td>
                        <td class="text-end">
                            <a href="{{ route('payments.show', $payment) }}" class="btn btn-outline-info btn-sm">Xem</a>
                            <a href="{{ route('payments.edit', $payment) }}" class="btn btn-outline-primary btn-sm">Sửa</a>
                            <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa phiếu học phí này?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-muted">Chưa có dữ liệu học phí.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body">{{ $payments->links() }}</div>
</div>
@endsection
