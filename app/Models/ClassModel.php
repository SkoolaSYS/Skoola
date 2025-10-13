<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'class_attendances';

    protected $fillable = [
        'student_id',
        'teacher_id',
        'grade',
        'class_name',
        'subject',
        'status',
        'attendance_time',
    ];

    // Relation: class has many students
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher()
{
    return $this->belongsTo(User::class, 'teacher_id');
}
}
