@extends('layouts.app')

@section('title', 'Quản lý lương')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-cash-stack me-2"></i>
                Quản lý lương
            </h2>
            <p class="text-muted mb-0">Quản lý bảng lương nhân viên</p>
        </div>
        <button type="button" class="btn btn-gradient" data-bs-toggle="modal" data-bs-target="#generateModal">
            <i class="bi bi-plus-circle me-2"></i>
            Tạo bảng lương
        </button>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('payroll.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tìm kiếm</label>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               class="form-control" 
                               placeholder="Tên, mã NV, email..."
                               style="border-radius: 12px;">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Tháng</label>
                        <input type="month" name="month" value="{{ $month }}" class="form-control" style="border-radius: 12px;">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Phòng ban</label>
                        <select name="department_id" class="form-select" style="border-radius: 12px;">
                            <option value="">Tất cả phòng ban</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Trạng thái</label>
                        <select name="status" class="form-select" style="border-radius: 12px;">
                            <option value="">Tất cả</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Nháp</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-gradient flex-fill">
                                <i class="bi bi-search me-2"></i>
                                Tìm kiếm
                            </button>
                            <a href="{{ route('payroll.export', request()->all()) }}" class="btn btn-success" title="Xuất Excel">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label fw-semibold">&nbsp;</label>
                        <a href="{{ route('payroll.index', ['month' => $month]) }}" class="btn btn-outline-secondary w-100" title="Xóa bộ lọc">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Payroll Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <tr>
                            <th class="py-3 px-4 fw-semibold">Mã NV</th>
                            <th class="py-3 px-4 fw-semibold">Họ tên</th>
                            <th class="py-3 px-4 fw-semibold">Phòng ban</th>
                            <th class="py-3 px-4 fw-semibold text-end">Lương cơ bản</th>
                            <th class="py-3 px-4 fw-semibold text-end">Thực lĩnh</th>
                            <th class="py-3 px-4 fw-semibold text-center">Trạng thái</th>
                            <th class="py-3 px-4 fw-semibold text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payrolls as $payroll)
                        <tr>
                            <td class="py-3 px-4 fw-semibold">{{ $payroll->user->code }}</td>
                            <td class="py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <div class="me-3" style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700;">
                                        {{ strtoupper(substr($payroll->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $payroll->user->name }}</div>
                                        <small class="text-muted">{{ $payroll->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4">{{ $payroll->user->profile->department->name ?? '-' }}</td>
                            <td class="py-3 px-4 text-end fw-semibold">
                                @php
                                    $baseSalary = $payroll->user->profile->base_salary_override ?? $payroll->user->profile->salaryGrade->base_salary ?? 0;
                                    $salaryType = $payroll->user->profile->salaryGrade->salary_type ?? 'monthly';
                                @endphp
                                {{ number_format($baseSalary, 0, ',', '.') }}đ
                                @if($salaryType === 'hourly')
                                    <small class="text-muted d-block">/giờ</small>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-end">
                                <div class="fw-bold text-success">{{ number_format($payroll->net_pay, 0, ',', '.') }}đ</div>
                                <small class="text-muted">
                                    {{ number_format($payroll->working_days, 1) }} ngày
                                </small>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($payroll->status === 'draft')
                                    <span class="badge bg-secondary">Nháp</span>
                                @elseif($payroll->status === 'confirmed')
                                    <span class="badge bg-primary">Đã xác nhận</span>
                                @else
                                    <span class="badge" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">Đã thanh toán</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-end">
                                <a href="{{ route('payroll.show', $payroll) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($payroll->status === 'draft')
                                <div class="btn-group btn-group-sm ms-1">
                                    <form action="{{ route('payroll.update-status', $payroll) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="btn btn-outline-success" style="border-radius: 8px;">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                </div>
                                @elseif($payroll->status === 'confirmed')
                                <div class="btn-group btn-group-sm ms-1">
                                    <form action="{{ route('payroll.update-status', $payroll) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="paid">
                                        <button type="submit" class="btn btn-outline-success" style="border-radius: 8px;">
                                            <i class="bi bi-cash"></i>
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-inbox text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                                <p class="text-muted mt-3">Chưa có bảng lương nào</p>
                                <button type="button" class="btn btn-gradient mt-2" data-bs-toggle="modal" data-bs-target="#generateModal">
                                    <i class="bi bi-plus-circle me-2"></i>
                                    Tạo bảng lương
                                </button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($payrolls->hasPages())
            <div class="p-4 border-top">
                {{ $payrolls->links('pagination::bootstrap-5') }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Generate Payroll Modal -->
<div class="modal fade" id="generateModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-calculator me-2"></i>
                    Tạo bảng lương
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('payroll.generate') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Từ ngày <span class="text-danger">*</span></label>
                        <input type="date" name="period_start" value="{{ now()->startOfMonth()->format('Y-m-d') }}" class="form-control" style="border-radius: 12px;" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Đến ngày <span class="text-danger">*</span></label>
                        <input type="date" name="period_end" value="{{ now()->endOfMonth()->format('Y-m-d') }}" class="form-control" style="border-radius: 12px;" required>
                    </div>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <small>Hệ thống sẽ tự động tính lương dựa trên dữ liệu chấm công và thang lương</small>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-gradient">
                        <i class="bi bi-check-circle me-2"></i>
                        Tạo bảng lương
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

