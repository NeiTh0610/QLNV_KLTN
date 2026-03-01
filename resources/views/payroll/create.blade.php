@extends('layouts.app')

@section('title', 'Tạo bảng lương')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('payroll.index') }}">Quản lý lương</a></li>
                    <li class="breadcrumb-item active">Tạo bảng lương</li>
                </ol>
            </nav>
            <h2 class="fw-bold mb-1"><i class="bi bi-calculator me-2"></i>Tạo bảng lương</h2>
            <p class="text-muted mb-0">Chọn kỳ lương để hệ thống tính lương theo chấm công và thang lương.</p>
        </div>
        <a href="{{ route('payroll.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Quay lại</a>
    </div>

    <div class="card" style="border-radius: 16px;">
        <div class="card-body p-4">
            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <strong>Có lỗi:</strong>
                    <ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <form method="POST" action="{{ route('payroll.store') }}">
                @csrf
                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">Từ ngày <span class="text-danger">*</span></label>
                        <input type="date" name="period_start" value="{{ old('period_start', now()->startOfMonth()->format('Y-m-d')) }}"
                               class="form-control form-control-lg @error('period_start') is-invalid @enderror" style="border-radius: 12px; max-width: 280px;" required>
                        @error('period_start')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">Đến ngày <span class="text-danger">*</span></label>
                        <input type="date" name="period_end" value="{{ old('period_end', now()->endOfMonth()->format('Y-m-d')) }}"
                               class="form-control form-control-lg @error('period_end') is-invalid @enderror" style="border-radius: 12px; max-width: 280px;" required>
                        @error('period_end')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle me-2"></i>Hệ thống tính lương cho nhân viên đang hoạt động có thang lương, dựa trên chấm công trong kỳ.
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-gradient btn-lg"><i class="bi bi-check-circle me-2"></i>Tạo bảng lương</button>
                    <a href="{{ route('payroll.index') }}" class="btn btn-light btn-lg">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
