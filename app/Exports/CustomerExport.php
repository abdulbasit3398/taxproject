<?php

namespace App\Exports;

use App\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function headings(): array
    {
        return [
            'TIN *',
            'Organization Type',
            'Organization Name',
            'First Name',
            'Middle Name',
            'Last Name',
            'Address',
            'City',
            'Country',
            'Postal',
            'Phone',
            'Email',
        ];
    }

    public function collection()
    {
        return Customer::select('customer_tin','organization_type','organization_name','name','middle_name','last_name','billing_address','billing_city','billing_country','billing_zip','contact','email')->get();
    }
}
