@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-people me-2 text-primary"></i>Quản lý học sinh</h4>
        <p class="text-muted mb-0 small">Danh sách tất cả học sinh trong hệ thống</p>
    </div>
    <a href="{{ route('students.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Thêm học sinh
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Họ tên</th>
                    <th>CCCD</th>
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
                        <td class="fw-semibold">{{ $student->name }}</td>
                        <td><span class="text-muted">{{ $student->cccd ?? '—' }}</span></td>
                        <td>{{ $student->parent_name }}</td>
                        <td>{{ $student->parent_phone }}</td>
                        <td>
                            @if($student->classes->count())
                                @foreach($student->classes as $class)
                                    <span class="badge text-bg-light border me-1">{{ $class->name }}</span>
                                @endforeach
                            @else
                                <span class="text-muted fst-italic">Chưa xếp lớp</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge text-bg-{{ $student->status === 'studying' ? 'success' : ($student->status === 'new' ? 'info' : 'secondary') }}">
                                {{ $student->status === 'studying' ? 'Đang học' : ($student->status === 'new' ? 'Mới' : 'Nghỉ học') }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('students.show', $student) }}" class="btn btn-outline-info" title="Xem"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('students.edit', $student) }}" class="btn btn-outline-primary" title="Sửa"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa học sinh này?')">
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
                                Chưa có dữ liệu học sinh
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($students->hasPages())
        <div class="card-body border-top">{{ $students->links() }}</div>
    @endif
</div>
@endsection
