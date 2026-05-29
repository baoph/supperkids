@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-collection me-2 text-primary"></i>Quản lý lớp học</h4>
        <p class="text-muted mb-0 small">Danh sách tất cả lớp học trong hệ thống</p>
    </div>
    <a href="{{ route('classes.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Thêm lớp học
    </a>
</div>

<div class="card">
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
                        <td class="fw-semibold">{{ $class->name }}</td>
                        <td><span class="badge text-bg-light border">{{ $class->subject }}</span></td>
                        <td>{{ $class->teacher->name ?? '<span class="text-muted fst-italic">Chưa phân công</span>' }}</td>
                        <td>
                            <span class="fw-semibold">{{ $class->students->count() }}</span><span class="text-muted">/{{ $class->capacity }}</span>
                        </td>
                        <td class="fw-semibold">{{ number_format($class->tuition_fee, 0, ',', '.') }} đ</td>
                        <td><span class="badge text-bg-{{ $class->status === 'open' ? 'success' : 'secondary' }}">{{ $class->status === 'open' ? 'Mở lớp' : 'Đóng lớp' }}</span></td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('classes.show', $class) }}" class="btn btn-outline-info" title="Xem"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('classes.edit', $class) }}" class="btn btn-outline-primary" title="Sửa"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('classes.destroy', $class) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa lớp học này?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm" title="Xóa"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Chưa có dữ liệu lớp học
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($classes->hasPages())
        <div class="card-body border-top">{{ $classes->links() }}</div>
    @endif
</div>
@endsection
