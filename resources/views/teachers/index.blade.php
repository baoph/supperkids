@extends('layouts.app')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="page-title">Giáo viên</h4>
        <p class="page-subtitle">Quản lý danh sách giáo viên</p>
    </div>
    <a href="{{ route('teachers.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Thêm giáo viên
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Họ tên</th>
                    <th>Chuyên môn</th>
                    <th>Liên hệ</th>
                    <th>Số lớp</th>
                    <th>Lương</th>
                    <th class="text-end">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teachers as $teacher)
                    <tr>
                        <td class="fw-semibold">{{ $teacher->name }}</td>
                        <td><span class="badge badge-soft-teal">{{ $teacher->specialization }}</span></td>
                        <td>
                            <div>{{ $teacher->phone }}</div>
                            <small style="color:#9ca3af;">{{ $teacher->email }}</small>
                        </td>
                        <td><span class="badge badge-soft-purple">{{ $teacher->classes_count }}</span></td>
                        <td class="fw-semibold">{{ number_format($teacher->salary, 0, ',', '.') }} đ</td>
                        <td class="text-end">
                            <div class="btn-group-actions">
                                <a href="{{ route('teachers.show', $teacher) }}" class="btn" title="Xem"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('teachers.edit', $teacher) }}" class="btn" title="Sửa"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa giáo viên này?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-action-danger" title="Xóa"><i class="bi bi-trash3"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="bi bi-person-workspace"></i>
                                <p>Chưa có giáo viên</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($teachers->hasPages())
        <div class="card-body border-top d-flex justify-content-between align-items-center">
            <span class="showing-text">Hiển thị {{ $teachers->firstItem() }}–{{ $teachers->lastItem() }} / {{ $teachers->total() }}</span>
            {{ $teachers->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
