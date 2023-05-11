<?php

namespace App\Http\Livewire;

use App\Exports\PractitionerByProvinceExport;
use App\Models\City;
use App\Models\Practitioner;
use App\Models\PractitionerType;
use App\Models\Province;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

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

    public function exportToExcel()
    {
        $practitioners = Practitioner::filter(
            $this->practitionerType,
            $this->province,
            $this->city
        )->get();
        return Excel::download(new PractitionerByProvinceExport($practitioners), 'practitioners.xlsx');
    }

    public function render()
    {

        $fileName = 'practitioners.xlsx';

        if (!empty($this->practitionerType || $this->province || $this->city)) {
            $practitioners = Practitioner::with('lastRegistration')
                ->when($this->practitionerType, function ($query) {
                    $query->whereHas('lastRegistration', function ($query) {
                        $query->where('practitionerType_id', $this->practitionerType)
                            ->withActiveRenewal();
                    })->with(['lastRegistration' => function ($query) {
                        $query->with('practitionerType');
                    }])->when($this->province, function ($query) {
                            $query->whereHas('address', function ($query) {
                                $query->where('province', $this->province);
                            });
                        })
                        ->when($this->city, function ($query) {
                            $query->whereHas('address', function ($query) {
                                $query->where('city_id', $this->city);
                            });
                        });
                })
                ->when($this->orderBy && $this->orderAsc, function ($query) {
                    $query->orderBy($this->orderBy);
                }, function ($query) {
                    $query->orderByDesc($this->orderBy);
                })
                ->paginate($this->perPage);

            $resultsCount = $practitioners->total();
            $paginationLinks = $practitioners->links();
        } else {
            $resultsCount = 0;
            $paginationLinks = null;
            $practitioners = collect([]);
        }

        return view('livewire.practitioner-report-by-province', [
            'practitioners' => $practitioners->isEmpty() ? null : $practitioners,
            'practitionerTypes' => PractitionerType::all(),
            'provinces' => Province::all(),
            'cities' => City::all(),
            'resultsCount' => $resultsCount,
            'paginationLinks' => $paginationLinks,
        ]);
    }
}
