<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('subject');
            $table->string('schedule')->nullable();
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->nullOnDelete();
            $table->integer('capacity')->default(0);
            $table->decimal('tuition_fee', 12, 2)->default(0);
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
