@extends('layouts.app')

@section('title', 'Thêm phòng ban')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Page Header -->
            <div class="mb-4">
                <h2 class="fw-bold mb-1">
                    <i class="bi bi-building me-2"></i>
                    Thêm phòng ban mới
                </h2>
                <p class="text-muted mb-0">Điền thông tin để thêm phòng ban vào hệ thống</p>
            </div>

            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('departments.store') }}">
                        @csrf

                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Tên phòng ban <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       style="border-radius: 12px;" 
                                       placeholder="Ví dụ: Phòng Nhân sự"
                                       required>
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Mô tả</label>
                                <textarea name="description" 
                                          class="form-control @error('description') is-invalid @enderror" 
                                          style="border-radius: 12px;" 
                                          rows="3"
                                          placeholder="Mô tả về phòng ban...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Trưởng phòng</label>
                                <select name="manager_id" class="form-select @error('manager_id') is-invalid @enderror" style="border-radius: 12px;">
                                    <option value="">Chọn trưởng phòng (tùy chọn)</option>
                                    @foreach($managers as $manager)
                                        <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
                                            {{ $manager->name }} ({{ $manager->code }}) - {{ $manager->email }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('manager_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Chỉ hiển thị người dùng có vai trò admin hoặc manager</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary" style="border-radius: 12px;">
                                <i class="bi bi-arrow-left me-2"></i>
                                Quay lại
                            </a>
                            <button type="submit" class="btn btn-gradient" style="border-radius: 12px;">
                                <i class="bi bi-check-circle me-2"></i>
                                Thêm phòng ban
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

