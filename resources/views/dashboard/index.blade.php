@extends('layouts.app')

@section('content')
<div class="page-header">
    <h4 class="page-title">Dashboard</h4>
    <p class="page-subtitle">Tổng quan hoạt động của trung tâm</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon-bg" style="background:#ede9fe;color:#7c3aed;">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <div class="stat-label">Học sinh</div>
                    <div class="stat-value">{{ number_format($totalStudents) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon-bg" style="background:#ccfbf1;color:#0d9488;">
                    <i class="bi bi-collection-fill"></i>
                </div>
                <div>
                    <div class="stat-label">Lớp học</div>
                    <div class="stat-value">{{ number_format($totalClasses) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon-bg" style="background:#dbeafe;color:#2563eb;">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
                <div>
                    <div class="stat-label">Giáo viên</div>
                    <div class="stat-value">{{ number_format($totalTeachers) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon-bg" style="background:#fef9c3;color:#ca8a04;">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div>
                    <div class="stat-label">Doanh thu tháng</div>
                    <div class="stat-value">{{ number_format($monthlyRevenue, 0, ',', '.') }} <small style="font-size:0.55em;font-weight:400;">đ</small></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-bar-chart me-2"></i>Doanh thu theo tháng</div>
            <div class="card-body">
                <canvas id="revenueChart" height="120"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-exclamation-circle me-2"></i>Chưa thanh toán</div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($unpaidPayments as $payment)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold" style="font-size:0.875rem;">{{ $payment->student->name }}</div>
                                    <small class="text-muted">{{ $payment->created_at?->format('d/m/Y') }}</small>
                                </div>
                                <span class="badge badge-soft-red">{{ number_format($payment->amount - $payment->paid_amount, 0, ',', '.') }} đ</span>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-center py-4" style="color:#9ca3af;">
                            <i class="bi bi-check-circle d-block mb-1" style="font-size:1.5rem;color:#22c55e;"></i>
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
    new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Doanh thu',
                data: @json($chartData),
                backgroundColor: 'rgba(124, 92, 252, 0.5)',
                borderColor: 'rgba(124, 92, 252, 1)',
                borderWidth: 1,
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1f2937',
                    titleFont: { family: 'Inter' },
                    bodyFont: { family: 'Inter' },
                    cornerRadius: 8,
                    callbacks: {
                        label: (ctx) => new Intl.NumberFormat('vi-VN').format(ctx.raw) + ' đ'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: { family: 'Inter', size: 11 },
                        color: '#9ca3af',
                        callback: (v) => new Intl.NumberFormat('vi-VN', {notation: 'compact'}).format(v)
                    },
                    grid: { color: '#f3f4f6', drawBorder: false }
                },
                x: {
                    ticks: { font: { family: 'Inter', size: 11 }, color: '#9ca3af' },
                    grid: { display: false }
                }
            }
        }
    });
</script>
@endpush
