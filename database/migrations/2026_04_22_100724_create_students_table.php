<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('cccd', 20)->nullable();
            $table->string('parent_name');
            $table->string('parent_phone', 20);
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->enum('status', ['new', 'studying', 'inactive'])->default('new');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
