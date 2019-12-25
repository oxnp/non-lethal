<?php

namespace App\Http\Models\Front\Buyers;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Buyers extends Model
{
    protected $fillable = ['first','last','email','company','phone','website','street1','street2','zip','city','state','country','bcc_emails','notes','user_id'];
    //

    public static function buyerLookupByMail($email){
        $buyer = Buyers::whereEmail($email)->get()->toArray();
        return $buyer;
    }
    /**
     * Returns a single Buyer of the old activation component
     * @param type $buyerID
     * @return type
     */
    public static function getOldBuyer($buyerID){
        $buyer = Buyers::find($buyerID);
        return $buyer;
    }

    public static function getBuyer(){
        $buyer = Buyers::whereUserId(Auth::ID())->get();
        if($buyer->isEmpty()){
            $buyer = User::whereId(Auth::ID())->get();
        }
        //dd($buyer);
        return $buyer;
    }
}
