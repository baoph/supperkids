@extends('layouts.app')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><h5 class="mb-0">Cập nhật phiếu học phí</h5></div>
    <div class="card-body">
        <form action="{{ route('payments.update', $payment) }}" method="POST">
            @method('PUT')
            @include('payments._form')
        </form>
    </div>
</div>
@endsection
