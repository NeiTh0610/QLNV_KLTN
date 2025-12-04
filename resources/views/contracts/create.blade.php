@extends('layouts.app')

@section('title', 'Tạo hợp đồng mới')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="mb-4">
                <h2 class="fw-bold mb-1">
                    <i class="bi bi-plus-circle me-2"></i>
                    Tạo hợp đồng mới
                </h2>
                <p class="text-muted mb-0">Thêm hợp đồng lao động cho nhân viên</p>
            </div>

            <form method="POST" action="{{ route('contracts.store') }}">
                @csrf

                <div class="card mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-person-badge me-2"></i>
                            Thông tin cơ bản
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nhân viên <span class="text-danger">*</span></label>
                                <select name="user_id" class="form-select" required>
                                    <option value="">Chọn nhân viên</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}" {{ old('user_id') == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->name }} ({{ $emp->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Loại hợp đồng <span class="text-danger">*</span></label>
                                <select name="contract_type" class="form-select" required>
                                    <option value="">Chọn loại</option>
                                    <option value="full_time" {{ old('contract_type') == 'full_time' ? 'selected' : '' }}>Chính thức</option>
                                    <option value="part_time" {{ old('contract_type') == 'part_time' ? 'selected' : '' }}>Part-time</option>
                                    <option value="intern" {{ old('contract_type') == 'intern' ? 'selected' : '' }}>Thực tập</option>
                                    <option value="temporary" {{ old('contract_type') == 'temporary' ? 'selected' : '' }}>Tạm thời</option>
                                </select>
                                @error('contract_type')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Ngày bắt đầu <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" value="{{ old('start_date') }}" class="form-control" required>
                                @error('start_date')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Ngày kết thúc</label>
                                <input type="date" name="end_date" value="{{ old('end_date') }}" class="form-control">
                                <small class="text-muted">Để trống nếu không xác định</small>
                                @error('end_date')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Mức lương <span class="text-danger">*</span></label>
                                <input type="number" name="salary" value="{{ old('salary') }}" class="form-control" min="0" step="1000" required>
                                @error('salary')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Chức vụ <span class="text-danger">*</span></label>
                                <input type="text" name="position" value="{{ old('position') }}" class="form-control" required>
                                @error('position')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Ngày ký</label>
                                <input type="date" name="signed_date" value="{{ old('signed_date') }}" class="form-control">
                                @error('signed_date')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Trạng thái <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Đang hiệu lực</option>
                                    <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Hết hạn</option>
                                    <option value="terminated" {{ old('status') == 'terminated' ? 'selected' : '' }}>Chấm dứt</option>
                                </select>
                                @error('status')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-file-text me-2"></i>
                            Chi tiết hợp đồng
                        </h5>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Mô tả công việc</label>
                                <textarea name="job_description" class="form-control" rows="4">{{ old('job_description') }}</textarea>
                                @error('job_description')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Phúc lợi</label>
                                <textarea name="benefits" class="form-control" rows="4">{{ old('benefits') }}</textarea>
                                @error('benefits')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Ghi chú</label>
                                <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                                @error('notes')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('contracts.index') }}" class="btn btn-light">Hủy</a>
                    <button type="submit" class="btn btn-gradient">
                        <i class="bi bi-check-circle me-2"></i>
                        Tạo hợp đồng
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

