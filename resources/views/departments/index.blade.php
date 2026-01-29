@extends('layouts.app')

@section('title', 'Quản lý phòng ban')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-building me-2"></i>
                Quản lý phòng ban
            </h2>
            <p class="text-muted mb-0">Quản lý thông tin phòng ban trong hệ thống</p>
        </div>
        <a href="{{ route('departments.create') }}" class="btn btn-gradient">
            <i class="bi bi-plus-circle me-2"></i>
            Thêm phòng ban
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('departments.index') }}">
                <div class="row g-3">
                    <div class="col-md-10">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               class="form-control" 
                               placeholder="Tìm kiếm theo tên hoặc mô tả phòng ban..."
                               style="border-radius: 12px; border: 2px solid #e2e8f0;">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-gradient w-100">
                            <i class="bi bi-search me-2"></i>
                            Tìm kiếm
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Departments Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <tr>
                            <th class="py-3 px-4 fw-semibold">Tên phòng ban</th>
                            <th class="py-3 px-4 fw-semibold">Mô tả</th>
                            <th class="py-3 px-4 fw-semibold">Trưởng phòng</th>
                            <th class="py-3 px-4 fw-semibold text-center">Số nhân viên</th>
                            <th class="py-3 px-4 fw-semibold">Ngày tạo</th>
                            <th class="py-3 px-4 fw-semibold text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departments as $department)
                        <tr>
                            <td class="py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <div class="me-3" style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700;">
                                        <i class="bi bi-building"></i>
                                    </div>
                                    <div class="fw-semibold">{{ $department->name }}</div>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="text-muted">{{ $department->description ?? '-' }}</span>
                            </td>
                            <td class="py-3 px-4">
                                @if($department->manager)
                                    <div class="d-flex align-items-center">
                                        <div class="me-2" style="width: 32px; height: 32px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.75rem;">
                                            {{ strtoupper(substr($department->manager->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $department->manager->name }}</div>
                                            <small class="text-muted">{{ $department->manager->code }}</small>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">Chưa có</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="badge bg-info rounded-pill">{{ $department->employee_count ?? 0 }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <small class="text-muted">{{ $department->created_at->format('d/m/Y') }}</small>
                            </td>
                            <td class="py-3 px-4 text-end">
                                <a href="{{ route('departments.show', $department) }}" class="btn btn-sm btn-outline-primary me-1" style="border-radius: 8px;" title="Xem chi tiết">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('departments.edit', $department) }}" class="btn btn-sm btn-outline-success me-1" style="border-radius: 8px;" title="Sửa">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('departments.destroy', $department) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa phòng ban này? Phòng ban phải không có nhân viên mới có thể xóa.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 8px;" title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-inbox text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                                <p class="text-muted mt-3">Không tìm thấy phòng ban nào</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($departments->hasPages())
            <div class="p-4 border-top">
                {{ $departments->links('pagination::bootstrap-5') }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

