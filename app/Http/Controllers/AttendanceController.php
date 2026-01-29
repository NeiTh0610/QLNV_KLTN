<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Support\Settings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function checkIn()
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');
        
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->where('work_date', $today)
            ->first();

        // Lấy IP local để tạo QR code URL
        // Luôn dùng IP thực tế từ hệ thống (tự động cập nhật khi đổi mạng)
        $localIp = $this->getLocalIp();
        $port = request()->getPort();
        // Nếu port là 80 (Apache) hoặc không có port, không thêm port vào URL
        // Nếu port khác (Laravel serve thường là 8000), thêm port vào URL
        if ($port && $port != 80 && $port != 443) {
            $qrBaseUrl = "http://{$localIp}:{$port}";
        } else {
            $qrBaseUrl = "http://{$localIp}";
        }

        return view('attendance.check-in', compact('todayAttendance', 'qrBaseUrl'));
    }

    /**
     * API endpoint để kiểm tra trạng thái attendance hiện tại (cho auto-reload)
     */
    public function checkStatus()
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');
        
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->where('work_date', $today)
            ->first();

        return response()->json([
            'has_check_in' => $todayAttendance && $todayAttendance->check_in_at ? true : false,
            'has_check_out' => $todayAttendance && $todayAttendance->check_out_at ? true : false,
            'check_in_at' => $todayAttendance && $todayAttendance->check_in_at ? $todayAttendance->check_in_at->toDateTimeString() : null,
            'check_out_at' => $todayAttendance && $todayAttendance->check_out_at ? $todayAttendance->check_out_at->toDateTimeString() : null,
            'status' => $todayAttendance ? $todayAttendance->status : null,
            'timestamp' => now()->toDateTimeString()
        ]);
    }

    /**
     * Lấy IP local của máy tính - Tự động phát hiện IP mới nhất từ hệ thống
     * Ưu tiên lấy từ hệ thống để tự động cập nhật khi đổi mạng
     */
    private function getLocalIp()
    {
        // Ưu tiên 1: Lấy IP từ hệ thống (Windows/Linux/Mac) - Tự động cập nhật khi đổi mạng
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Windows: Sử dụng ipconfig để lấy IP mới nhất
            $command = 'ipconfig | findstr /i "IPv4"';
            $output = @shell_exec($command);
            if ($output && preg_match_all('/\b(?:[0-9]{1,3}\.){3}[0-9]{1,3}\b/', $output, $matches)) {
                $ips = array_unique($matches[0]);
                
                // Ưu tiên 1: IP bắt đầu bằng 192.168 (thường là WiFi/Ethernet chính)
                // Loại bỏ 192.168.137.x (thường là hotspot)
                foreach ($ips as $ip) {
                    if ($ip !== '127.0.0.1' && $ip !== '0.0.0.0' && strpos($ip, '192.168.') === 0) {
                        if (strpos($ip, '192.168.137.') !== 0) {
                            return $ip; // Trả về IP đầu tiên hợp lệ (IP mới nhất)
                        }
                    }
                }
                
                // Ưu tiên 2: IP bắt đầu bằng 10. (mạng nội bộ)
                foreach ($ips as $ip) {
                    if ($ip !== '127.0.0.1' && $ip !== '0.0.0.0' && strpos($ip, '10.') === 0) {
                        return $ip;
                    }
                }
                
                // Ưu tiên 3: IP bắt đầu bằng 172. (mạng nội bộ)
                foreach ($ips as $ip) {
                    if ($ip !== '127.0.0.1' && $ip !== '0.0.0.0' && strpos($ip, '172.') === 0) {
                        return $ip;
                    }
                }
                
                // Nếu không có IP nội bộ, lấy IP đầu tiên hợp lệ (không phải localhost)
                foreach ($ips as $ip) {
                    if ($ip !== '127.0.0.1' && $ip !== '0.0.0.0' && filter_var($ip, FILTER_VALIDATE_IP)) {
                        return $ip;
                    }
                }
            }
        } else {
            // Linux/Mac: Sử dụng hostname -I hoặc ip route
            $command = "hostname -I 2>/dev/null | awk '{print $1}'";
            $output = @shell_exec($command);
            $ip = trim($output ?? '');
            if (!empty($ip) && filter_var($ip, FILTER_VALIDATE_IP) && $ip !== '127.0.0.1' && $ip !== '0.0.0.0') {
                return $ip;
            }
            
            // Thử với ip route (Linux) - lấy IP của interface mặc định
            $command = "ip route get 8.8.8.8 2>/dev/null | awk '{print $7}' | head -1";
            $output = @shell_exec($command);
            $ip = trim($output ?? '');
            if (!empty($ip) && filter_var($ip, FILTER_VALIDATE_IP) && $ip !== '127.0.0.1' && $ip !== '0.0.0.0') {
                return $ip;
            }
        }
        
        // Fallback 1: Thử lấy từ request host (khi truy cập từ điện thoại)
        $host = request()->getHost();
        if ($host && $host !== 'localhost' && $host !== '127.0.0.1' && $host !== '0.0.0.0' && filter_var($host, FILTER_VALIDATE_IP)) {
            return $host;
        }
        
        // Fallback 2: Thử lấy từ $_SERVER['HTTP_HOST'] nếu có
        if (isset($_SERVER['HTTP_HOST'])) {
            $httpHost = $_SERVER['HTTP_HOST'];
            // Loại bỏ port nếu có
            $httpHost = explode(':', $httpHost)[0];
            if (filter_var($httpHost, FILTER_VALIDATE_IP) && $httpHost !== '127.0.0.1' && $httpHost !== '0.0.0.0') {
                return $httpHost;
            }
        }
        
        // Fallback 3: Sử dụng IP từ request server
        $serverIp = request()->server('SERVER_ADDR');
        if (!empty($serverIp) && $serverIp !== '127.0.0.1' && $serverIp !== '::1' && $serverIp !== '0.0.0.0') {
            return $serverIp;
        }
        
        // Fallback 4: Lấy từ APP_URL trong .env (chỉ khi không tìm được từ hệ thống)
        $appUrl = env('APP_URL');
        if ($appUrl) {
            $parsedUrl = parse_url($appUrl);
            if (isset($parsedUrl['host']) && filter_var($parsedUrl['host'], FILTER_VALIDATE_IP)) {
                $host = $parsedUrl['host'];
                if ($host !== '127.0.0.1' && $host !== 'localhost' && $host !== '0.0.0.0') {
                    return $host;
                }
            }
        }
        
        // Nếu vẫn không tìm thấy, trả về localhost
        return 'localhost';
    }

    /**
     * Kiểm tra xem hai IP có cùng network không (subnet /24)
     */
    private function isSameNetwork($ip1, $ip2)
    {
        // Nếu một trong hai IP là localhost, coi như cùng network (cho dev)
        if ($ip1 === 'localhost' || $ip2 === 'localhost' || 
            $ip1 === '127.0.0.1' || $ip2 === '127.0.0.1' ||
            $ip1 === '::1' || $ip2 === '::1') {
            return true;
        }

        // Validate IP
        if (!filter_var($ip1, FILTER_VALIDATE_IP) || !filter_var($ip2, FILTER_VALIDATE_IP)) {
            return false;
        }

        // Lấy 3 octet đầu (giả sử subnet /24)
        $ip1Parts = explode('.', $ip1);
        $ip2Parts = explode('.', $ip2);

        // Chỉ kiểm tra với IPv4
        if (count($ip1Parts) !== 4 || count($ip2Parts) !== 4) {
            // Nếu là IPv6 hoặc format khác, cho phép (có thể là cùng network)
            return true;
        }

        // So sánh 3 octet đầu
        return ($ip1Parts[0] === $ip2Parts[0] && 
                $ip1Parts[1] === $ip2Parts[1] && 
                $ip1Parts[2] === $ip2Parts[2]);
    }

    public function storeCheckIn(Request $request)
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');
        $now = now();
        
        $workingStart = Settings::get('attendance.working_hours.start', '08:00');
        $lateThreshold = (int) Settings::get('attendance.late.threshold_minutes', 15);
        
        $startTime = Carbon::parse($today . ' ' . $workingStart);
        $checkInTime = Carbon::parse($now->format('Y-m-d H:i:s'));
        
        $status = 'on_time';
        if ($checkInTime->gt($startTime->copy()->addMinutes($lateThreshold))) {
            $status = 'late';
        }
        
        $attendance = Attendance::firstOrCreate(
            [
                'user_id' => $user->id,
                'work_date' => $today,
            ],
            [
                'check_in_at' => $now,
                'check_in_method' => 'manual',
                'check_in_ip' => $request->ip(),
                'status' => $status,
            ]
        );

        if (!$attendance->wasRecentlyCreated && !$attendance->check_in_at) {
            $attendance->update([
                'check_in_at' => $now,
                'check_in_method' => 'manual',
                'check_in_ip' => $request->ip(),
                'status' => $status,
            ]);
        }

        return redirect()->route('attendance.check-in')->with('success', 'Check-in thành công!');
    }

    public function storeCheckOut(Request $request)
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');
        $now = now();
        
        $attendance = Attendance::where('user_id', $user->id)
            ->where('work_date', $today)
            ->first();

        if (!$attendance || !$attendance->check_in_at) {
            return redirect()->route('attendance.check-in')->with('error', 'Bạn cần check-in trước!');
        }

        if ($attendance->check_out_at) {
            return redirect()->route('attendance.check-in')->with('error', 'Bạn đã check-out rồi!');
        }

        $workingEnd = Settings::get('attendance.working_hours.end', '17:00');
        $earlyThreshold = (int) Settings::get('attendance.early_leave.threshold_minutes', 15);
        
        $endTime = Carbon::parse($today . ' ' . $workingEnd);
        $checkOutTime = Carbon::parse($now->format('Y-m-d H:i:s'));
        
        $status = $attendance->status;
        if ($checkOutTime->lt($endTime->copy()->subMinutes($earlyThreshold))) {
            $status = 'early_leave';
        } elseif ($status === 'late' && $checkOutTime->gte($endTime)) {
            // Nếu đi muộn nhưng về đúng giờ, vẫn giữ trạng thái đi muộn
            $status = 'late';
        } elseif ($status !== 'late') {
            $status = 'on_time';
        }

        $attendance->update([
            'check_out_at' => $now,
            'check_out_method' => 'manual',
            'check_out_ip' => $request->ip(),
            'status' => $status,
        ]);

        return redirect()->route('attendance.check-in')->with('success', 'Check-out thành công!');
    }

    public function history(Request $request)
    {
        $user = Auth::user();
        $month = $request->get('month', now()->format('Y-m'));
        
        $attendances = Attendance::where('user_id', $user->id)
            ->whereYear('work_date', substr($month, 0, 4))
            ->whereMonth('work_date', substr($month, 5, 2))
            ->orderBy('work_date', 'desc')
            ->get();

        return view('attendance.history', compact('attendances'));
    }

    /**
     * Xử lý quét QR code từ mobile
     */
    public function qrScan(Request $request)
    {
        $data = $request->get('data');
        
        if (!$data) {
            return view('attendance.qr-result', [
                'success' => false,
                'message' => 'Dữ liệu QR code không hợp lệ!'
            ]);
        }

        // Parse dữ liệu: user_id|date|token
        $parts = explode('|', $data);
        
        if (count($parts) !== 3) {
            return view('attendance.qr-result', [
                'success' => false,
                'message' => 'Định dạng QR code không đúng!'
            ]);
        }

        [$userId, $date, $token] = $parts;

        // Kiểm tra token (có thể là CSRF token hoặc token đặc biệt)
        // Ở đây đơn giản hóa, chỉ kiểm tra format
        if (empty($token) || strlen($token) < 10) {
            return view('attendance.qr-result', [
                'success' => false,
                'message' => 'Token không hợp lệ!'
            ]);
        }

        // Kiểm tra IP - so sánh IP client với IP máy chủ
        $serverIp = $this->getLocalIp();
        $clientIp = $request->ip();
        
        // Lấy IP thực từ các header nếu có proxy/load balancer
        $forwardedIp = $request->header('X-Forwarded-For');
        if ($forwardedIp) {
            $clientIp = explode(',', $forwardedIp)[0];
            $clientIp = trim($clientIp);
        }
        
        // Kiểm tra xem có cùng network không (so sánh 3 octet đầu cho subnet /24)
        $isSameNetwork = $this->isSameNetwork($serverIp, $clientIp);
        
        if (!$isSameNetwork && $serverIp !== 'localhost' && $clientIp !== '127.0.0.1' && $clientIp !== '::1') {
            // Lưu thông báo vào session để hiển thị trên máy tính
            session()->flash('qr_scan_ip_warning', [
                'message' => 'Thiết bị vừa quét QR không cùng IP với máy chủ',
                'client_ip' => $clientIp,
                'server_ip' => $serverIp,
                'timestamp' => now()->format('H:i:s'),
            ]);
            
            return view('attendance.qr-result', [
                'success' => false,
                'message' => 'Không cùng IP với máy chủ'
            ]);
        }

        // Kiểm tra ngày (chỉ cho phép quét QR code trong ngày)
        $today = now()->format('Y-m-d');
        if ($date !== $today) {
            return view('attendance.qr-result', [
                'success' => false,
                'message' => 'QR code đã hết hạn! Chỉ có hiệu lực trong ngày hôm nay.'
            ]);
        }

        // Tìm user
        $user = \App\Models\User::find($userId);
        if (!$user) {
            return view('attendance.qr-result', [
                'success' => false,
                'message' => 'Không tìm thấy nhân viên!'
            ]);
        }

        // Tự động check-in hoặc check-out
        $attendance = Attendance::where('user_id', $user->id)
            ->where('work_date', $today)
            ->first();

        $now = now();
        $workingStart = Settings::get('attendance.working_hours.start', '08:00');
        $workingEnd = Settings::get('attendance.working_hours.end', '17:00');
        $lateThreshold = (int) Settings::get('attendance.late.threshold_minutes', 15);
        $earlyThreshold = (int) Settings::get('attendance.early_leave.threshold_minutes', 15);

        if (!$attendance || !$attendance->check_in_at) {
            // Check-in
            $startTime = Carbon::parse($today . ' ' . $workingStart);
            $checkInTime = Carbon::parse($now->format('Y-m-d H:i:s'));
            
            $status = 'on_time';
            if ($checkInTime->gt($startTime->copy()->addMinutes($lateThreshold))) {
                $status = 'late';
            }

            if (!$attendance) {
                Attendance::create([
                    'user_id' => $user->id,
                    'work_date' => $today,
                    'check_in_at' => $now,
                    'check_in_method' => 'qr',
                    'check_in_ip' => $request->ip(),
                    'status' => $status,
                ]);
            } else {
                $attendance->update([
                    'check_in_at' => $now,
                    'check_in_method' => 'qr',
                    'check_in_ip' => $request->ip(),
                    'status' => $status,
                ]);
            }

            return view('attendance.qr-result', [
                'success' => true,
                'message' => 'Check-in thành công!',
                'user_name' => $user->name,
                'time' => $now->format('H:i:s'),
                'action' => 'check-in'
            ]);
        } else {
            // Check-out
            if ($attendance->check_out_at) {
                return view('attendance.qr-result', [
                    'success' => false,
                    'message' => 'Bạn đã check-out rồi!',
                    'user_name' => $user->name,
                ]);
            }

            $endTime = Carbon::parse($today . ' ' . $workingEnd);
            $checkOutTime = Carbon::parse($now->format('Y-m-d H:i:s'));
            
            $status = $attendance->status;
            if ($checkOutTime->lt($endTime->copy()->subMinutes($earlyThreshold))) {
                $status = 'early_leave';
            } elseif ($status === 'late' && $checkOutTime->gte($endTime)) {
                $status = 'late';
            } elseif ($status !== 'late') {
                $status = 'on_time';
            }

            $attendance->update([
                'check_out_at' => $now,
                'check_out_method' => 'qr',
                'check_out_ip' => $request->ip(),
                'status' => $status,
            ]);

            return view('attendance.qr-result', [
                'success' => true,
                'message' => 'Check-out thành công!',
                'user_name' => $user->name,
                'time' => $now->format('H:i:s'),
                'action' => 'check-out'
            ]);
        }
    }
}

