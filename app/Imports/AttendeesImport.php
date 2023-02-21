<?php

namespace App\Imports;

use App\Attendee;
use Maatwebsite\Excel\Concerns\ToModel;

class AttendeesImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Attendee([
          'first_name'     => $row[0],
          'last_name'    => $row[1],
          'email'    => $row[2],
          'enveloppe'    => $row[3],
          'company'    => $row[4],
          'sender'    => $row[5],
        ]);


    }
}
