@extends('layouts.app')

@section('title', 'Báo cáo')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="mb-4">
        <h2 class="fw-bold mb-1">
            <i class="bi bi-bar-chart me-2"></i>
            Báo cáo chấm công
        </h2>
        <p class="text-muted mb-0">Thống kê và báo cáo chi tiết</p>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Khoảng thời gian</label>
                        <select name="period" class="form-select" style="border-radius: 12px;">
                            <option value="day" {{ request('period') == 'day' ? 'selected' : '' }}>Theo ngày</option>
                            <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>Theo tuần</option>
                            <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>Theo tháng</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Chọn thời gian</label>
                        <input type="{{ request('period') == 'day' ? 'date' : (request('period') == 'week' ? 'week' : 'month') }}" 
                               name="date" 
                               value="{{ request('date', now()->format('Y-m')) }}"
                               class="form-control"
                               style="border-radius: 12px;">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Phòng ban</label>
                        <select name="department_id" class="form-select" style="border-radius: 12px;">
                            <option value="">Tất cả phòng ban</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-gradient flex-fill">
                                <i class="bi bi-search me-2"></i>
                                Xem
                            </button>
                            <a href="{{ route('reports.export', request()->all()) }}" class="btn btn-success">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Report Card -->
    <div class="card">
        <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-calendar-range me-2"></i>
                Báo cáo từ {{ $startDate->format('d/m/Y') }} đến {{ $endDate->format('d/m/Y') }}
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4 fw-semibold">Mã NV</th>
                            <th class="py-3 px-4 fw-semibold">Họ tên</th>
                            <th class="py-3 px-4 fw-semibold">Phòng ban</th>
                            <th class="py-3 px-4 fw-semibold text-center">Tổng ngày</th>
                            <th class="py-3 px-4 fw-semibold text-center">Đúng giờ</th>
                            <th class="py-3 px-4 fw-semibold text-center">Đi muộn</th>
                            <th class="py-3 px-4 fw-semibold text-center">Về sớm</th>
                            <th class="py-3 px-4 fw-semibold text-center">Vắng mặt</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                        <tr>
                            <td class="py-3 px-4 fw-semibold">{{ $report['user']->code }}</td>
                            <td class="py-3 px-4">{{ $report['user']->name }}</td>
                            <td class="py-3 px-4">{{ $report['user']->profile->department->name ?? '-' }}</td>
                            <td class="py-3 px-4 text-center">
                                <span class="badge bg-primary rounded-pill">{{ $report['total_days'] }}</span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="badge rounded-pill" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
                                    {{ $report['on_time'] }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="badge bg-warning text-dark rounded-pill">{{ $report['late'] }}</span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="badge bg-danger rounded-pill">{{ $report['early_leave'] }}</span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="badge bg-dark rounded-pill">{{ $report['absent'] }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-inbox text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                                <p class="text-muted mt-3">Không có dữ liệu báo cáo</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
