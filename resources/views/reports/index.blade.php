@extends('layouts.app')

@section('content')
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white"><h5 class="mb-0">Bộ lọc báo cáo</h5></div>
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
                <button class="btn btn-primary w-100">Xem báo cáo</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white">Báo cáo doanh thu</div>
            <div class="card-body">
                <p><strong>Dự kiến:</strong> {{ number_format($revenueReport->expected_revenue ?? 0, 0, ',', '.') }} đ</p>
                <p><strong>Đã thu:</strong> {{ number_format($revenueReport->collected_revenue ?? 0, 0, ',', '.') }} đ</p>
                <p><strong>Công nợ:</strong> {{ number_format(($revenueReport->expected_revenue ?? 0) - ($revenueReport->collected_revenue ?? 0), 0, ',', '.') }} đ</p>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white">Báo cáo học phí</div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>Trạng thái</th><th>Số phiếu</th><th>Công nợ</th></tr></thead>
                    <tbody>
                    @forelse($feeStatusReport as $row)
                        <tr>
                            <td>{{ $row->status }}</td>
                            <td>{{ $row->total_records }}</td>
                            <td>{{ number_format($row->outstanding ?? 0, 0, ',', '.') }} đ</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-muted text-center">Không có dữ liệu</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">Báo cáo học sinh</div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>Trạng thái</th><th>Số lượng</th></tr></thead>
                    <tbody>
                    @forelse($studentReport as $row)
                        <tr><td>{{ $row->status }}</td><td>{{ $row->total }}</td></tr>
                    @empty
                        <tr><td colspan="2" class="text-muted text-center">Không có dữ liệu</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">Hiệu suất giáo viên</div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>Giáo viên</th><th>Số lớp</th><th>Tổng học phí lớp</th></tr></thead>
                    <tbody>
                    @forelse($teacherPerformance as $teacher)
                        <tr>
                            <td>{{ $teacher->name }}</td>
                            <td>{{ $teacher->classes_count }}</td>
                            <td>{{ number_format($teacher->total_tuition ?? 0, 0, ',', '.') }} đ</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-muted text-center">Không có dữ liệu</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
