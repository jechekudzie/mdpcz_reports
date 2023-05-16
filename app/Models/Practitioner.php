<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Practitioner extends Model
{
    use HasFactory;

    protected $table = 'practitioner';

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'practitioner_id');
    }

    public function lastRegistration()
    {
        return $this->hasOne(Registration::class)->latestOfMany();
    }

    public function address()
    {
        return $this->hasMany(Address::class);
    }

    public function contact()
    {
        return $this->hasMany(Contact::class, 'practitioner_id');
    }


}
