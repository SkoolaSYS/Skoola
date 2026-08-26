<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    use HasFactory;

        protected $fillable = [
        'student_id',
        'type',
        'name',
        'ic',
        'phone',
        'email',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
