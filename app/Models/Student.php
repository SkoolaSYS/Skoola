<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
    'name', 'ic', 'birth_cert_no', 'dob', 'gender', 'grade',
    'race', 'religion', 'nationality', 'orphan', 'address', 'oku',
    'state_id', 'district_id', 'school_id', 'age', 'class_name', 'status', 'session'
];

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function parents()
{
    return $this->belongsToMany(User::class, 'parent_student', 'student_id', 'parent_id')
                ->withTimestamps();
}


    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function guardians()
{
    return $this->belongsToMany(User::class, 'parent_student', 'student_id', 'parent_id')
                ->withTimestamps();
}
}
