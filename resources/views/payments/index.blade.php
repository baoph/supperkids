@extends('layouts.app')

@section('content')
<div class="page-header mb-4">
    <div>
        <h4 class="page-title"><i class="bi bi-wallet2 me-2"></i>Quản lý học phí</h4>
        <p class="page-subtitle">Theo dõi thu học phí và công nợ</p>
    </div>
    <div class="d-flex gap-2 align-items-center flex-wrap">
        <a href="{{ route('payments.export', request()->only('student_name', 'created_from', 'created_to', 'payment_from', 'payment_to', 'status')) }}" class="btn btn-outline-success">
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

{{-- Filter --}}
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('payments.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Tên học sinh</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="student_name" value="{{ request('student_name') }}" class="form-control" placeholder="Tìm theo tên học sinh...">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Ngày tạo</label>
                <div class="d-flex align-items-center gap-1">
                    <input type="date" name="created_from" value="{{ request('created_from') }}" class="form-control" title="Từ ngày">
                    <span class="text-muted">–</span>
                    <input type="date" name="created_to" value="{{ request('created_to') }}" class="form-control" title="Đến ngày">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Ngày thanh toán</label>
                <div class="d-flex align-items-center gap-1">
                    <input type="date" name="payment_from" value="{{ request('payment_from') }}" class="form-control" title="Từ ngày">
                    <span class="text-muted">–</span>
                    <input type="date" name="payment_to" value="{{ request('payment_to') }}" class="form-control" title="Đến ngày">
                </div>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-funnel me-1"></i> Lọc
                </button>
                @if(request('student_name') || request('created_from') || request('created_to') || request('payment_from') || request('payment_to') || request('status'))
                    <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary" title="Xóa bộ lọc">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </a>
                @endif
            </div>
        </form>
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
                        <td colspan="10">
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
