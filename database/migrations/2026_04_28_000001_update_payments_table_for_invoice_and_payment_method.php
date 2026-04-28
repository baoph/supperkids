<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->renameColumn('invoice_code', 'invoice_number');
            $table->dropColumn('payment_due_date');
            $table->enum('payment_method', ['cash', 'transfer'])->default('cash')->after('paid_amount');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('payment_method');
            $table->date('payment_due_date')->after('paid_amount');
            $table->renameColumn('invoice_number', 'invoice_code');
        });
    }
};
