@extends('layouts.app')

@section('title', 'Hồ sơ của tôi')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1">Hồ sơ của tôi</h2>
                    <p class="text-muted mb-0">Thông tin cá nhân và hợp đồng</p>
                </div>
                <div class="d-flex gap-2">
                    @can('manage-employees')
                    <a href="{{ route('employee-profiles.edit', $user) }}" class="btn btn-gradient">
                        <i class="bi bi-pencil me-2"></i>Chỉnh sửa hồ sơ
                    </a>
                    @endcan
                    <a href="{{ url()->previous() }}" class="btn btn-light">
                        <i class="bi bi-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-body text-center p-4">
                            @if($user->avatar_path)
                                <img src="{{ asset('storage/' . $user->avatar_path) }}" alt="avatar" class="img-fluid rounded mb-3" style="max-width:180px;">
                            @else
                                <div class="user-avatar mx-auto mb-3" style="width:180px;height:180px;border-radius:12px;font-size:56px;display:flex;align-items:center;justify-content:center;">
                                    {{ strtoupper(substr($user->name,0,1)) }}
                                </div>
                            @endif

                            <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                            <div class="text-muted mb-3">{{ $user->email }}</div>

                            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <input type="file" name="avatar" class="form-control">
                                    <div class="form-text">Hỗ trợ: jpeg, jpg, png, gif, webp. Tối đa 5MB.</div>
                                </div>
                                <div class="d-grid">
                                    <button class="btn btn-gradient">Cập nhật ảnh</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="card h-100">
                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-4"><i class="bi bi-person-badge me-2"></i>Thông tin cá nhân</h5>
                                    <div class="mb-3"><small class="text-muted d-block mb-1">Mã nhân viên</small><h6 class="fw-semibold mb-0">{{ $user->code ?? '-' }}</h6></div>
                                    <div class="mb-3"><small class="text-muted d-block mb-1">CMND/CCCD</small><h6 class="fw-semibold mb-0">{{ $user->profile->id_number ?? '-' }}</h6></div>
                                    <div class="mb-3"><small class="text-muted d-block mb-1">Ngày sinh</small><h6 class="fw-semibold mb-0">{{ $user->profile->date_of_birth ? $user->profile->date_of_birth->format('d/m/Y') : '-' }}</h6></div>
                                    <div class="mb-3"><small class="text-muted d-block mb-1">Giới tính</small><h6 class="fw-semibold mb-0">@if($user->profile->gender){{ $user->profile->gender === 'male' ? 'Nam' : ($user->profile->gender === 'female' ? 'Nữ' : 'Khác') }}@else - @endif</h6></div>
                                    <div class="mb-3"><small class="text-muted d-block mb-1">Địa chỉ</small><h6 class="fw-semibold mb-0">{{ $user->profile->address ?? '-' }}</h6></div>
                                    <div class="mb-3"><small class="text-muted d-block mb-1">Số điện thoại</small><h6 class="fw-semibold mb-0">{{ $user->phone ?? ($user->profile->phone ?? '-') }}</h6></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card h-100">
                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-4"><i class="bi bi-mortarboard me-2"></i>Học vấn & Kinh nghiệm</h5>
                                    <div class="mb-3"><small class="text-muted d-block mb-1">Trình độ</small><h6 class="fw-semibold mb-0">{{ $user->profile->education_level ?? '-' }}</h6></div>
                                    <div class="mb-3"><small class="text-muted d-block mb-1">Chuyên ngành</small><h6 class="fw-semibold mb-0">{{ $user->profile->major ?? '-' }}</h6></div>
                                    <div class="mb-3"><small class="text-muted d-block mb-1">Trường</small><h6 class="fw-semibold mb-0">{{ $user->profile->university ?? '-' }}</h6></div>
                                    <div class="mb-3"><small class="text-muted d-block mb-1">Kinh nghiệm</small><h6 class="fw-semibold mb-0">{{ $user->profile->years_of_experience ?? 0 }} năm</h6></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-4"><i class="bi bi-file-earmark-text me-2"></i>Hợp đồng</h5>
                                    @if($user->contracts && $user->contracts->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Số hợp đồng</th>
                                                    <th>Loại</th>
                                                    <th>Ngày bắt đầu</th>
                                                    <th>Ngày kết thúc</th>
                                                    <th>Lương</th>
                                                    <th>Trạng thái</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($user->contracts as $contract)
                                                <tr>
                                                    <td>{{ $contract->contract_number }}</td>
                                                    <td>{{ $contract->contract_type }}</td>
                                                    <td>{{ $contract->start_date?->format('d/m/Y') }}</td>
                                                    <td>{{ $contract->end_date?->format('d/m/Y') ?? '-' }}</td>
                                                    <td>{{ $contract->salary ? number_format($contract->salary,0,',','.') . 'đ' : '-' }}</td>
                                                    <td>{{ $contract->status }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @else
                                    <div class="text-muted">Không có hợp đồng.</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
