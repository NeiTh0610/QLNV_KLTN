@extends('layouts.app')

@section('title', 'Chi tiết phòng ban')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1">
                    <i class="bi bi-building me-2"></i>
                    {{ $department->name }}
                </h2>
                <p class="text-muted mb-0">Thông tin chi tiết phòng ban</p>
            </div>
            <div>
                <a href="{{ route('departments.edit', $department) }}" class="btn btn-gradient me-2">
                    <i class="bi bi-pencil me-2"></i>
                    Sửa
                </a>
                <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>
                    Quay lại
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Department Info Card -->
            <div class="card mb-4">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-info-circle me-2"></i>
                        Thông tin phòng ban
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold text-muted">Tên phòng ban</label>
                            <div class="fs-5 fw-bold">{{ $department->name }}</div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold text-muted">Mô tả</label>
                            <div>{{ $department->description ?? '-' }}</div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold text-muted">Trưởng phòng</label>
                            @if($department->manager)
                                <div class="d-flex align-items-center">
                                    <div class="me-3" style="width: 48px; height: 48px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.2rem;">
                                        {{ strtoupper(substr($department->manager->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $department->manager->name }}</div>
                                        <small class="text-muted">{{ $department->manager->code }} - {{ $department->manager->email }}</small>
                                    </div>
                                </div>
                            @else
                                <div class="text-muted">Chưa có trưởng phòng</div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Số nhân viên</label>
                            <div class="fs-4 fw-bold">
                                <span class="badge bg-info rounded-pill" style="font-size: 1rem; padding: 0.5rem 1rem;">
                                    {{ $employeeCount }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Ngày tạo</label>
                            <div>{{ $department->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Statistics Card -->
            <div class="card mb-4">
                <div class="card-header" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-bar-chart me-2"></i>
                        Thống kê
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center py-3">
                        <div class="display-4 fw-bold text-primary mb-2">{{ $employeeCount }}</div>
                        <div class="text-muted">Nhân viên</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

