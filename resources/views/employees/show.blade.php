@extends('layouts.app')

@section('title', 'Chi tiết nhân viên')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1">
                        <i class="bi bi-person-circle me-2"></i>
                        {{ $employee->name }}
                    </h2>
                    <p class="text-muted mb-0">Mã NV: {{ $employee->code }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-gradient">
                        <i class="bi bi-pencil me-2"></i>
                        Sửa
                    </a>
                    <a href="{{ route('employees.index') }}" class="btn btn-light">
                        <i class="bi bi-arrow-left me-2"></i>
                        Quay lại
                    </a>
                </div>
            </div>

            <div class="row g-4">
                <!-- Basic Info -->
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">
                                <i class="bi bi-person-badge me-2"></i>
                                Thông tin cơ bản
                            </h5>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Họ tên</small>
                                <h6 class="fw-semibold mb-0">{{ $employee->name }}</h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Email</small>
                                <h6 class="fw-semibold mb-0">{{ $employee->email }}</h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Số điện thoại</small>
                                <h6 class="fw-semibold mb-0">{{ $employee->phone ?? '-' }}</h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Trạng thái</small>
                                @if($employee->status === 'active')
                                    <span class="badge badge-custom" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
                                        <i class="bi bi-check-circle me-1"></i>Đang làm việc
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-x-circle me-1"></i>Nghỉ việc
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Work Info -->
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">
                                <i class="bi bi-briefcase me-2"></i>
                                Thông tin công việc
                            </h5>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Phòng ban</small>
                                <h6 class="fw-semibold mb-0">{{ $employee->profile->department->name ?? '-' }}</h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Chức vụ</small>
                                <h6 class="fw-semibold mb-0">{{ $employee->profile->position ?? '-' }}</h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Vai trò</small>
                                <div>
                                    @foreach($employee->roles as $role)
                                        <span class="badge badge-custom" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                                            {{ $role->display_name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Ngày vào làm</small>
                                <h6 class="fw-semibold mb-0">{{ $employee->hired_at?->format('d/m/Y') ?? '-' }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
