<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class SchoolClassSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = Teacher::all()->keyBy('specialization');

        $classes = [
            ['name' => 'Toán 6A', 'subject' => 'Toán', 'schedule' => 'T2-T4 18:00-19:30', 'capacity' => 25, 'tuition_fee' => 1500000, 'status' => 'open'],
            ['name' => 'Anh Văn Giao Tiếp 7B', 'subject' => 'Tiếng Anh', 'schedule' => 'T3-T5 18:00-19:30', 'capacity' => 20, 'tuition_fee' => 1800000, 'status' => 'open'],
            ['name' => 'Lý 8C', 'subject' => 'Vật Lý', 'schedule' => 'T4-T6 18:30-20:00', 'capacity' => 18, 'tuition_fee' => 1600000, 'status' => 'open'],
            ['name' => 'Văn 9A', 'subject' => 'Ngữ Văn', 'schedule' => 'T3-T7 17:30-19:00', 'capacity' => 22, 'tuition_fee' => 1700000, 'status' => 'open'],
        ];

        foreach ($classes as $class) {
            $teacher = $teachers->get($class['subject']);
            SchoolClass::updateOrCreate(
                ['name' => $class['name']],
                array_merge($class, ['teacher_id' => $teacher?->id])
            );
        }
    }
}
