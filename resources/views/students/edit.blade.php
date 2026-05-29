@extends('layouts.app')

@section('content')
<div class="mb-3">
    <h4 class="fw-bold mb-1"><i class="bi bi-pencil-square me-2 text-primary"></i>Cập nhật học sinh</h4>
    <p class="text-muted mb-0 small">Chỉnh sửa thông tin học sinh <strong>{{ $student->name }}</strong></p>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('students.update', $student) }}" method="POST">
            @method('PUT')
            @include('students._form')
        </form>
    </div>
</div>
@endsection
