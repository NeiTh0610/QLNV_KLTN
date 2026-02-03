@extends('layouts.app')

@section('title', 'Đăng ký làm thêm giờ')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-clock-history me-2"></i>
                Đăng ký làm thêm giờ
            </h2>
            <p class="text-muted mb-0">Quản lý đăng ký làm thêm giờ</p>
        </div>
        @if(!auth()->user()->canManageEmployees())
        <a href="{{ route('overtime-requests.create') }}" class="btn btn-gradient">
            <i class="bi bi-plus-circle me-2"></i>
            Đăng ký mới
        </a>
        @endif
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('overtime-requests.index') }}">
                <div class="row g-3 align-items-end">
                    @if(auth()->user()->canManageEmployees())
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Nhân viên</label>
                        <select name="user_id" class="form-select" style="border-radius: 12px;">
                            <option value="">Tất cả nhân viên</option>
                            @foreach(\App\Models\User::where('status', 'active')->get() as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Trạng thái</label>
                        <select name="status" class="form-select" style="border-radius: 12px;">
                            <option value="">Tất cả trạng thái</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Từ chối</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tháng</label>
                        <input type="month" name="month" class="form-control" value="{{ request('month', now()->format('Y-m')) }}" style="border-radius: 12px;">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-gradient w-100">
                            <i class="bi bi-funnel me-2"></i>
                            Lọc
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Overtime Requests Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            @if(auth()->user()->canManageEmployees())
                            <th class="py-3 px-4 fw-semibold">Nhân viên</th>
                            @endif
                            <th class="py-3 px-4 fw-semibold">Ngày</th>
                            <th class="py-3 px-4 fw-semibold">Thời gian</th>
                            <th class="py-3 px-4 fw-semibold">Số giờ</th>
                            <th class="py-3 px-4 fw-semibold">Lý do</th>
                            <th class="py-3 px-4 fw-semibold">Trạng thái</th>
                            <th class="py-3 px-4 fw-semibold text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($overtimeRequests as $request)
                        <tr>
                            @if(auth()->user()->canManageEmployees())
                            <td class="py-3 px-4">{{ $request->user->name }}</td>
                            @endif
                            <td class="py-3 px-4">{{ $request->date->format('d/m/Y') }}</td>
                            <td class="py-3 px-4">
                                {{ \Carbon\Carbon::parse($request->start_time)->format('H:i') }} - 
                                {{ \Carbon\Carbon::parse($request->end_time)->format('H:i') }}
                            </td>
                            <td class="py-3 px-4 fw-semibold">{{ number_format($request->hours, 2) }}h</td>
                            <td class="py-3 px-4">
                                <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $request->reason }}">
                                    {{ $request->reason ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                @if($request->status === 'approved')
                                    <span class="badge bg-success">Đã duyệt</span>
                                @elseif($request->status === 'rejected')
                                    <span class="badge bg-danger">Từ chối</span>
                                @else
                                    <span class="badge bg-warning">Chờ duyệt</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-end">
                                <a href="{{ route('overtime-requests.show', $request) }}" class="btn btn-sm btn-outline-info me-2">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($request->status === 'pending')
                                    @if(auth()->user()->canManageEmployees())
                                        <form action="{{ route('overtime-requests.approve', $request) }}" method="POST" class="d-inline me-2">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success" onclick="return confirm('Bạn có chắc chắn muốn duyệt đăng ký này?');">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $request->id }}">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    @elseif($request->user_id === auth()->id())
                                        <a href="{{ route('overtime-requests.edit', $request) }}" class="btn btn-sm btn-outline-primary me-2">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('overtime-requests.destroy', $request) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đăng ký này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        
                        <!-- Reject Modal -->
                        @if(auth()->user()->canManageEmployees() && $request->status === 'pending')
                        <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('overtime-requests.reject', $request) }}" method="POST">
                                        @csrf
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">Từ chối đăng ký làm thêm giờ</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Lý do từ chối (tùy chọn)</label>
                                                <textarea name="rejection_reason" class="form-control" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-danger">Từ chối</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                        @empty
                        <tr>
                            <td colspan="{{ auth()->user()->canManageEmployees() ? '7' : '6' }}" class="text-center py-5">
                                <i class="bi bi-inbox text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                                <p class="text-muted mt-3">Chưa có đăng ký làm thêm giờ nào</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($overtimeRequests->hasPages())
            <div class="p-4 border-top">
                {{ $overtimeRequests->links('pagination::bootstrap-5') }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

