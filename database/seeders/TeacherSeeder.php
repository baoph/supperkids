<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = [
            ['name' => 'Nguyễn Minh Anh', 'specialization' => 'Toán', 'phone' => '0901000001', 'email' => 'minhanh.teacher@supperkids.vn', 'salary' => 12000000, 'teaching_schedule' => 'T2-T4-T6 18:00-20:00', 'status' => 'active'],
            ['name' => 'Trần Thu Hà', 'specialization' => 'Tiếng Anh', 'phone' => '0901000002', 'email' => 'thuha.teacher@supperkids.vn', 'salary' => 13000000, 'teaching_schedule' => 'T3-T5-T7 18:00-20:00', 'status' => 'active'],
            ['name' => 'Lê Quốc Bảo', 'specialization' => 'Vật Lý', 'phone' => '0901000003', 'email' => 'quocbao.teacher@supperkids.vn', 'salary' => 11500000, 'teaching_schedule' => 'T2-T4-CN 18:00-20:00', 'status' => 'active'],
            ['name' => 'Phạm Hải Yến', 'specialization' => 'Ngữ Văn', 'phone' => '0901000004', 'email' => 'haiyen.teacher@supperkids.vn', 'salary' => 11000000, 'teaching_schedule' => 'T3-T6-CN 17:30-19:30', 'status' => 'active'],
        ];

        foreach ($teachers as $teacher) {
            Teacher::updateOrCreate(['email' => $teacher['email']], $teacher);
        }
    }
}
