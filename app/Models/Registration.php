<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $table = 'registration';

    public function practitioner()
    {
        return $this->belongsTo(Practitioner::class);
    }

    public function practitionerType()
    {
        return $this->belongsTo(PractitionerType::class,'practitionerType_id');
    }

    public function renewals()
    {
        return $this->hasMany(Renewal::class);
    }

    public function scopeLatest($query)
    {
        return $query->latest('id');
    }

    public function scopeWithActiveRenewal($query)
    {
        return $query->whereHas('renewals', function ($query) {
            $query->where('renewalStatus', 'ACTIVE');
        });
    }



}
