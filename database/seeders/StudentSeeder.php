<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            ['name' => 'Nguyễn Gia Huy', 'cccd' => '079201000001', 'parent_name' => 'Nguyễn Văn Long', 'parent_phone' => '0912000001', 'email' => 'giahuy@student.vn', 'address' => 'Quận 1, TP.HCM', 'status' => 'studying'],
            ['name' => 'Trần Minh Châu', 'cccd' => '079201000002', 'parent_name' => 'Trần Thu Trang', 'parent_phone' => '0912000002', 'email' => 'minhchau@student.vn', 'address' => 'Quận 3, TP.HCM', 'status' => 'studying'],
            ['name' => 'Lê Hoàng Nam', 'cccd' => null, 'parent_name' => 'Lê Mạnh Hùng', 'parent_phone' => '0912000003', 'email' => 'hoangnam@student.vn', 'address' => 'Quận 7, TP.HCM', 'status' => 'new'],
            ['name' => 'Phạm Ngọc Ánh', 'cccd' => '079201000004', 'parent_name' => 'Phạm Thu Hạnh', 'parent_phone' => '0912000004', 'email' => 'ngocanh@student.vn', 'address' => 'TP Thủ Đức, TP.HCM', 'status' => 'studying'],
            ['name' => 'Võ Khánh Linh', 'cccd' => null, 'parent_name' => 'Võ Thành Nam', 'parent_phone' => '0912000005', 'email' => 'khanhlinh@student.vn', 'address' => 'Quận Bình Thạnh, TP.HCM', 'status' => 'inactive'],
        ];

        foreach ($students as $row) {
            Student::updateOrCreate(['email' => $row['email']], $row);
        }

        $classIds = SchoolClass::pluck('id')->toArray();
        Student::all()->each(function (Student $student) use ($classIds) {
            if (empty($classIds)) {
                return;
            }

            $pick = collect($classIds)->shuffle()->take(rand(1, min(2, count($classIds))))->all();
            $sync = [];
            foreach ($pick as $classId) {
                $sync[$classId] = ['enrolled_at' => now()->subDays(rand(10, 90))->toDateString()];
            }
            $student->classes()->sync($sync);
        });
    }
}
