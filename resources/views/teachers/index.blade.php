@extends('layouts.app')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Quản lý giáo viên</h5>
        <a href="{{ route('teachers.create') }}" class="btn btn-primary btn-sm">+ Thêm giáo viên</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Họ tên</th>
                    <th>Chuyên môn</th>
                    <th>Liên hệ</th>
                    <th>Số lớp phụ trách</th>
                    <th>Lương</th>
                    <th class="text-end">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teachers as $teacher)
                    <tr>
                        <td>{{ $teacher->name }}</td>
                        <td>{{ $teacher->specialization }}</td>
                        <td>{{ $teacher->phone }}<br><small>{{ $teacher->email }}</small></td>
                        <td>{{ $teacher->classes_count }}</td>
                        <td>{{ number_format($teacher->salary, 0, ',', '.') }} đ</td>
                        <td class="text-end">
                            <a href="{{ route('teachers.show', $teacher) }}" class="btn btn-outline-info btn-sm">Xem</a>
                            <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-outline-primary btn-sm">Sửa</a>
                            <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa giáo viên này?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Chưa có giáo viên.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body">{{ $teachers->links() }}</div>
</div>
@endsection
