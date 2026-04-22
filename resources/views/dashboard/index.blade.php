@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Dashboard tổng quan</h3>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Tổng học sinh</h6>
                <h3>{{ number_format($totalStudents) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Tổng lớp học</h6>
                <h3>{{ number_format($totalClasses) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Tổng giáo viên</h6>
                <h3>{{ number_format($totalTeachers) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Doanh thu tháng này</h6>
                <h3>{{ number_format($monthlyRevenue, 0, ',', '.') }} đ</h3>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Biểu đồ doanh thu theo tháng</div>
            <div class="card-body">
                <canvas id="revenueChart" height="120"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-semibold">Học phí chưa thanh toán</div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($unpaidPayments as $payment)
                        <li class="list-group-item">
                            <div class="fw-semibold">{{ $payment->student->name }}</div>
                            <small class="text-muted">Hạn: {{ $payment->payment_due_date?->format('d/m/Y') }} - {{ number_format($payment->amount - $payment->paid_amount, 0, ',', '.') }} đ</small>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Không có công nợ.</li>
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
                backgroundColor: 'rgba(13,110,253,0.65)',
                borderColor: 'rgba(13,110,253,1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });
</script>
@endpush
