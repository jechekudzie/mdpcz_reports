<?php

namespace App\Exports;

use App\Models\Practitioner;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PractitionerByProvinceExport implements FromCollection, WithHeadings
{
    private $practitioners;
    private $headers;

    public function __construct(Collection $practitioners, array $headers = [])
    {
        $this->practitioners = $practitioners;
        $this->headers = $headers;
    }

    public function collection()
    {
        return $this->practitioners->map(function($item) {
            return [
                $item->firstName.' '.$item->lastName,
                $item->lastRegistration->practitionerType->name ?? '',
                $item->address->province ?? '',
                $item->address->city->name ?? '',
                ($item->address->addressLine1 ?? '') . ' ' . ($item->address->addressLine2 ?? ''),
            ];
        });
    }

    public function headings(): array
    {
        if (count($this->headers) > 0) {
            return $this->headers;
        }

        return ['Full name', 'Profession', 'Province', 'City','Address'];
    }
}
