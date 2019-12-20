<?php

namespace App\Http\Models\License;

use Illuminate\Database\Eloquent\Model;

class Seats extends Model
{
    protected $table = 'seats';

    /**
     * Get list of seats for assigned license
     * @param  int $licenseID The license ID
     * @return JObjectList The corresponding seats
     */
    public function getLicenseSeats($licenseID) {

        $seats = Seats::whereLicenseId($licenseID)->get()->toArray();

        return $seats;
    }
}
