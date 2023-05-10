<?php

namespace App\Http\Livewire;

use App\Models\City;
use App\Models\Practitioner;
use App\Models\PractitionerType;
use App\Models\Province;
use App\Models\Registration;
use App\Models\Speciality;
use Livewire\Component;
use Livewire\WithPagination;

class PractitionerReportByProvince extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $search;
    public $orderBy = 'firstName';
    public $orderAsc = true;
    public $specialty;

    public $province;
    public $city;
    public $practitionerType;
    //public $registration;

    public $exporting = false; // Define the $exporting property

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        $practitioners = collect([]);

        $practitioners = Practitioner::with('lastRegistration')
            ->filter($this->practitionerType, $this->province, $this->city)
            ->whereHas('lastRegistration', function ($query) {
                $query->withActiveRenewal();
            })
            ->orderBy('id', $this->orderAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);




        return view('livewire.practitioner-report-by-province',
            [
                'practitioners' => $practitioners,
                'practitionerTypes' => PractitionerType::all(),
                'provinces' => Province::all(),
                'cities' => City::all(),
            ]
        );
    }
}
