<?php

namespace App\Exports;

use App\Attendee;
use Maatwebsite\Excel\Concerns\FromCollection;

class AttendeesExport implements FromCollection

{

    /**

    * @return \Illuminate\Support\Collection

    */

    public function collection()

    {

        return Attendee::all();

    }

}
