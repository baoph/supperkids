@extends('layouts.app')

@section('content')
<div class="page-header mb-4">
    <div>
        <h4 class="page-title"><i class="bi bi-pencil-square me-2"></i>Cập nhật lớp học</h4>
        <p class="page-subtitle">Chỉnh sửa thông tin lớp {{ $class->name }}</p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('classes.update', $class) }}" method="POST">
            @method('PUT')
            @include('classes._form')
        </form>
    </div>
</div>
@endsection
