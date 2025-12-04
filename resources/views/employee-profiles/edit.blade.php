@extends('layouts.app')

@section('title', 'Sửa hồ sơ nhân viên')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="mb-4">
                <h2 class="fw-bold mb-1">
                    <i class="bi bi-pencil-square me-2"></i>
                    Sửa hồ sơ nhân viên
                </h2>
                <p class="text-muted mb-0">Cập nhật thông tin hồ sơ cho {{ $employee->name }}</p>
            </div>

            <form method="POST" action="{{ route('employee-profiles.update', $employee) }}">
                @csrf
                @method('PUT')

                <div class="card mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-person-badge me-2"></i>
                            Thông tin cơ bản
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Mã nhân viên <span class="text-danger">*</span></label>
                                <input type="text" name="code" value="{{ old('code', $employee->code) }}" class="form-control" required>
                                @error('code')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Họ tên <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $employee->name) }}" class="form-control" required>
                                @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" value="{{ old('email', $employee->email) }}" class="form-control" required>
                                @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Số điện thoại</label>
                                <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}" class="form-control">
                                @error('phone')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-file-person me-2"></i>
                            Thông tin cá nhân
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">CMND/CCCD</label>
                                <input type="text" name="id_number" value="{{ old('id_number', $employee->profile->id_number ?? '') }}" class="form-control">
                                @error('id_number')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Ngày sinh</label>
                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $employee->profile->date_of_birth?->format('Y-m-d') ?? '') }}" class="form-control">
                                @error('date_of_birth')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Giới tính</label>
                                <select name="gender" class="form-select">
                                    <option value="">Chọn giới tính</option>
                                    <option value="male" {{ old('gender', $employee->profile->gender ?? '') == 'male' ? 'selected' : '' }}>Nam</option>
                                    <option value="female" {{ old('gender', $employee->profile->gender ?? '') == 'female' ? 'selected' : '' }}>Nữ</option>
                                    <option value="other" {{ old('gender', $employee->profile->gender ?? '') == 'other' ? 'selected' : '' }}>Khác</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Địa chỉ</label>
                                <input type="text" name="address" value="{{ old('address', $employee->profile->address ?? '') }}" class="form-control">
                                @error('address')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email cá nhân</label>
                                <input type="email" name="personal_email" value="{{ old('personal_email', $employee->profile->personal_email ?? '') }}" class="form-control">
                                @error('personal_email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tên người liên hệ khẩn cấp</label>
                                <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $employee->profile->emergency_contact_name ?? '') }}" class="form-control">
                                @error('emergency_contact_name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">SĐT người liên hệ khẩn cấp</label>
                                <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $employee->profile->emergency_contact_phone ?? '') }}" class="form-control">
                                @error('emergency_contact_phone')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-mortarboard me-2"></i>
                            Học vấn & Kinh nghiệm
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Trình độ học vấn</label>
                                <input type="text" name="education_level" value="{{ old('education_level', $employee->profile->education_level ?? '') }}" class="form-control" placeholder="VD: Đại học, Cao đẳng...">
                                @error('education_level')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Chuyên ngành</label>
                                <input type="text" name="major" value="{{ old('major', $employee->profile->major ?? '') }}" class="form-control">
                                @error('major')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Trường đại học</label>
                                <input type="text" name="university" value="{{ old('university', $employee->profile->university ?? '') }}" class="form-control">
                                @error('university')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Số năm kinh nghiệm</label>
                                <input type="number" name="years_of_experience" value="{{ old('years_of_experience', $employee->profile->years_of_experience ?? 0) }}" class="form-control" min="0">
                                @error('years_of_experience')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Kỹ năng</label>
                                <textarea name="skills" class="form-control" rows="3">{{ old('skills', $employee->profile->skills ?? '') }}</textarea>
                                @error('skills')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Chứng chỉ</label>
                                <textarea name="certifications" class="form-control" rows="3">{{ old('certifications', $employee->profile->certifications ?? '') }}</textarea>
                                @error('certifications')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Công việc trước đây</label>
                                <textarea name="previous_work" class="form-control" rows="3">{{ old('previous_work', $employee->profile->previous_work ?? '') }}</textarea>
                                @error('previous_work')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-briefcase me-2"></i>
                            Thông tin công việc
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Phòng ban</label>
                                <select name="department_id" class="form-select">
                                    <option value="">Chọn phòng ban</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id', $employee->profile->department_id ?? '') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Chức vụ</label>
                                <input type="text" name="position" value="{{ old('position', $employee->profile->position ?? '') }}" class="form-control">
                                @error('position')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Bậc lương</label>
                                <select name="salary_grade_id" class="form-select">
                                    <option value="">Chọn bậc lương</option>
                                    @foreach($salaryGrades as $grade)
                                        <option value="{{ $grade->id }}" {{ old('salary_grade_id', $employee->profile->salary_grade_id ?? '') == $grade->id ? 'selected' : '' }}>
                                            {{ $grade->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Lương cơ bản (ghi đè)</label>
                                <input type="number" name="base_salary_override" value="{{ old('base_salary_override', $employee->profile->base_salary_override ?? '') }}" class="form-control" min="0">
                                <small class="text-muted">Để trống nếu dùng lương từ bậc lương</small>
                                @error('base_salary_override')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('employee-profiles.index') }}" class="btn btn-light">Hủy</a>
                    <button type="submit" class="btn btn-gradient">
                        <i class="bi bi-check-circle me-2"></i>
                        Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

