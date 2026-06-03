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
        <a href="{{ route('payments.export', request()->only('status')) }}" class="btn btn-outline-success">
            <i class="bi bi-file-earmark-excel me-1"></i> Xuất Excel
        </a>
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="bi bi-upload me-1"></i> Nhập Excel
        </button>
        <a href="{{ route('payments.create') }}" class="btn btn-primary px-4">
            <i class="bi bi-plus-lg me-1"></i> Thêm học phí
        </a>
    </div>
</div>

{{-- Import Modal --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('payments.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-upload me-2"></i>Nhập học phí từ Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">Chọn file Excel (.xlsx) theo đúng định dạng mẫu. Việc liên kết học sinh dựa trên <strong>Mã học sinh</strong>. Tải file mẫu bên dưới nếu cần.</p>
                    <div class="mb-3">
                        <label class="form-label">File Excel <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
                    </div>
                    <a href="{{ route('payments.template') }}" class="text-decoration-none small">
                        <i class="bi bi-download me-1"></i> Tải file mẫu import
                    </a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ngày</th>
                    <th>Học sinh</th>
                    <th>Lớp</th>
                    <th>Phải thu</th>
                    <th>Đã thu</th>
                    <th>Còn nợ</th>
                    <th>Hình thức</th>
                    <th>Trạng thái</th>
                    <th>Ngày thanh toán </th>
                    <th class="text-end">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <td class="fw-semibold">{{ $payment->created_at->format('d/m/Y') }}</td>
                        <td>{{ $payment->student->name }}</td>
                        <td>{{ $payment->schoolClass->name ?? '—' }}</td>
                        <td>{{ number_format($payment->amount, 0, ',', '.') }} đ</td>
                        <td class="text-success fw-semibold">{{ number_format($payment->paid_amount, 0, ',', '.') }} đ</td>
                        <td class="text-danger fw-semibold">{{ number_format($payment->amount - $payment->paid_amount, 0, ',', '.') }} đ</td>
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
                        <td>{{ $payment->payment_date ? $payment->payment_date->format('d/m/Y') : '—' }}</td>
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
            {{ $payments->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
