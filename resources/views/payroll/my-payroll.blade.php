@extends('layouts.app')

@section('title', 'Bảng lương của tôi')

@section('content')
<div class="container-fluid">
    <h2 class="fw-bold mb-1"><i class="bi bi-wallet2 me-2"></i>Bảng lương của tôi</h2>
    <p class="text-muted mb-4">Xem bảng lương theo tháng</p>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('payroll.my-payroll') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-auto">
                        <label class="form-label mb-0">Tháng</label>
                        <input type="month" name="month" value="{{ $month }}" class="form-control" style="max-width: 180px;">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-gradient">Xem</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @forelse($payrolls as $payroll)
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h5 class="fw-bold mb-1">Tháng {{ $payroll->period_start->format('m/Y') }}</h5>
                    <p class="text-muted small mb-0">Kỳ: {{ $payroll->period_start->format('d/m/Y') }} - {{ $payroll->period_end->format('d/m/Y') }}</p>
                </div>
                <div class="text-end">
                    <span class="badge {{ $payroll->status === 'paid' ? 'bg-success' : ($payroll->status === 'confirmed' ? 'bg-primary' : 'bg-secondary') }}">
                        {{ $payroll->status === 'draft' ? 'Nháp' : ($payroll->status === 'confirmed' ? 'Đã xác nhận' : 'Đã thanh toán') }}
                    </span>
                    <h4 class="fw-bold text-success mt-2 mb-0">{{ number_format($payroll->net_pay, 0, ',', '.') }}đ</h4>
                </div>
            </div>
            <p class="mb-0 mt-2 small text-muted">Số ngày làm: {{ number_format($payroll->working_days, 1) }}</p>
        </div>
    </div>
    @empty
    <div class="card">
        <div class="card-body text-center py-5">
            <p class="text-muted">Chưa có bảng lương nào. Bảng lương sẽ được cập nhật khi quản lý tạo.</p>
        </div>
    </div>
    @endforelse
</div>
@endsection
