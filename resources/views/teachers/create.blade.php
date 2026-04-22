@extends('layouts.app')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><h5 class="mb-0">Thêm giáo viên</h5></div>
    <div class="card-body">
        <form action="{{ route('teachers.store') }}" method="POST">
            @include('teachers._form')
        </form>
    </div>
</div>
@endsection
