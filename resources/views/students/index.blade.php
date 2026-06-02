@extends('layouts.app')

@section('content')
<div class="page-header mb-4">
    <div>
        <h4 class="page-title"><i class="bi bi-people me-2"></i>Quản lý học sinh</h4>
        <p class="page-subtitle">Danh sách tất cả học sinh trong hệ thống</p>
    </div>
    <a href="{{ route('students.create') }}" class="btn btn-primary px-4">
        <i class="bi bi-plus-lg me-1"></i> Thêm học sinh
    </a>
</div>

{{-- Search & Filter --}}
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('students.index') }}" class="row g-2 align-items-end">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Tìm kiếm theo tên hoặc số điện thoại...">
                </div>
            </div>
            <div class="col-md-4">
                <select name="class_id" class="form-select">
                    <option value="">Tất cả lớp</option>
                    @foreach($classes as $cls)
                        <option value="{{ $cls->id }}" @selected(request('class_id') == $cls->id)>{{ $cls->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-funnel me-1"></i> Lọc
                </button>
                @if(request('search') || request('class_id'))
                    <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Họ tên</th>
                    <th>CCCD</th>
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
                        <td class="text-muted">{{ $student->cccd ?? '—' }}</td>
                        <td>{{ $student->parent_phone ?? '—' }}</td>
                        <td>
                            @if($student->classes->count())
                                @foreach($student->classes as $cls)
                                    <span class="badge badge-soft-purple me-1">{{ $cls->name }}</span>
                                @endforeach
                            @else
                                <span class="text-muted fst-italic">Chưa xếp lớp</span>
                            @endif
                        </td>
                        <td>
                            @if($student->status === 'studying')
                                <span class="status-dot status-active"></span> Đang học
                            @elseif($student->status === 'new')
                                <span class="status-dot status-warning"></span> Mới
                            @else
                                <span class="status-dot status-inactive"></span> Nghỉ học
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="btn-group-actions">
                                <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-light" title="Xem"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-light" title="Sửa"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa học sinh này?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-action-danger" title="Xóa"><i class="bi bi-trash3"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="bi bi-people"></i>
                                @if(request('search') || request('class_id'))
                                    <p>Không tìm thấy học sinh phù hợp</p>
                                @else
                                    <p>Chưa có dữ liệu học sinh</p>
                                @endif
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
