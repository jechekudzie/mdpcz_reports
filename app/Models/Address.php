<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $table = 'address';

    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function practitioner()
    {
        return $this->belongsTo(Practitioner::class);
    }
}
