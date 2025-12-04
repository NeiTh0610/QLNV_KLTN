@extends('layouts.app')

@section('title', 'Đơn xin nghỉ')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-file-earmark-text me-2"></i>
                Đơn xin nghỉ
            </h2>
            <p class="text-muted mb-0">Quản lý đơn xin nghỉ phép</p>
        </div>
        @if(!auth()->user()->canManageEmployees())
        <a href="{{ route('leave-requests.create') }}" class="btn btn-gradient">
            <i class="bi bi-plus-circle me-2"></i>
            Tạo đơn mới
        </a>
        @endif
    </div>

    <!-- Filters -->
    @if(auth()->user()->canManageEmployees())
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('leave-requests.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-9">
                        <label class="form-label fw-semibold">Trạng thái</label>
                        <select name="status" class="form-select" style="border-radius: 12px;">
                            <option value="">Tất cả trạng thái</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Từ chối</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-gradient w-100">
                            <i class="bi bi-funnel me-2"></i>
                            Lọc
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Leave Requests Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <tr>
                            <th class="py-3 px-4 fw-semibold">Nhân viên</th>
                            <th class="py-3 px-4 fw-semibold">Loại</th>
                            <th class="py-3 px-4 fw-semibold">Thời gian</th>
                            <th class="py-3 px-4 fw-semibold">Lý do</th>
                            <th class="py-3 px-4 fw-semibold text-center">Trạng thái</th>
                            <th class="py-3 px-4 fw-semibold">Lý do từ chối</th>
                            @if(auth()->user()->canManageEmployees())
                            <th class="py-3 px-4 fw-semibold text-end">Thao tác</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaveRequests as $request)
                        <tr>
                            <td class="py-3 px-4">
                                <div class="fw-semibold">{{ $request->user->name }}</div>
                                <small class="text-muted">{{ $request->user->code }}</small>
                            </td>
                            <td class="py-3 px-4">
                                @if($request->type === 'leave')
                                    <span class="badge bg-info">Nghỉ phép</span>
                                @elseif($request->type === 'late')
                                    <span class="badge bg-warning text-dark">Đi muộn</span>
                                @elseif($request->type === 'early')
                                    <span class="badge bg-danger">Về sớm</span>
                                @elseif($request->type === 'remote')
                                    <span class="badge bg-primary">Làm từ xa</span>
                                @else
                                    <span class="badge bg-secondary">Khác</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="small">{{ \Carbon\Carbon::parse($request->start_at)->format('d/m/Y H:i') }}</div>
                                <div class="text-muted small">đến {{ \Carbon\Carbon::parse($request->end_at)->format('d/m/Y H:i') }}</div>
                                <div class="badge bg-light text-dark mt-1">{{ $request->duration_hours }} giờ</div>
                            </td>
                            <td class="py-3 px-4">
                                <div style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $request->reason }}
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($request->status === 'pending')
                                    <span class="badge bg-warning text-dark" style="padding: 0.5rem 1rem; border-radius: 8px;">
                                        <i class="bi bi-clock me-1"></i>Chờ duyệt
                                    </span>
                                @elseif($request->status === 'approved')
                                    <span class="badge" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; padding: 0.5rem 1rem; border-radius: 8px;">
                                        <i class="bi bi-check-circle me-1"></i>Đã duyệt
                                    </span>
                                @elseif($request->status === 'rejected')
                                    <span class="badge bg-danger" style="padding: 0.5rem 1rem; border-radius: 8px;">
                                        <i class="bi bi-x-circle me-1"></i>Từ chối
                                    </span>
                                @else
                                    <span class="badge bg-secondary" style="padding: 0.5rem 1rem; border-radius: 8px;">
                                        <i class="bi bi-slash-circle me-1"></i>Đã hủy
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if($request->status === 'rejected' && $request->rejection_reason)
                                    <div class="text-danger small" style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $request->rejection_reason }}">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        {{ $request->rejection_reason }}
                                    </div>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            @if(auth()->user()->canManageEmployees() && $request->status === 'pending')
                            <td class="py-3 px-4 text-end">
                                <form action="{{ route('leave-requests.approve', $request) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success me-1" style="border-radius: 8px;">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                                <button onclick="showRejectModal({{ $request->id }})" class="btn btn-sm btn-danger" style="border-radius: 8px;">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </td>
                            @elseif(auth()->user()->canManageEmployees())
                            <td class="py-3 px-4 text-end">
                                <small class="text-muted">{{ $request->approver->name ?? '-' }}</small>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ auth()->user()->canManageEmployees() ? '7' : '6' }}" class="text-center py-5">
                                <i class="bi bi-inbox text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                                <p class="text-muted mt-3">Không có đơn xin nghỉ nào</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($leaveRequests->hasPages())
            <div class="p-4 border-top">
                {{ $leaveRequests->links('pagination::bootstrap-5') }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-x-circle text-danger me-2"></i>
                    Từ chối đơn
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <label class="form-label fw-semibold">Lý do từ chối <span class="text-danger">*</span></label>
                    <textarea name="rejection_reason" rows="4" class="form-control" style="border-radius: 12px;" placeholder="Nhập lý do từ chối đơn xin nghỉ..." required></textarea>
                    <small class="text-muted">Lý do từ chối sẽ được hiển thị cho nhân viên.</small>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-circle me-2"></i>
                        Từ chối
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function showRejectModal(requestId) {
        document.getElementById('rejectForm').action = `/leave-requests/${requestId}/reject`;
        new bootstrap.Modal(document.getElementById('rejectModal')).show();
    }
</script>
@endsection
