@extends('layouts.app')

@section('content')
<div class="page-header">
    <h4 class="page-title">Thêm học sinh</h4>
    <p class="page-subtitle">Điền thông tin học sinh mới vào biểu mẫu bên dưới</p>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('students.store') }}" method="POST">
            @include('students._form')
        </form>
    </div>
</div>
@endsection
