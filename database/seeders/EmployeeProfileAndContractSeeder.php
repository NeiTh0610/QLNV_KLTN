<?php

namespace Database\Seeders;

use App\Models\Contract;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EmployeeProfileAndContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        
        // Lấy admin user để làm created_by
        $admin = User::whereHas('roles', function($q) {
            $q->where('name', 'admin');
        })->first();

        // Lấy tất cả nhân viên (employee và part_time)
        $employees = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['employee', 'part_time']);
        })->get();

        // Dữ liệu mẫu cho hồ sơ
        $profileData = [
            [
                'id_number' => '001234567890',
                'date_of_birth' => '1990-05-15',
                'gender' => 'male',
                'address' => '123 Đường Nguyễn Văn Linh, Quận 7, TP.HCM',
                'personal_email' => 'nguyenvana.personal@gmail.com',
                'emergency_contact_name' => 'Nguyễn Văn Bố',
                'emergency_contact_phone' => '0912000001',
                'education_level' => 'Đại học',
                'major' => 'Quản trị Nhân sự',
                'university' => 'Đại học Kinh tế TP.HCM',
                'years_of_experience' => 3,
                'skills' => 'Quản lý nhân sự, Tuyển dụng, Đào tạo, Quan hệ lao động',
                'certifications' => 'Chứng chỉ Quản trị Nhân sự Quốc tế (SHRM)',
                'previous_work' => 'Công ty ABC - Chuyên viên Nhân sự (2020-2022)',
            ],
            [
                'id_number' => '001234567891',
                'date_of_birth' => '1992-08-20',
                'gender' => 'female',
                'address' => '456 Đường Lê Văn Việt, Quận 9, TP.HCM',
                'personal_email' => 'tranthib.personal@gmail.com',
                'emergency_contact_name' => 'Trần Thị Mẹ',
                'emergency_contact_phone' => '0912000002',
                'education_level' => 'Đại học',
                'major' => 'Công nghệ Thông tin',
                'university' => 'Đại học Bách Khoa TP.HCM',
                'years_of_experience' => 2,
                'skills' => 'PHP, Laravel, JavaScript, Vue.js, MySQL, Git',
                'certifications' => 'AWS Certified Developer, Laravel Certified Developer',
                'previous_work' => 'Công ty XYZ - Junior Developer (2021-2023)',
            ],
            [
                'id_number' => '001234567892',
                'date_of_birth' => '1988-12-10',
                'gender' => 'male',
                'address' => '789 Đường Võ Văn Tần, Quận 3, TP.HCM',
                'personal_email' => 'levanc.personal@gmail.com',
                'emergency_contact_name' => 'Lê Văn Anh',
                'emergency_contact_phone' => '0912000003',
                'education_level' => 'Đại học',
                'major' => 'Kế toán',
                'university' => 'Đại học Kinh tế TP.HCM',
                'years_of_experience' => 5,
                'skills' => 'Kế toán tài chính, Thuế, Báo cáo tài chính, Excel nâng cao',
                'certifications' => 'Chứng chỉ Kế toán viên công chứng (CPA)',
                'previous_work' => 'Công ty DEF - Kế toán trưởng (2018-2023)',
            ],
            [
                'id_number' => '001234567893',
                'date_of_birth' => '1995-03-25',
                'gender' => 'female',
                'address' => '321 Đường Nguyễn Thị Minh Khai, Quận 1, TP.HCM',
                'personal_email' => 'phamthid.personal@gmail.com',
                'emergency_contact_name' => 'Phạm Thị Chị',
                'emergency_contact_phone' => '0912000004',
                'education_level' => 'Cao đẳng',
                'major' => 'Hành chính Văn phòng',
                'university' => 'Cao đẳng Kinh tế TP.HCM',
                'years_of_experience' => 1,
                'skills' => 'Quản lý văn phòng, Soạn thảo văn bản, Tổ chức sự kiện',
                'certifications' => 'Chứng chỉ Tin học văn phòng MOS',
                'previous_work' => 'Công ty GHI - Nhân viên Hành chính (2022-2023)',
            ],
            [
                'id_number' => '001234567894',
                'date_of_birth' => '1991-07-18',
                'gender' => 'male',
                'address' => '654 Đường Điện Biên Phủ, Quận Bình Thạnh, TP.HCM',
                'personal_email' => 'dovane.personal@gmail.com',
                'emergency_contact_name' => 'Đỗ Văn Em',
                'emergency_contact_phone' => '0912000005',
                'education_level' => 'Đại học',
                'major' => 'Marketing',
                'university' => 'Đại học Kinh tế TP.HCM',
                'years_of_experience' => 4,
                'skills' => 'Digital Marketing, SEO, Google Ads, Facebook Ads, Content Marketing',
                'certifications' => 'Google Ads Certified, Facebook Blueprint Certified',
                'previous_work' => 'Công ty JKL - Chuyên viên Marketing (2019-2023)',
            ],
            [
                'id_number' => '001234567895',
                'date_of_birth' => '1998-11-30',
                'gender' => 'male',
                'address' => '987 Đường Cộng Hòa, Quận Tân Bình, TP.HCM',
                'personal_email' => 'vuminhf.personal@gmail.com',
                'emergency_contact_name' => 'Vũ Minh Bạn',
                'emergency_contact_phone' => '0912000006',
                'education_level' => 'Cao đẳng',
                'major' => 'Công nghệ Thông tin',
                'university' => 'Cao đẳng Công nghệ Thông tin TP.HCM',
                'years_of_experience' => 0,
                'skills' => 'HTML, CSS, JavaScript cơ bản, WordPress',
                'certifications' => null,
                'previous_work' => null,
            ],
        ];

        // Cập nhật hồ sơ cho từng nhân viên
        foreach ($employees as $index => $employee) {
            $profileInfo = $profileData[$index % count($profileData)] ?? $profileData[0];
            
            // Cập nhật user_profiles với thông tin đầy đủ
            DB::table('user_profiles')
                ->where('user_id', $employee->id)
                ->update([
                    'id_number' => $profileInfo['id_number'],
                    'date_of_birth' => $profileInfo['date_of_birth'],
                    'gender' => $profileInfo['gender'],
                    'address' => $profileInfo['address'],
                    'personal_email' => $profileInfo['personal_email'],
                    'emergency_contact_name' => $profileInfo['emergency_contact_name'],
                    'emergency_contact_phone' => $profileInfo['emergency_contact_phone'],
                    'education_level' => $profileInfo['education_level'],
                    'major' => $profileInfo['major'],
                    'university' => $profileInfo['university'],
                    'years_of_experience' => $profileInfo['years_of_experience'],
                    'skills' => $profileInfo['skills'],
                    'certifications' => $profileInfo['certifications'],
                    'previous_work' => $profileInfo['previous_work'],
                    'updated_at' => $now,
                ]);

            // Lấy thông tin profile để xác định loại hợp đồng
            $employee->load('profile', 'roles');
            $profile = $employee->profile;
            $isPartTime = $employee->roles->contains('name', 'part_time');
            
            // Xác định loại hợp đồng và lương
            $contractType = $isPartTime ? 'part_time' : 'full_time';
            $salary = $isPartTime ? 45000 : 10000000; // Part-time: 45k/giờ, Full-time: 10 triệu/tháng
            
            // Tạo hợp đồng cho nhân viên
            $startDate = $employee->hired_at 
                ? Carbon::parse($employee->hired_at) 
                : Carbon::now()->subMonths(rand(1, 24));
            $endDate = $isPartTime ? null : $startDate->copy()->addYears(2); // Part-time không có ngày kết thúc
            
            $contractNumber = 'HD-' . strtoupper(Str::random(8)) . '-' . $startDate->format('Y');
            
            $position = $profile ? ($profile->position ?? 'Nhân viên') : 'Nhân viên';
            
            Contract::updateOrCreate(
                [
                    'user_id' => $employee->id,
                ],
                [
                    'contract_number' => $contractNumber,
                    'contract_type' => $contractType,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'salary' => $salary,
                    'position' => $position,
                    'job_description' => $this->getJobDescription($position, $contractType),
                    'benefits' => $this->getBenefits($contractType),
                    'status' => 'active',
                    'signed_date' => $startDate->copy()->subDays(rand(1, 7)),
                    'notes' => 'Hợp đồng được tạo tự động từ seeder',
                    'created_by' => $admin->id ?? null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $this->command->info('Đã cập nhật hồ sơ và tạo hợp đồng cho ' . $employees->count() . ' nhân viên.');
    }

    /**
     * Lấy mô tả công việc theo chức vụ
     */
    private function getJobDescription(string $position, string $contractType): string
    {
        $descriptions = [
            'Chuyên viên nhân sự' => 'Thực hiện các công việc tuyển dụng, quản lý hồ sơ nhân viên, xử lý các vấn đề về nhân sự, tổ chức đào tạo và phát triển nhân viên.',
            'Kỹ sư phần mềm' => 'Phát triển và bảo trì các ứng dụng web, tham gia vào quá trình thiết kế và triển khai hệ thống, đảm bảo chất lượng code và hiệu suất ứng dụng.',
            'Kế toán viên' => 'Thực hiện các công việc kế toán, lập báo cáo tài chính, quản lý sổ sách kế toán, xử lý các vấn đề về thuế và tài chính.',
            'Nhân viên hành chính' => 'Quản lý văn phòng, soạn thảo văn bản, tổ chức sự kiện, hỗ trợ các phòng ban khác trong công ty.',
            'Chuyên viên kinh doanh' => 'Tìm kiếm và phát triển khách hàng mới, duy trì mối quan hệ với khách hàng hiện tại, đạt được các chỉ tiêu doanh số.',
            'Nhân viên hỗ trợ part-time' => 'Hỗ trợ các công việc hành chính, trả lời điện thoại, tiếp đón khách, hỗ trợ các phòng ban khi cần.',
        ];

        return $descriptions[$position] ?? 'Thực hiện các công việc được giao theo yêu cầu của cấp trên và đảm bảo hoàn thành đúng thời hạn.';
    }

    /**
     * Lấy phúc lợi theo loại hợp đồng
     */
    private function getBenefits(string $contractType): string
    {
        if ($contractType === 'part_time') {
            return 'Lương theo giờ làm việc, làm việc linh hoạt theo ca, hỗ trợ ăn trưa.';
        }

        return 'Lương tháng 13, Bảo hiểm đầy đủ (BHXH, BHYT, BHTN), Nghỉ phép có lương, Đào tạo và phát triển kỹ năng, Môi trường làm việc chuyên nghiệp, Cơ hội thăng tiến.';
    }
}
