@extends('layouts.app')

@section('content')
<div class="page-header">
    <h4 class="page-title">Cập nhật học sinh</h4>
    <p class="page-subtitle">Chỉnh sửa thông tin học sinh <strong>{{ $student->name }}</strong></p>
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
