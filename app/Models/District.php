<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Zoha\Metable;

class District extends Model
{
    use HasFactory;
    use Metable;

    public $fillable = ['state_id', 'ppd'];

    public function school()
    {
        return $this->hasMany(School::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }
}
