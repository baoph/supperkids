<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        Payment::query()->delete();

        $students = Student::with('classes')->get();

        foreach ($students as $index => $student) {
            $class = $student->classes->first();
            $amount = $class?->tuition_fee ?? 1500000;

            $paidAmount = match ($index % 3) {
                0 => $amount,
                1 => $amount / 2,
                default => 0,
            };

            $status = match (true) {
                $paidAmount == 0 => 'unpaid',
                $paidAmount < $amount => 'partial',
                default => 'paid',
            };

            Payment::create([
                'student_id' => $student->id,
                'class_id' => $class?->id,
                'invoice_number' => Payment::generateInvoiceNumber(),
                'amount' => $amount,
                'paid_amount' => $paidAmount,
                'payment_method' => $index % 2 === 0 ? 'cash' : 'transfer',
                'payment_date' => $status === 'paid' ? now()->startOfMonth()->addDays(8)->toDateString() : null,
                'status' => $status,
                'note' => $status === 'unpaid' ? 'Cần nhắc phụ huynh đóng học phí.' : null,
            ]);
        }
    }
}
