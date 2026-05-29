@extends('layouts.app')

@section('content')
<div class="page-header mb-4">
    <div>
        <h4 class="page-title"><i class="bi bi-bar-chart-line me-2"></i>Báo cáo thống kê</h4>
        <p class="page-subtitle">Tổng hợp doanh thu, học phí và hiệu suất</p>
    </div>
</div>

{{-- Filter --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Loại kỳ</label>
                <select name="period" class="form-select">
                    <option value="month" @selected($period==='month')>Tháng</option>
                    <option value="quarter" @selected($period==='quarter')>Quý</option>
                    <option value="year" @selected($period==='year')>Năm</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Năm</label>
                <input type="number" name="year" class="form-control" value="{{ $year }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Tháng</label>
                <input type="number" name="month" min="1" max="12" class="form-control" value="{{ $month }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Quý</label>
                <select name="quarter" class="form-select">
                    @for($q=1;$q<=4;$q++)
                        <option value="{{ $q }}" @selected($quarter===$q)>Quý {{ $q }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Xem báo cáo</button>
            </div>
        </form>
    </div>
</div>

{{-- Revenue & Fee Status --}}
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="text-uppercase text-muted small fw-semibold mb-3">Báo cáo doanh thu</h6>
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td class="text-muted">Dự kiến</td>
                            <td class="fw-semibold text-end">{{ number_format($revenueReport->expected_revenue ?? 0, 0, ',', '.') }} đ</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Đã thu</td>
                            <td class="fw-semibold text-success text-end">{{ number_format($revenueReport->collected_revenue ?? 0, 0, ',', '.') }} đ</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Công nợ</td>
                            <td class="fw-semibold text-danger text-end">{{ number_format(($revenueReport->expected_revenue ?? 0) - ($revenueReport->collected_revenue ?? 0), 0, ',', '.') }} đ</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="text-uppercase text-muted small fw-semibold mb-3">Báo cáo học phí</h6>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead><tr><th>Trạng thái</th><th class="text-end">Số phiếu</th><th class="text-end">Công nợ</th></tr></thead>
                        <tbody>
                        @forelse($feeStatusReport as $row)
                            <tr>
                                <td>
                                    @if($row->status === 'paid')
                                        <span class="status-dot status-active"></span> Đã thu
                                    @elseif($row->status === 'partial')
                                        <span class="status-dot status-warning"></span> Thu một phần
                                    @else
                                        <span class="status-dot status-inactive"></span> Chưa thu
                                    @endif
                                </td>
                                <td class="text-end">{{ $row->total_records }}</td>
                                <td class="text-end fw-semibold">{{ number_format($row->outstanding ?? 0, 0, ',', '.') }} đ</td>
                            </tr>
                        @empty
                            <tr><td colspan="3"><div class="empty-state"><i class="bi bi-inbox"></i><p>Không có dữ liệu</p></div></td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Students & Teachers --}}
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h6 class="text-uppercase text-muted small fw-semibold mb-3">Báo cáo học sinh</h6>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead><tr><th>Trạng thái</th><th class="text-end">Số lượng</th></tr></thead>
                        <tbody>
                        @forelse($studentReport as $row)
                            <tr>
                                <td>
                                    @if($row->status === 'active')
                                        <span class="status-dot status-active"></span> Đang học
                                    @elseif($row->status === 'inactive')
                                        <span class="status-dot status-inactive"></span> Nghỉ học
                                    @else
                                        <span class="status-dot status-warning"></span> {{ $row->status }}
                                    @endif
                                </td>
                                <td class="text-end fw-semibold">{{ $row->total }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2"><div class="empty-state"><i class="bi bi-people"></i><p>Không có dữ liệu</p></div></td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h6 class="text-uppercase text-muted small fw-semibold mb-3">Hiệu suất giáo viên</h6>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead><tr><th>Giáo viên</th><th class="text-end">Số lớp</th><th class="text-end">Tổng học phí lớp</th></tr></thead>
                        <tbody>
                        @forelse($teacherPerformance as $teacher)
                            <tr>
                                <td class="fw-semibold">{{ $teacher->name }}</td>
                                <td class="text-end">{{ $teacher->classes_count }}</td>
                                <td class="text-end fw-semibold">{{ number_format($teacher->total_tuition ?? 0, 0, ',', '.') }} đ</td>
                            </tr>
                        @empty
                            <tr><td colspan="3"><div class="empty-state"><i class="bi bi-person-badge"></i><p>Không có dữ liệu</p></div></td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
