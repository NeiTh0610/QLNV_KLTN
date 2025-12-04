@extends('layouts.app')

@section('title', 'Chi tiết hợp đồng')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1">
                        <i class="bi bi-file-earmark-text me-2"></i>
                        Hợp đồng: {{ $contract->contract_number }}
                    </h2>
                    <p class="text-muted mb-0">Nhân viên: {{ $contract->user->name }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('contracts.edit', $contract) }}" class="btn btn-gradient">
                        <i class="bi bi-pencil me-2"></i>
                        Sửa
                    </a>
                    <a href="{{ route('contracts.index') }}" class="btn btn-light">
                        <i class="bi bi-arrow-left me-2"></i>
                        Quay lại
                    </a>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">
                                <i class="bi bi-info-circle me-2"></i>
                                Thông tin hợp đồng
                            </h5>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Số hợp đồng</small>
                                <h6 class="fw-semibold mb-0">{{ $contract->contract_number }}</h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Nhân viên</small>
                                <h6 class="fw-semibold mb-0">{{ $contract->user->name }} ({{ $contract->user->code }})</h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Loại hợp đồng</small>
                                <h6 class="fw-semibold mb-0">
                                    @if($contract->contract_type === 'full_time')
                                        <span class="badge bg-primary">Chính thức</span>
                                    @elseif($contract->contract_type === 'part_time')
                                        <span class="badge bg-info">Part-time</span>
                                    @elseif($contract->contract_type === 'intern')
                                        <span class="badge bg-warning">Thực tập</span>
                                    @else
                                        <span class="badge bg-secondary">Tạm thời</span>
                                    @endif
                                </h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Chức vụ</small>
                                <h6 class="fw-semibold mb-0">{{ $contract->position }}</h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Trạng thái</small>
                                <h6 class="fw-semibold mb-0">
                                    @if($contract->status === 'active')
                                        <span class="badge bg-success">Đang hiệu lực</span>
                                    @elseif($contract->status === 'expired')
                                        <span class="badge bg-warning">Hết hạn</span>
                                    @elseif($contract->status === 'terminated')
                                        <span class="badge bg-danger">Chấm dứt</span>
                                    @else
                                        <span class="badge bg-secondary">Chờ duyệt</span>
                                    @endif
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">
                                <i class="bi bi-calendar me-2"></i>
                                Thời hạn & Lương
                            </h5>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Ngày bắt đầu</small>
                                <h6 class="fw-semibold mb-0">{{ $contract->start_date->format('d/m/Y') }}</h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Ngày kết thúc</small>
                                <h6 class="fw-semibold mb-0">{{ $contract->end_date ? $contract->end_date->format('d/m/Y') : 'Không xác định' }}</h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Ngày ký</small>
                                <h6 class="fw-semibold mb-0">{{ $contract->signed_date ? $contract->signed_date->format('d/m/Y') : '-' }}</h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Mức lương</small>
                                <h6 class="fw-semibold mb-0 text-success">{{ number_format($contract->salary, 0, ',', '.') }}đ</h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Người tạo</small>
                                <h6 class="fw-semibold mb-0">{{ $contract->creator->name ?? '-' }}</h6>
                            </div>
                        </div>
                    </div>
                </div>

                @if($contract->job_description)
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">
                                <i class="bi bi-file-text me-2"></i>
                                Mô tả công việc
                            </h5>
                            <p class="mb-0">{{ $contract->job_description }}</p>
                        </div>
                    </div>
                </div>
                @endif

                @if($contract->benefits)
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">
                                <i class="bi bi-gift me-2"></i>
                                Phúc lợi
                            </h5>
                            <p class="mb-0">{{ $contract->benefits }}</p>
                        </div>
                    </div>
                </div>
                @endif

                @if($contract->notes)
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">
                                <i class="bi bi-sticky me-2"></i>
                                Ghi chú
                            </h5>
                            <p class="mb-0">{{ $contract->notes }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

