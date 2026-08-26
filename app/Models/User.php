<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Zoha\Metable;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    use Metable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public function state()
{
    return $this->belongsTo(State::class, 'state_id');
}

public function citie()
{
    return $this->belongsTo(Citie::class, 'citie_id');
}

public function postcode()
{
    return $this->belongsTo(Postcode::class, 'postcode_id');
}

public function school()
{
    return $this->belongsTo(School::class, 'school_id');
}


    //public function students()
//{
   // return $this->belongsToMany(Student::class, 'parent_student', 'parent_id', 'student_id')
    //            ->withTimestamps();
//}

    public function students()
{
    return $this->hasManyThrough(
        \App\Models\Student::class,   // Final model
        \App\Models\Guardian::class,  // Intermediate model
        'ic',                         // Guardians.ic (foreign key to users.ic)
        'id',                         // Students.id (local key on final model)
        'ic',                         // Users.ic (local key on users)
        'student_id'                  // Guardians.student_id (foreign key to students)
    );
}

// Additional students linked via pivot (secondary guardian)
public function extraStudents()
{
    return $this->belongsToMany(Student::class, 'parent_student', 'parent_id', 'student_id')
                ->withTimestamps();
}

// Get all students of this parent (primary + extra via pivot)
public function allStudents()
{
    // Now everything comes from the pivot
    return $this->belongsToMany(Student::class, 'parent_student', 'parent_id', 'student_id')
                ->withTimestamps()
                ->get();
}


// Get additional guardians (other parents linked to the same students)
public function additionalGuardians()
{
    $studentIds = $this->students()->pluck('students.id');

    return User::whereHas('students', function($q) use ($studentIds) {
        $q->whereIn('student_id', $studentIds);
    })
    ->where('id', '!=', $this->id) // exclude this parent
    ->distinct()
    ->get();
}

public function classes()
{
    return $this->belongsToMany(SchoolClass::class, 'class_user', 'user_id', 'school_class_id');
}

    public function guardians()
    {
        return $this->hasMany(Guardian::class, 'parent_id');
    }




//public function guardians()
//{
    // A student can have many parents/guardians
    //return $this->belongsToMany(User::class, 'parent_student', 'student_id', 'parent_id')
      //          ->withTimestamps();
//}

// main students (parent_id column in students table)



    protected $fillable = [
        'name',
        'email',
        'phone_num',
        'ic',
        'password',
        'address',
        'state_id',
        'citie_id',
        'postcode_id',
        'relationship',
        'occupation',
        'username',
        'teacher_id',
        'school_id',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];
}
