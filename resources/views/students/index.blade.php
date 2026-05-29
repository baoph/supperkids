@extends('layouts.app')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="page-title">Học sinh</h4>
        <p class="page-subtitle">Quản lý danh sách học sinh</p>
    </div>
    <a href="{{ route('students.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Thêm học sinh
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Họ tên</th>
                    <th>CCCD</th>
                    <th>Phụ huynh</th>
                    <th>SĐT</th>
                    <th>Lớp</th>
                    <th>Trạng thái</th>
                    <th class="text-end">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td class="fw-semibold">{{ $student->name }}</td>
                        <td style="color:#9ca3af;">{{ $student->cccd ?? '—' }}</td>
                        <td>{{ $student->parent_name }}</td>
                        <td>{{ $student->parent_phone }}</td>
                        <td>
                            @if($student->classes->count())
                                @foreach($student->classes as $cls)
                                    <span class="badge badge-soft-purple me-1">{{ $cls->name }}</span>
                                @endforeach
                            @else
                                <span style="color:#d1d5db;font-style:italic;">Chưa xếp lớp</span>
                            @endif
                        </td>
                        <td>
                            <span class="status-dot status-{{ $student->status === 'studying' ? 'active' : ($student->status === 'new' ? 'new' : 'inactive') }}">
                                {{ $student->status === 'studying' ? 'Đang học' : ($student->status === 'new' ? 'Mới' : 'Nghỉ học') }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group-actions">
                                <a href="{{ route('students.show', $student) }}" class="btn" title="Xem"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('students.edit', $student) }}" class="btn" title="Sửa"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa học sinh này?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-action-danger" title="Xóa"><i class="bi bi-trash3"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="bi bi-people"></i>
                                <p>Chưa có dữ liệu học sinh</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($students->hasPages())
        <div class="card-body border-top d-flex justify-content-between align-items-center">
            <span class="showing-text">Hiển thị {{ $students->firstItem() }}–{{ $students->lastItem() }} / {{ $students->total() }} học sinh</span>
            {{ $students->links() }}
        </div>
    @endif
</div>
@endsection
