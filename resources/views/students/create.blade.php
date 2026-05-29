@extends('layouts.app')

@section('content')
<div class="mb-3">
    <h4 class="fw-bold mb-1"><i class="bi bi-person-plus me-2 text-primary"></i>Thêm học sinh</h4>
    <p class="text-muted mb-0 small">Điền thông tin học sinh mới vào biểu mẫu bên dưới</p>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('students.store') }}" method="POST">
            @include('students._form')
        </form>
    </div>
</div>
@endsection
