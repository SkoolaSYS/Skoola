<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postcode extends Model
{
    use HasFactory;
    protected $fillable = [
        'citie_id',
        'name'
    ];
    public function citie()
    {
        return $this->belongsTo(Citie::class);
    }
}
