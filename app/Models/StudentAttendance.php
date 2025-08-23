<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    use HasFactory;

    public $fillable = ['date', 'total_student', 'total_attend', 'total_absent', 'school_id'];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
}
