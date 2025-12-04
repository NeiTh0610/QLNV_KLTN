@extends('layouts.app')

@section('title', 'Sửa đăng ký làm thêm giờ')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-pencil me-2"></i>
                        Sửa đăng ký làm thêm giờ
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('overtime-requests.update', $overtimeRequest) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ngày làm thêm <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" 
                                   value="{{ old('date', $overtimeRequest->date->format('Y-m-d')) }}" min="{{ now()->format('Y-m-d') }}" required style="border-radius: 12px;">
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Giờ bắt đầu <span class="text-danger">*</span></label>
                                <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror" 
                                       value="{{ old('start_time', \Carbon\Carbon::parse($overtimeRequest->start_time)->format('H:i')) }}" required style="border-radius: 12px;">
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Giờ kết thúc <span class="text-danger">*</span></label>
                                <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror" 
                                       value="{{ old('end_time', \Carbon\Carbon::parse($overtimeRequest->end_time)->format('H:i')) }}" required style="border-radius: 12px;">
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Phải sau giờ bắt đầu</small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Lý do (tùy chọn)</label>
                            <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" 
                                      rows="4" style="border-radius: 12px;">{{ old('reason', $overtimeRequest->reason) }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('overtime-requests.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>
                                Quay lại
                            </a>
                            <button type="submit" class="btn btn-gradient">
                                <i class="bi bi-check-circle me-2"></i>
                                Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

