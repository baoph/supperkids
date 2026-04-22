@extends('layouts.app')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><h5 class="mb-0">Thêm học sinh</h5></div>
    <div class="card-body">
        <form action="{{ route('students.store') }}" method="POST">
            @include('students._form')
        </form>
    </div>
</div>
@endsection
