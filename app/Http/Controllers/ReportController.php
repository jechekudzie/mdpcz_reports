<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\City;
use App\Models\Practitioner;
use App\Models\PractitionerType;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    //
    public function index()
    {
        $provinces = Province::all();
        $cities = City::all();
        $practitioner_types = PractitionerType::all();
        return view('reports.index', compact('provinces', 'cities', 'practitioner_types'));
    }

    public function get_report()
    {
        $provinces = Province::all();
        $cities = City::all();
        $practitioner_types = PractitionerType::all();

        $practitioners = Practitioner::all();


    }

    public function random_update(Request $request)
    {

        $practitioner = Practitioner::find(9065);
        $lastRegistration = $practitioner->registration;

        $activeRenewal = $lastRegistration->renewals()->where('renewalStatus', 'ACTIVE')->first();
        $practitionerTypeName = $lastRegistration->practitionerType->name;

        dd($activeRenewal->renewalStatus);

        // Get an array of all province names
        /*$provinces = Province::pluck('name')->toArray();
        // Get all addresses where province column is null
        $addresses = Address::whereNull('province')->get();

        foreach ($addresses as $address) {
            // Generate a random province name
            $randomProvince = $provinces[array_rand($provinces)];

            // Update the address record with the random province name
            DB::table('address')
                ->where('id', $address->id)
                ->update(['province' => $randomProvince]);
        }*/

        //return 'done';
    }


}
