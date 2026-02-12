<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolGrade extends Model
{
    protected $fillable = [
        'school_id',
        'grade_name',
        'is_active',
    ];

    public function classes()
{
    return $this->hasMany(SchoolClass::class, 'school_grade_id');
}

}
