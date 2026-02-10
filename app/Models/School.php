<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Zoha\Metable;

class School extends Model
{
    use HasFactory;
    use Metable;

    public $fillable = ['state_id', 'district_id', 'name'];

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function studentAttendance()
    {
        return $this->hasMany(StudentAttendance::class, 'school_id');
    }

    public function student()
    {
        return $this->hasMany(Student::class);
    }

    // New relationship specifically for management page
    public function allStudents()
    {
        return $this->hasMany(Student::class);
    }
}

