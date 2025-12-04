@extends('layouts.app')

@section('title', 'Đăng ký làm thêm giờ')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-clock-history me-2"></i>
                        Đăng ký làm thêm giờ
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('overtime-requests.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ngày làm thêm <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" 
                                   value="{{ old('date') }}" min="{{ now()->format('Y-m-d') }}" required style="border-radius: 12px;">
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Giờ bắt đầu <span class="text-danger">*</span></label>
                                <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror" 
                                       value="{{ old('start_time') }}" required style="border-radius: 12px;">
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Giờ kết thúc <span class="text-danger">*</span></label>
                                <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror" 
                                       value="{{ old('end_time') }}" required style="border-radius: 12px;">
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Phải sau giờ bắt đầu</small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Lý do (tùy chọn)</label>
                            <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" 
                                      rows="4" style="border-radius: 12px;">{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Lưu ý:</strong> Đăng ký làm thêm giờ phải được quản lý duyệt trước khi được tính vào lương. 
                            Nếu không đăng ký, giờ làm thêm sẽ không được tính tiền.
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('overtime-requests.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>
                                Quay lại
                            </a>
                            <button type="submit" class="btn btn-gradient">
                                <i class="bi bi-check-circle me-2"></i>
                                Đăng ký
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

