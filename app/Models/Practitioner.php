<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Practitioner extends Model
{
    use HasFactory;

    protected $table = 'practitioner';


    public function registration()
    {
        return $this->hasMany(Registration::class, 'practitioner_id');
    }

    public function lastRegistration()
    {
        return $this->hasOne(Registration::class, 'practitioner_id')->latest('id');
    }

    public function address()
    {
        return $this->hasOne(Address::class);
        //return $this->hasOne(Address::class)->where('addressType', 'BUSINESS');
    }

    public function scopeFilter($query, $practitionerType = null, $province = null, $city = null)
    {
        return $query
            ->when($practitionerType, function ($query) use ($practitionerType) {
                return $query->whereHas('registration', function ($query) use ($practitionerType) {
                    $query->where('practitionerType_id', $practitionerType);
                })->with(['registration' => function ($query) {
                    $query->latest('id')->withActiveRenewal();
                }]);
            })
            ->when($province || $city, function ($query) use ($province, $city) {
                return $query->whereHas('address', function ($query) use ($province, $city) {
                    $query->where('province', $province);
                    if ($city) {
                        $query->where('city_id', $city);
                    }
                });
            });
    }


    public function scopeWithActiveRenewal($query)
    {
        return $query->whereHas('lastRegistration.renewal', function ($query) {
            $query->where('renewalStatus', 'ACTIVE');
        });
    }

}
