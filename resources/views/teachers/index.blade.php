@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-person-workspace me-2 text-primary"></i>Quản lý giáo viên</h4>
        <p class="text-muted mb-0 small">Danh sách tất cả giáo viên trong hệ thống</p>
    </div>
    <a href="{{ route('teachers.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Thêm giáo viên
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
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
                        <td><span class="badge text-bg-light border">{{ $teacher->specialization }}</span></td>
                        <td>
                            <div>{{ $teacher->phone }}</div>
                            <small class="text-muted">{{ $teacher->email }}</small>
                        </td>
                        <td><span class="badge text-bg-primary">{{ $teacher->classes_count }}</span></td>
                        <td class="fw-semibold">{{ number_format($teacher->salary, 0, ',', '.') }} đ</td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('teachers.show', $teacher) }}" class="btn btn-outline-info" title="Xem"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-outline-primary" title="Sửa"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa giáo viên này?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm" title="Xóa"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Chưa có giáo viên
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($teachers->hasPages())
        <div class="card-body border-top">{{ $teachers->links() }}</div>
    @endif
</div>
@endsection
