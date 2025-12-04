@extends('layouts.app')

@section('title', 'Chi tiết hồ sơ nhân viên')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1">
                        <i class="bi bi-file-person me-2"></i>
                        Hồ sơ: {{ $employee->name }}
                    </h2>
                    <p class="text-muted mb-0">Mã NV: {{ $employee->code }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('employee-profiles.edit', $employee) }}" class="btn btn-gradient">
                        <i class="bi bi-pencil me-2"></i>
                        Sửa hồ sơ
                    </a>
                    <a href="{{ route('employee-profiles.index') }}" class="btn btn-light">
                        <i class="bi bi-arrow-left me-2"></i>
                        Quay lại
                    </a>
                </div>
            </div>

            <div class="row g-4">
                <!-- Thông tin cá nhân -->
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">
                                <i class="bi bi-person-badge me-2"></i>
                                Thông tin cá nhân
                            </h5>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">CMND/CCCD</small>
                                <h6 class="fw-semibold mb-0">{{ $employee->profile->id_number ?? '-' }}</h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Ngày sinh</small>
                                <h6 class="fw-semibold mb-0">{{ $employee->profile->date_of_birth ? $employee->profile->date_of_birth->format('d/m/Y') : '-' }}</h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Giới tính</small>
                                <h6 class="fw-semibold mb-0">
                                    @if($employee->profile->gender)
                                        {{ $employee->profile->gender === 'male' ? 'Nam' : ($employee->profile->gender === 'female' ? 'Nữ' : 'Khác') }}
                                    @else
                                        -
                                    @endif
                                </h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Địa chỉ</small>
                                <h6 class="fw-semibold mb-0">{{ $employee->profile->address ?? '-' }}</h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Email cá nhân</small>
                                <h6 class="fw-semibold mb-0">{{ $employee->profile->personal_email ?? '-' }}</h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Liên hệ khẩn cấp</small>
                                <h6 class="fw-semibold mb-0">
                                    {{ $employee->profile->emergency_contact_name ?? '-' }}
                                    @if($employee->profile->emergency_contact_phone)
                                        <br><small class="text-muted">{{ $employee->profile->emergency_contact_phone }}</small>
                                    @endif
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Học vấn & Kinh nghiệm -->
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">
                                <i class="bi bi-mortarboard me-2"></i>
                                Học vấn & Kinh nghiệm
                            </h5>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Trình độ học vấn</small>
                                <h6 class="fw-semibold mb-0">{{ $employee->profile->education_level ?? '-' }}</h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Chuyên ngành</small>
                                <h6 class="fw-semibold mb-0">{{ $employee->profile->major ?? '-' }}</h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Trường đại học</small>
                                <h6 class="fw-semibold mb-0">{{ $employee->profile->university ?? '-' }}</h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Số năm kinh nghiệm</small>
                                <h6 class="fw-semibold mb-0">{{ $employee->profile->years_of_experience ?? 0 }} năm</h6>
                            </div>
                            @if($employee->profile->skills)
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Kỹ năng</small>
                                <p class="mb-0">{{ $employee->profile->skills }}</p>
                            </div>
                            @endif
                            @if($employee->profile->certifications)
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Chứng chỉ</small>
                                <p class="mb-0">{{ $employee->profile->certifications }}</p>
                            </div>
                            @endif
                            @if($employee->profile->previous_work)
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Công việc trước đây</small>
                                <p class="mb-0">{{ $employee->profile->previous_work }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Hợp đồng -->
                @if($employee->contracts->count() > 0)
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">
                                <i class="bi bi-file-earmark-text me-2"></i>
                                Hợp đồng
                            </h5>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Số hợp đồng</th>
                                            <th>Loại</th>
                                            <th>Ngày bắt đầu</th>
                                            <th>Ngày kết thúc</th>
                                            <th>Lương</th>
                                            <th>Trạng thái</th>
                                            <th class="text-end">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($employee->contracts as $contract)
                                        <tr>
                                            <td>{{ $contract->contract_number }}</td>
                                            <td>
                                                @if($contract->contract_type === 'full_time')
                                                    <span class="badge bg-primary">Chính thức</span>
                                                @elseif($contract->contract_type === 'part_time')
                                                    <span class="badge bg-info">Part-time</span>
                                                @elseif($contract->contract_type === 'intern')
                                                    <span class="badge bg-warning">Thực tập</span>
                                                @else
                                                    <span class="badge bg-secondary">Tạm thời</span>
                                                @endif
                                            </td>
                                            <td>{{ $contract->start_date->format('d/m/Y') }}</td>
                                            <td>{{ $contract->end_date ? $contract->end_date->format('d/m/Y') : 'Không xác định' }}</td>
                                            <td>{{ number_format($contract->salary, 0, ',', '.') }}đ</td>
                                            <td>
                                                @if($contract->status === 'active')
                                                    <span class="badge bg-success">Đang hiệu lực</span>
                                                @elseif($contract->status === 'expired')
                                                    <span class="badge bg-warning">Hết hạn</span>
                                                @elseif($contract->status === 'terminated')
                                                    <span class="badge bg-danger">Chấm dứt</span>
                                                @else
                                                    <span class="badge bg-secondary">Chờ duyệt</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('contracts.show', $contract) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

