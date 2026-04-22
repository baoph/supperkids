<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date_of_birth',
        'gender',
        'parent_name',
        'parent_phone',
        'email',
        'address',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(SchoolClass::class, 'class_student', 'student_id', 'class_id')
            ->withPivot('enrolled_at')
            ->withTimestamps();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
