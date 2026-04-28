<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'class_id',
        'invoice_number',
        'amount',
        'paid_amount',
        'payment_method',
        'payment_date',
        'status',
        'note',
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (Payment $payment): void {
            if (empty($payment->invoice_number)) {
                $payment->invoice_number = self::generateInvoiceNumber();
            }
        });
    }

    public static function generateInvoiceNumber(): string
    {
        $prefix = 'INV-' . now()->format('Ymd') . '-';

        $lastInvoice = self::query()
            ->where('invoice_number', 'like', $prefix . '%')
            ->orderByDesc('invoice_number')
            ->value('invoice_number');

        $lastSequence = 0;

        if ($lastInvoice) {
            $lastSequence = (int) substr($lastInvoice, -4);
        }

        return $prefix . str_pad((string) ($lastSequence + 1), 4, '0', STR_PAD_LEFT);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}
