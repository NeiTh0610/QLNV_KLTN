@extends('layouts.app')

@section('title', 'Chi tiết bảng lương')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1">
                        <i class="bi bi-receipt me-2"></i>
                        Bảng lương chi tiết
                    </h2>
                    <p class="text-muted mb-0">{{ $payroll->user->name }} - {{ $payroll->user->code }}</p>
                </div>
                <a href="{{ route('payroll.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left me-2"></i>
                    Quay lại
                </a>
            </div>

            <!-- Employee Info -->
            <div class="card mb-4">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-person-badge me-2"></i>
                        Thông tin nhân viên
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block mb-1">Họ tên</small>
                            <h6 class="fw-semibold">{{ $payroll->user->name }}</h6>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block mb-1">Mã nhân viên</small>
                            <h6 class="fw-semibold">{{ $payroll->user->code }}</h6>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block mb-1">Phòng ban</small>
                            <h6 class="fw-semibold">{{ $payroll->user->profile->department->name ?? '-' }}</h6>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block mb-1">Thang lương</small>
                            <h6 class="fw-semibold">{{ $payroll->user->profile->salaryGrade->name ?? '-' }}</h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Salary Details -->
            <div class="card mb-4">
                <div class="card-header" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-calculator me-2"></i>
                        Chi tiết lương
                    </h5>
                </div>
                <div class="card-body p-4">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td class="py-2">
                                    <i class="bi bi-calendar-range text-primary me-2"></i>
                                    Kỳ lương
                                </td>
                                <td class="text-end fw-semibold">
                                    {{ \Carbon\Carbon::parse($payroll->period_start)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($payroll->period_end)->format('d/m/Y') }}
                                </td>
                            </tr>
                            @php
                                $salaryType = $payroll->user->profile->salaryGrade->salary_type ?? 'monthly';
                                $baseSalary = $payroll->user->profile->base_salary_override ?? $payroll->user->profile->salaryGrade->base_salary ?? 0;
                                
                                // Với part-time: Lấy số giờ làm việc trung bình mỗi ngày từ database
                                $hoursPerDay = $salaryType === 'hourly' && $payroll->hours_per_day !== null 
                                    ? $payroll->hours_per_day 
                                    : 0;
                            @endphp
                            <tr>
                                <td class="py-2">
                                    <i class="bi bi-calendar-check text-success me-2"></i>
                                    Số ngày đi làm
                                </td>
                                <td class="text-end fw-semibold">
                                    {{ number_format($payroll->working_days, 1) }} ngày
                                </td>
                            </tr>
                            @if($salaryType === 'hourly' && $hoursPerDay > 0)
                            <tr>
                                <td class="py-2">
                                    <i class="bi bi-clock text-info me-2"></i>
                                    Số giờ làm việc trung bình/ngày
                                </td>
                                <td class="text-end fw-semibold">{{ number_format($hoursPerDay, 1) }} giờ/ngày</td>
                            </tr>
                            @endif
                            <tr class="border-top">
                                <td class="py-2 fw-semibold">
                                    Lương cơ bản
                                    @if($salaryType === 'hourly')
                                        (theo giờ)
                                    @else
                                        (theo tháng)
                                    @endif
                                </td>
                                <td class="text-end fw-semibold">
                                    {{ number_format($baseSalary, 0, ',', '.') }}đ
                                    @if($salaryType === 'hourly')
                                        /giờ
                                    @endif
                                </td>
                            </tr>
                            @if($salaryType === 'monthly')
                            <tr>
                                <td class="py-2">
                                    <i class="bi bi-info-circle text-info me-2"></i>
                                    Lương cơ bản (theo số ngày đi làm: {{ number_format($payroll->working_days, 1) }} ngày)
                                </td>
                                <td class="text-end">{{ number_format($payroll->basic_salary, 0, ',', '.') }}đ</td>
                            </tr>
                            @else
                            <tr>
                                <td class="py-2">
                                    <i class="bi bi-info-circle text-info me-2"></i>
                                    Lương cơ bản ({{ number_format($hoursPerDay, 1) }} giờ/ngày × {{ number_format($payroll->working_days, 1) }} ngày × {{ number_format($baseSalary, 0, ',', '.') }}đ/giờ)
                                </td>
                                <td class="text-end">{{ number_format($payroll->basic_salary, 0, ',', '.') }}đ</td>
                            </tr>
                            @endif
                            <tr>
                                <td class="py-2">
                                    <i class="bi bi-plus-circle text-success me-2"></i>
                                    Phụ cấp
                                </td>
                                <td class="text-end">{{ number_format($payroll->allowances, 0, ',', '.') }}đ</td>
                            </tr>
                            @if($payroll->ot_hours > 0 && $salaryType === 'monthly')
                            @php
                                // Chỉ tính làm thêm cho full-time
                                // Full-time: Tính lương giờ từ lương tháng
                                // Giả sử 1 tháng có 22 ngày làm việc, mỗi ngày 8 giờ
                                $standardWorkingDays = 22;
                                $hourlyRate = $baseSalary > 0 && $standardWorkingDays > 0 
                                    ? $baseSalary / ($standardWorkingDays * 8) 
                                    : 0;
                                $overtimeAmount = $hourlyRate > 0 
                                    ? round($payroll->ot_hours * $hourlyRate * 1.5, 0) 
                                    : 0;
                            @endphp
                            <tr>
                                <td class="py-2">
                                    <i class="bi bi-plus-circle text-success me-2"></i>
                                    Làm thêm ({{ $payroll->ot_hours }}h x 1.5)
                                </td>
                                <td class="text-end text-success">+{{ number_format($overtimeAmount, 0, ',', '.') }}đ</td>
                            </tr>
                            @endif
                            <tr class="border-top">
                                <td class="py-2 fw-semibold">Tổng lương</td>
                                <td class="text-end fw-semibold">{{ number_format($payroll->gross_salary, 0, ',', '.') }}đ</td>
                            </tr>
                            @if($payroll->late_count_under_30 > 0 || $payroll->late_count_half_day > 0)
                            <tr>
                                <td class="py-2 text-warning">
                                    <i class="bi bi-dash-circle me-2"></i>
                                    Phạt đi muộn
                                    @if($payroll->late_count_under_30 > 0)
                                        <small class="d-block text-muted">({{ $payroll->late_count_under_30 }} lần < 30p × 50.000đ)</small>
                                    @endif
                                    @if($payroll->late_count_half_day > 0)
                                        <small class="d-block text-muted">({{ $payroll->late_count_half_day }} lần ≥ 30p × 500.000đ)</small>
                                    @endif
                                </td>
                                <td class="text-end text-warning">
                                    -{{ number_format(($payroll->late_count_under_30 * 50000) + ($payroll->late_count_half_day * 500000), 0, ',', '.') }}đ
                                </td>
                            </tr>
                            @endif
                            @if($payroll->early_leave_count_under_30 > 0 || $payroll->early_leave_count_half_day > 0)
                            <tr>
                                <td class="py-2 text-warning">
                                    <i class="bi bi-dash-circle me-2"></i>
                                    Phạt về sớm
                                    @if($payroll->early_leave_count_under_30 > 0)
                                        <small class="d-block text-muted">({{ $payroll->early_leave_count_under_30 }} lần < 30p × 50.000đ)</small>
                                    @endif
                                    @if($payroll->early_leave_count_half_day > 0)
                                        <small class="d-block text-muted">({{ $payroll->early_leave_count_half_day }} lần ≥ 30p × 500.000đ)</small>
                                    @endif
                                </td>
                                <td class="text-end text-warning">
                                    -{{ number_format(($payroll->early_leave_count_under_30 * 50000) + ($payroll->early_leave_count_half_day * 500000), 0, ',', '.') }}đ
                                </td>
                            </tr>
                            @endif
                            @if($salaryType === 'monthly')
                            <tr class="border-top">
                                <td class="py-2 text-danger">
                                    <i class="bi bi-dash-circle me-2"></i>
                                    BHXH (8%)
                                </td>
                                <td class="text-end text-danger">-{{ number_format($payroll->social_insurance, 0, ',', '.') }}đ</td>
                            </tr>
                            <tr>
                                <td class="py-2 text-danger">
                                    <i class="bi bi-dash-circle me-2"></i>
                                    BHYT (1.5%)
                                </td>
                                <td class="text-end text-danger">-{{ number_format($payroll->health_insurance, 0, ',', '.') }}đ</td>
                            </tr>
                            <tr>
                                <td class="py-2 text-danger">
                                    <i class="bi bi-dash-circle me-2"></i>
                                    BHTN (1%)
                                </td>
                                <td class="text-end text-danger">-{{ number_format($payroll->unemployment_insurance, 0, ',', '.') }}đ</td>
                            </tr>
                            <tr>
                                <td class="py-2 text-danger">
                                    <i class="bi bi-dash-circle me-2"></i>
                                    Thuế TNCN
                                </td>
                                <td class="text-end text-danger">-{{ number_format($payroll->personal_income_tax, 0, ',', '.') }}đ</td>
                            </tr>
                            @endif
                            <tr class="border-top">
                                <td class="py-3">
                                    <h5 class="mb-0 fw-bold">Thực lĩnh</h5>
                                </td>
                                <td class="text-end">
                                    <h4 class="mb-0 fw-bold text-success">{{ number_format($payroll->net_pay, 0, ',', '.') }}đ</h4>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Status Update -->
            <div class="card">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Cập nhật trạng thái</h6>
                    <form method="POST" action="{{ route('payroll.update-status', $payroll) }}">
                        @csrf
                        <div class="row align-items-end g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Trạng thái</label>
                                <select name="status" class="form-select" style="border-radius: 12px;">
                                    <option value="draft" {{ $payroll->status === 'draft' ? 'selected' : '' }}>Nháp</option>
                                    <option value="confirmed" {{ $payroll->status === 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                    <option value="paid" {{ $payroll->status === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-gradient w-100">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Cập nhật
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



