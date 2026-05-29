@extends('layouts.app')

@section('content')
<div class="page-header">
    <h4 class="page-title">Thêm giáo viên</h4>
    <p class="page-subtitle">Điền thông tin giáo viên mới</p>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('teachers.store') }}" method="POST">
            @include('teachers._form')
        </form>
    </div>
</div>
@endsection
