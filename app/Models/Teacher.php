<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'specialization',
        'phone',
        'email',
        'salary',
        'teaching_schedule',
        'status',
    ];

    public function classes(): HasMany
    {
        return $this->hasMany(SchoolClass::class, 'teacher_id');
    }
}
