@extends('layouts.app')

@section('content')
<div class="page-header mb-4">
    <div>
        <h4 class="page-title"><i class="bi bi-plus-circle me-2"></i>Thêm phiếu học phí</h4>
        <p class="page-subtitle">Tạo phiếu thu học phí mới</p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('payments.store') }}" method="POST">
            @include('payments._form')
        </form>
    </div>
</div>
@endsection
