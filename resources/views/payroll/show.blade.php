@extends('layouts.app')

@section('title', 'Chi tiết bảng lương')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="bi bi-cash-stack me-2"></i>Chi tiết bảng lương</h2>
            <p class="text-muted mb-0">{{ $payroll->user->name }} - {{ $payroll->user->code }}</p>
        </div>
        <a href="{{ route('payroll.index') }}" class="btn btn-light"><i class="bi bi-arrow-left me-2"></i>Quay lại</a>
    </div>

    <div class="card">
        <div class="card-body">
            <p class="text-muted mb-3">Kỳ: {{ $payroll->period_start->format('d/m/Y') }} - {{ $payroll->period_end->format('d/m/Y') }}</p>
            <table class="table table-bordered">
                <tr><th class="bg-light">Lương cơ bản</th><td class="text-end">{{ number_format($payroll->basic_salary, 0, ',', '.') }}đ</td></tr>
                <tr><th class="bg-light">Phụ cấp</th><td class="text-end">{{ number_format($payroll->allowances, 0, ',', '.') }}đ</td></tr>
                <tr><th class="bg-light">Tổng thu nhập</th><td class="text-end fw-bold">{{ number_format($payroll->gross_salary, 0, ',', '.') }}đ</td></tr>
                <tr><th class="bg-light">BHXH</th><td class="text-end text-danger">-{{ number_format($payroll->social_insurance, 0, ',', '.') }}đ</td></tr>
                <tr><th class="bg-light">BHYT</th><td class="text-end text-danger">-{{ number_format($payroll->health_insurance, 0, ',', '.') }}đ</td></tr>
                <tr><th class="bg-light">BHTN</th><td class="text-end text-danger">-{{ number_format($payroll->unemployment_insurance, 0, ',', '.') }}đ</td></tr>
                <tr><th class="bg-light">Thuế TNCN</th><td class="text-end text-danger">-{{ number_format($payroll->personal_income_tax, 0, ',', '.') }}đ</td></tr>
                <tr><th class="bg-light">Khấu trừ (đi muộn/về sớm)</th><td class="text-end text-danger">-{{ number_format($payroll->deductions, 0, ',', '.') }}đ</td></tr>
                <tr><th class="bg-light">Thực lĩnh</th><td class="text-end fw-bold text-success fs-5">{{ number_format($payroll->net_pay, 0, ',', '.') }}đ</td></tr>
            </table>
            <p class="mb-0 text-muted small">Số ngày làm: {{ number_format($payroll->working_days, 1) }} | Trạng thái: {{ $payroll->status === 'draft' ? 'Nháp' : ($payroll->status === 'confirmed' ? 'Đã xác nhận' : 'Đã thanh toán') }}</p>
        </div>
    </div>
</div>
@endsection
