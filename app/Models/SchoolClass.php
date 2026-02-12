<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    protected $fillable = ['school_grade_id', 'class_name'];

    public function grade()
    {
        return $this->belongsTo(SchoolGrade::class, 'school_grade_id');
    }

    public function teachers()
{
    return $this->belongsToMany(User::class, 'class_user');
}



}
