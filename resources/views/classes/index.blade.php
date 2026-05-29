@extends('layouts.app')

@section('content')
<div class="page-header mb-4">
    <div>
        <h4 class="page-title"><i class="bi bi-collection me-2"></i>Quản lý lớp học</h4>
        <p class="page-subtitle">Danh sách tất cả lớp học trong hệ thống</p>
    </div>
    <a href="{{ route('classes.create') }}" class="btn btn-primary px-4">
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
                        <td><span class="badge-soft-purple">{{ $class->subject }}</span></td>
                        <td>{{ $class->teacher->name ?? '—' }}</td>
                        <td>
                            <span class="fw-semibold">{{ $class->students->count() }}</span><span class="text-muted">/{{ $class->capacity }}</span>
                        </td>
                        <td class="fw-semibold">{{ number_format($class->tuition_fee, 0, ',', '.') }} đ</td>
                        <td>
                            @if($class->status === 'open')
                                <span class="status-dot status-active"></span> Mở lớp
                            @else
                                <span class="status-dot status-inactive"></span> Đóng lớp
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="btn-group-actions">
                                <a href="{{ route('classes.show', $class) }}" class="btn btn-sm btn-light" title="Xem"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('classes.edit', $class) }}" class="btn btn-sm btn-light" title="Sửa"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('classes.destroy', $class) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa lớp học này?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-action-danger" title="Xóa"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="bi bi-collection"></i>
                                <p>Chưa có dữ liệu lớp học</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($classes->hasPages())
        <div class="card-body border-top d-flex justify-content-between align-items-center">
            <span class="showing-text">Hiển thị {{ $classes->firstItem() }}–{{ $classes->lastItem() }} / {{ $classes->total() }} lớp học</span>
            {{ $classes->links() }}
        </div>
    @endif
</div>
@endsection
