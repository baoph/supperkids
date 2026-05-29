@extends('layouts.app')

@section('content')
<div class="page-header mb-4">
    <div>
        <h4 class="page-title"><i class="bi bi-pencil-square me-2"></i>Cập nhật phiếu học phí</h4>
        <p class="page-subtitle">Chỉnh sửa thông tin phiếu {{ $payment->invoice_number }}</p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('payments.update', $payment) }}" method="POST">
            @method('PUT')
            @include('payments._form')
        </form>
    </div>
</div>
@endsection
