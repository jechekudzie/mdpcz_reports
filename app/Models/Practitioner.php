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
        // filter by practitioner type
        $query->when($practitionerType, function ($query, $practitionerType) {
            $query->whereHas('lastRegistration', function ($query) use ($practitionerType) {
                $query->where('practitionerType_id', $practitionerType)
                    ->withActiveRenewal();
            })->with(['lastRegistration' => function ($query) {
                $query->with('practitionerType');
            }]);
        });

        // filter by province
        $query->when(!empty($province), function ($query) use ($province) {
            $query->whereHas('address', function ($query) use ($province) {
                $query->where('province', $province);
            });
        });

        // filter by city
        $query->when(!empty($city), function ($query) use ($city) {
            $query->whereHas('address', function ($query) use ($city) {
                $query->where('city_id', $city);
            });
        });

        // filter by province && city
        $query->when($province && $city, function ($query) use ($province, $city) {
            $query->whereHas('address', function ($query) use ($province, $city) {
                $query->where('province', $province)->where('city_id', $city);
                $query->where('addressType', 'BUSINESS');
            });
        });

        return $query;
    }

    public function scopeWithActiveRenewal($query)
    {
        return $query->whereHas('lastRegistration.renewal', function ($query) {
            $query->where('renewalStatus', 'ACTIVE');
        });
    }

}
