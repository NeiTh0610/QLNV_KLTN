@extends('layouts.app')

@section('title', 'Yêu cầu quên mật khẩu')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1">Yêu cầu quên mật khẩu</h2>
                    <p class="text-muted mb-0">Danh sách nhân viên yêu cầu hỗ trợ đặt lại mật khẩu</p>
                </div>
            </div>

            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nhân viên</th>
                                    <th>Email</th>
                                    <th>Trạng thái</th>
                                    <th>Ghi chú</th>
                                    <th>Thời gian</th>
                                    <th class="text-end">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requests as $index => $request)
                                    <tr>
                                        <td>{{ $requests->firstItem() + $index }}</td>
                                        <td>
                                            @if($request->user)
                                                <strong>{{ $request->user->name }}</strong><br>
                                                <small class="text-muted">{{ $request->user->code }}</small>
                                            @else
                                                <span class="text-muted">Không tìm thấy người dùng</span>
                                            @endif
                                        </td>
                                        <td>{{ $request->email }}</td>
                                        <td>
                                            @php
                                                $badgeClass = match($request->status) {
                                                    'processed' => 'badge bg-success',
                                                    'rejected' => 'badge bg-danger',
                                                    default => 'badge bg-warning text-dark',
                                                };
                                            @endphp
                                            <span class="{{ $badgeClass }}">
                                                {{ $request->status === 'pending' ? 'Chờ xử lý' : ($request->status === 'processed' ? 'Đã xử lý' : 'Từ chối') }}
                                            </span>
                                        </td>
                                        <td style="max-width: 220px;">
                                            <small class="text-muted">{{ $request->note }}</small>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $request->created_at->format('d/m/Y H:i') }}
                                            </small>
                                        </td>
                                        <td class="text-end">
                                            <form method="POST" action="{{ route('password-requests.update', $request) }}" class="d-inline-block text-start" style="min-width:260px;">
                                                @csrf
                                                @method('PUT')
                                                <div class="row g-2 align-items-center">
                                                    <div class="col-5">
                                                        <select name="status" class="form-select form-select-sm">
                                                            <option value="pending" {{ $request->status === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                                            <option value="processed" {{ $request->status === 'processed' ? 'selected' : '' }}>Đã xử lý</option>
                                                            <option value="rejected" {{ $request->status === 'rejected' ? 'selected' : '' }}>Từ chối</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-5">
                                                        <input type="text" name="note" value="{{ $request->note }}" class="form-control form-control-sm" placeholder="Ghi chú">
                                                    </div>
                                                    <div class="col-2 text-end">
                                                        <button class="btn btn-sm btn-primary">
                                                            Lưu
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            Chưa có yêu cầu quên mật khẩu nào.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($requests->hasPages())
                    <div class="card-footer">
                        {{ $requests->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

