<?php

namespace App\Http\Models\Front\Buyers;

use Illuminate\Database\Eloquent\Model;

class Buyers extends Model
{
    protected $fillable = ['first','last','email','company','phone','website','street1','street2','zip','city','state','country','bcc_emails','notes'];
    //
}
