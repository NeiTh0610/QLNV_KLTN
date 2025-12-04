@extends('layouts.app')

@section('title', 'Thêm nhân viên')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Page Header -->
            <div class="mb-4">
                <h2 class="fw-bold mb-1">
                    <i class="bi bi-person-plus me-2"></i>
                    Thêm nhân viên mới
                </h2>
                <p class="text-muted mb-0">Điền thông tin để thêm nhân viên vào hệ thống</p>
            </div>

            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('employees.store') }}">
                        @csrf

                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-person-badge me-2"></i>
                            Thông tin cơ bản
                        </h5>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Mã nhân viên <span class="text-danger">*</span></label>
                                <input type="text" name="code" value="{{ old('code') }}" class="form-control" style="border-radius: 12px;" required>
                                @error('code')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Họ tên <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control" style="border-radius: 12px;" required>
                                @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control" style="border-radius: 12px;" required>
                                @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Số điện thoại</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" style="border-radius: 12px;">
                                @error('phone')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" style="border-radius: 12px;" required>
                                @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Ngày vào làm</label>
                                <input type="date" name="hired_at" value="{{ old('hired_at', now()->format('Y-m-d')) }}" class="form-control" style="border-radius: 12px;">
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-briefcase me-2"></i>
                            Thông tin công việc
                        </h5>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Phòng ban</label>
                                <select name="department_id" class="form-select" style="border-radius: 12px;">
                                    <option value="">Chọn phòng ban</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Chức vụ</label>
                                <input type="text" name="position" value="{{ old('position') }}" class="form-control" style="border-radius: 12px;">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Vai trò <span class="text-danger">*</span></label>
                                <select name="role_id" class="form-select" style="border-radius: 12px;" required>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                            {{ $role->display_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Trạng thái <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" style="border-radius: 12px;" required>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Đang làm việc</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Nghỉ việc</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Thang lương</label>
                                <select name="salary_grade_id" class="form-select" style="border-radius: 12px;">
                                    <option value="">Chọn thang lương</option>
                                    @foreach($salaryGrades as $grade)
                                        <option value="{{ $grade->id }}" {{ old('salary_grade_id') == $grade->id ? 'selected' : '' }}>
                                            {{ $grade->name }} - {{ number_format($grade->base_salary, 0, ',', '.') }}đ
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Lương cơ bản (ghi đè)</label>
                                <input type="number" name="base_salary_override" value="{{ old('base_salary_override') }}" step="1000" class="form-control" style="border-radius: 12px;">
                                <small class="text-muted">Để trống nếu dùng lương từ thang lương</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('employees.index') }}" class="btn btn-light px-4">
                                <i class="bi bi-x-circle me-2"></i>
                                Hủy
                            </a>
                            <button type="submit" class="btn btn-gradient px-4">
                                <i class="bi bi-check-circle me-2"></i>
                                Thêm nhân viên
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
