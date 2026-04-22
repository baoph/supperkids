<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete();
            $table->string('invoice_code')->unique();
            $table->decimal('amount', 12, 2);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->date('payment_due_date');
            $table->date('payment_date')->nullable();
            $table->enum('status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
