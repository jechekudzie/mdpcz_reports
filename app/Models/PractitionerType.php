<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PractitionerType extends Model
{
    use HasFactory;

    protected $table = 'practitioner_type';

    public function registrations()
    {
        return $this->hasMany(Registration::class,'practitionerType_id');
    }
}
