<?php

// ClassUser.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassUser extends Model
{
    protected $table = 'class_user';
    public $timestamps = false; // if you don't have created_at / updated_at

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id', 'id');
    }
}

