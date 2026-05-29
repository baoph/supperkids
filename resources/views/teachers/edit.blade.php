@extends('layouts.app')

@section('content')
<div class="page-header">
    <h4 class="page-title">Cập nhật giáo viên</h4>
    <p class="page-subtitle">Chỉnh sửa thông tin giáo viên <strong>{{ $teacher->name }}</strong></p>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('teachers.update', $teacher) }}" method="POST">
            @method('PUT')
            @include('teachers._form')
        </form>
    </div>
</div>
@endsection
