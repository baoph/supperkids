@extends('layouts.app')

@section('content')
<div class="page-header mb-4">
    <div>
        <h4 class="page-title"><i class="bi bi-plus-circle me-2"></i>Thêm lớp học</h4>
        <p class="page-subtitle">Tạo lớp học mới trong hệ thống</p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('classes.store') }}" method="POST">
            @include('classes._form')
        </form>
    </div>
</div>
@endsection
