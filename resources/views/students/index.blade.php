@extends('layouts.app')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Quản lý học sinh</h5>
        <a href="{{ route('students.create') }}" class="btn btn-primary btn-sm">+ Thêm học sinh</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Họ tên</th>
                    <th>Phụ huynh</th>
                    <th>SĐT</th>
                    <th>Lớp đang học</th>
                    <th>Trạng thái</th>
                    <th class="text-end">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->parent_name }}</td>
                        <td>{{ $student->parent_phone }}</td>
                        <td>{{ $student->classes->pluck('name')->join(', ') ?: 'Chưa xếp lớp' }}</td>
                        <td>{{ $student->status }}</td>
                        <td class="text-end">
                            <a href="{{ route('students.show', $student) }}" class="btn btn-outline-info btn-sm">Xem</a>
                            <a href="{{ route('students.edit', $student) }}" class="btn btn-outline-primary btn-sm">Sửa</a>
                            <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa học sinh này?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Chưa có dữ liệu học sinh.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body">{{ $students->links() }}</div>
</div>
@endsection
