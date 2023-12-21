<?php

namespace App\Http\Livewire;

use App\Exports\PractitionerByProvinceExport;
use App\Models\City;
use App\Models\Practitioner;
use App\Models\PractitionerType;
use App\Models\Province;
use App\Models\Registration;
use App\Models\Renewal;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;


class PractitionerReportByProvince extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $search;
    public $orderBy = 'firstName';
    public $orderAsc = true;
    public $practitionerType;
    public $province;
    public $city;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $data = $this->fetchPractitioners();

        return view('livewire.practitioner-report-by-province', [
            'practitioners' => $data['practitioners'],
            'practitionerTypes' => PractitionerType::all(),
            'provinces' => Province::all(),
            'cities' => City::all(),
            'resultsCount' => $data['renewals']->total(),
            'renewals' => $data['renewals'],
            'startIndex' => ($data['renewals']->currentPage() - 1) * $this->perPage + 1,
            'endIndex' => min($data['renewals']->currentPage() * $this->perPage, $data['renewals']->total()),
        ]);
    }

    private function fetchPractitioners()
    {
        $query = Renewal::query()->with('registration.practitioner', 'registration.practitionerType');

        // Apply your filters and conditions here
        if ($this->practitionerType) {
            $query->whereHas('registration.practitionerType', function ($subQuery) {
                $subQuery->where('id', $this->practitionerType);
            });
        }

        if ($this->province && $this->city) {
            $query->whereHas('registration.practitioner.address', function ($subQuery) {
                $subQuery->where('province', $this->province)->where('city_id', $this->city);
            });
        } elseif ($this->province) {
            $query->whereHas('registration.practitioner.address', function ($subQuery) {
                $subQuery->where('province', $this->province);
            });
        } elseif ($this->city) {
            $query->whereHas('registration.practitioner.address', function ($subQuery) {
                $subQuery->where('city_id', $this->city);
            });
        }

        if ($this->search) {
            $searchTerm = '%' . $this->search . '%';
            $query->whereHas('registration.practitioner', function ($subQuery) use ($searchTerm) {
                $subQuery->where(function ($subQuery) use ($searchTerm) {
                    $subQuery->where('firstName', 'like', $searchTerm)
                        ->orWhere('lastName', 'like', $searchTerm);
                });
            });
        }


        $renewals = $query->where('renewalStatus', 'ACTIVE')->paginate($this->perPage);


        $practitioners = $renewals->map(function ($renewal) {
            // Map the practitioner data here
            $registration = $renewal->registration;
            $practitioner = $registration->practitioner;
            $practitionerType = $registration->practitionerType->name;


            if ($this->province && $this->city) {
                $businessAddress = $practitioner->address->where('addressType', 'RESIDENTIAL')->where('province', $this->province)->where('city_id', $this->city)->first();
                $postalAddress = $practitioner->address->where('addressType', 'BUSINESS')->where('province', $this->province)->where('city_id', $this->city)->first();
            } elseif ($this->province) {
                $businessAddress = $practitioner->address->where('addressType', 'RESIDENTIAL')->where('province', $this->province)->first();
                $postalAddress = $practitioner->address->where('addressType', 'BUSINESS')->where('province', $this->province)->first();
            } elseif ($this->city) {
                $businessAddress = $practitioner->address->where('addressType', 'RESIDENTIAL')->where('city_id', $this->city)->first();
                $postalAddress = $practitioner->address->where('addressType', 'BUSINESS')->where('city_id', $this->city)->first();
            } else {
                $businessAddress = $practitioner->address->where('addressType', 'RESIDENTIAL')->first();
                $postalAddress = $practitioner->address->where('addressType', 'BUSINESS')->first();;
            }

            $address = $businessAddress ?: $postalAddress;

            $email = $practitioner->contact()->whereHas('contactType', function ($query) {
                $query->where('name', 'Email Address');
            })->pluck('detail')->first();

            $workPhoneMobile = $practitioner->contact()->whereIn('contactType_id', function ($query) {
                $query->select('id')->from('contact_type')->whereIn('name', ['Work Phone', 'Mobile/Cell Number']);
            })->pluck('detail')->first();

            $firstName = $practitioner->firstName;
            $lastName = $practitioner->lastName;
            $province = optional($address)->province ?? null;
            $city = optional($address)->city->name ?? null;
            $addressLine1 = optional($address)->addressLine1 ?? null;
            $addressLine2 = optional($address)->addressLine2 ?? null;

            return [
                'firstName' => $firstName,
                'lastName' => $lastName,
                'practitionerType' => $practitionerType,
                'province' => $province,
                'city' => $city,
                'address' => $addressLine1 . ' ' . $addressLine2,
                'email' => $email,
                'workPhoneMobile' => $workPhoneMobile,
                'renewalStatus' => $renewal->renewalStatus,
            ];
        });

        return [
            'practitioners' => $practitioners,
            'renewals' => $renewals,
        ];
    }


    public function exportToExcel()
    {
        $practitioners = $this->fetchPractitionersWithoutPagination();

        return Excel::download(new PractitionerByProvinceExport($practitioners), 'practitioners.xlsx');
    }

    private function fetchPractitionersWithoutPagination()
    {
        $query = Renewal::query()->with('registration.practitioner', 'registration.practitionerType');

        // Apply your filters and conditions here
        if ($this->practitionerType) {
            $query->whereHas('registration.practitionerType', function ($subQuery) {
                $subQuery->where('id', $this->practitionerType);
            });
        }

        if ($this->province && $this->city) {
            $query->whereHas('registration.practitioner.address', function ($subQuery) {
                $subQuery->where('province', $this->province)->where('city_id', $this->city);
            });
        } elseif ($this->province) {
            $query->whereHas('registration.practitioner.address', function ($subQuery) {
                $subQuery->where('province', $this->province);
            });
        } elseif ($this->city) {
            $query->whereHas('registration.practitioner.address', function ($subQuery) {
                $subQuery->where('city_id', $this->city);
            });
        }

        if ($this->search) {
            $searchTerm = '%' . $this->search . '%';
            $query->whereHas('registration.practitioner', function ($subQuery) use ($searchTerm) {
                $subQuery->where(function ($subQuery) use ($searchTerm) {
                    $subQuery->where('firstName', 'like', $searchTerm)
                        ->orWhere('lastName', 'like', $searchTerm);
                });
            });
        }

        $renewals = $query->where('renewalStatus', 'ACTIVE')->get();

        $practitioners = $renewals->map(function ($renewal) {
            // Map the practitioner data here
            $registration = $renewal->registration;
            $practitioner = $registration->practitioner;
            $practitionerType = $registration->practitionerType->name;

            if ($this->province && $this->city) {
                $businessAddress = $practitioner->address->where('addressType', 'RESIDENTIAL')->where('province', $this->province)->where('city_id', $this->city)->first();
                $postalAddress = $practitioner->address->where('addressType', 'BUSINESS')->where('province', $this->province)->where('city_id', $this->city)->first();
            } elseif ($this->province) {
                $businessAddress = $practitioner->address->where('addressType', 'RESIDENTIAL')->where('province', $this->province)->first();
                $postalAddress = $practitioner->address->where('addressType', 'BUSINESS')->where('province', $this->province)->first();
            } elseif ($this->city) {
                $businessAddress = $practitioner->address->where('addressType', 'RESIDENTIAL')->where('city_id', $this->city)->first();
                $postalAddress = $practitioner->address->where('addressType', 'BUSINESS')->where('city_id', $this->city)->first();
            } else {
                $businessAddress = $practitioner->address->where('addressType', 'RESIDENTIAL')->first();
                $postalAddress = $practitioner->address->where('addressType', 'BUSINESS')->first();
            }
            $address = $businessAddress ?: $postalAddress;

            $email = $practitioner->contact()->whereHas('contactType', function ($query) {
                $query->where('name', 'Email Address');
            })->pluck('detail')->first();

            $workPhoneMobile = $practitioner->contact()->whereIn('contactType_id', function ($query) {
                $query->select('id')->from('contact_type')->whereIn('name', ['Work Phone', 'Mobile/Cell Number']);
            })->pluck('detail')->first();

            $firstName = $practitioner->firstName;
            $lastName = $practitioner->lastName;
            $province = optional($address)->province ?? null;
            $city = optional($address)->city->name ?? null;
            $addressLine1 = optional($address)->addressLine1 ?? null;
            $addressLine2 = optional($address)->addressLine2 ?? null;

            return [
                'firstName' => $firstName,
                'lastName' => $lastName,
                'practitionerType' => $practitionerType,
                'province' => $province,
                'city' => $city,
                'address' => $addressLine1 . ' ' . $addressLine2,
                'email' => $email,
                'workPhoneMobile' => $workPhoneMobile,
                'renewalStatus' => $renewal->renewalStatus,
            ];
        });

        return $practitioners;
    }

}
