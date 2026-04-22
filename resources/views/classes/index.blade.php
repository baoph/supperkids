@extends('layouts.app')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Quản lý lớp học</h5>
        <a href="{{ route('classes.create') }}" class="btn btn-primary btn-sm">+ Thêm lớp học</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Tên lớp</th>
                    <th>Môn</th>
                    <th>Giáo viên</th>
                    <th>Sĩ số</th>
                    <th>Học phí</th>
                    <th>Trạng thái</th>
                    <th class="text-end">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classes as $class)
                    <tr>
                        <td>{{ $class->name }}</td>
                        <td>{{ $class->subject }}</td>
                        <td>{{ $class->teacher->name ?? 'Chưa phân công' }}</td>
                        <td>{{ $class->students->count() }}/{{ $class->capacity }}</td>
                        <td>{{ number_format($class->tuition_fee, 0, ',', '.') }} đ</td>
                        <td><span class="badge text-bg-{{ $class->status === 'open' ? 'success' : 'secondary' }}">{{ $class->status === 'open' ? 'Mở lớp' : 'Đóng lớp' }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('classes.show', $class) }}" class="btn btn-outline-info btn-sm">Xem</a>
                            <a href="{{ route('classes.edit', $class) }}" class="btn btn-outline-primary btn-sm">Sửa</a>
                            <form action="{{ route('classes.destroy', $class) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa lớp học này?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted">Chưa có dữ liệu lớp học.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body">{{ $classes->links() }}</div>
</div>
@endsection
