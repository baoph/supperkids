@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-1"><i class="bi bi-grid-1x2 me-2 text-primary"></i>Dashboard tổng quan</h4>
    <p class="text-muted mb-0 small">Xin chào! Đây là tổng quan hoạt động của trung tâm.</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card border-start border-primary border-4">
            <div class="card-body">
                <h6 class="text-muted mb-2"><i class="bi bi-people me-1"></i> Tổng học sinh</h6>
                <h3>{{ number_format($totalStudents) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card border-start border-success border-4">
            <div class="card-body">
                <h6 class="text-muted mb-2"><i class="bi bi-collection me-1"></i> Tổng lớp học</h6>
                <h3>{{ number_format($totalClasses) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card border-start border-info border-4">
            <div class="card-body">
                <h6 class="text-muted mb-2"><i class="bi bi-person-workspace me-1"></i> Tổng giáo viên</h6>
                <h3>{{ number_format($totalTeachers) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card border-start border-warning border-4">
            <div class="card-body">
                <h6 class="text-muted mb-2"><i class="bi bi-wallet2 me-1"></i> Doanh thu tháng</h6>
                <h3>{{ number_format($monthlyRevenue, 0, ',', '.') }} <small class="fs-6 fw-normal">đ</small></h3>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white"><i class="bi bi-bar-chart me-2"></i>Biểu đồ doanh thu theo tháng</div>
            <div class="card-body">
                <canvas id="revenueChart" height="120"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-white"><i class="bi bi-exclamation-triangle me-2"></i>Học phí chưa thanh toán</div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($unpaidPayments as $payment)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-semibold">{{ $payment->student->name }}</div>
                                    <small class="text-muted">{{ $payment->created_at?->format('d/m/Y') }}</small>
                                </div>
                                <span class="badge text-bg-danger">{{ number_format($payment->amount - $payment->paid_amount, 0, ',', '.') }} đ</span>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted py-4">
                            <i class="bi bi-check-circle d-block fs-4 mb-1 text-success"></i>
                            Không có công nợ
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Doanh thu',
                data: @json($chartData),
                backgroundColor: 'rgba(79, 70, 229, 0.6)',
                borderColor: 'rgba(79, 70, 229, 1)',
                borderWidth: 1,
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (ctx) => new Intl.NumberFormat('vi-VN').format(ctx.raw) + ' đ'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: (v) => new Intl.NumberFormat('vi-VN', {notation: 'compact'}).format(v)
                    },
                    grid: { color: '#f1f5f9' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
</script>
@endpush
