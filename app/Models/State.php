<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Zoha\Metable;

class State extends Model
{
    use HasFactory;
    use Metable;

    protected $fillable = ['name'];

    public function cities()
    {
        return $this->hasMany(Citie::class);
    }

    public function districts()
    {
        return $this->hasMany(District::class);
    }
}
