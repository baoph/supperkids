@extends('layouts.app')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><h5 class="mb-0">Cập nhật lớp học</h5></div>
    <div class="card-body">
        <form action="{{ route('classes.update', $class) }}" method="POST">
            @method('PUT')
            @include('classes._form')
        </form>
    </div>
</div>
@endsection
