<?php

namespace App\Http\Models\Buyers;

use Illuminate\Database\Eloquent\Model;
use DB;
class Buyers extends Model
{
    protected $fillable = ['first','last','email','company','phone','website','street1','street2','zip','city','state','country','bcc_emails','notes'];

    public static function getBuyers($per_page){
        $buyers  =  Buyers::select(DB::raw('count(l.id) as licensecount'), DB::raw('sum(l.seats)  as seatcount'),'buyers.*')
            ->leftjoin('licenses as l','l.buyer_id','buyers.id')
            ->groupby('buyers.id')
            ->paginate($per_page);

        return $buyers;
    }

    public static function getBuyerById($id){
        $buyer  =  Buyers::find($id);
        return $buyer;
    }
    public static function updateBuyerById($request, $id){
        Buyers::find($id)->update([
            'first'=>$request->first,
            'last'=>$request->last,
            'email'=>$request->email,
            'company'=>$request->company,
            'phone'=>$request->phone,
            'website'=>$request->website,
            'street1'=>$request->street1,
            'street2'=>$request->street2,
            'zip'=>$request->zip,
            'city'=>$request->city,
            'state'=>$request->state,
            'country'=>$request->country,
            'bcc_emails'=>$request->bcc_emails,
            'notes'=>$request->notes
        ]);
    }

    public static function getBuyersForExport($ids){
        $buyers = Buyers::whereIn('id',$ids)->select(DB::raw("CONCAT_WS(' ',first ,last) as full"),'company','email')->get()->toArray();

        $text = '';
        foreach($buyers as $buyer){
            $text .= $buyer['full'].','.$buyer['company'] .','.$buyer['email']." \r\n";
        }

        return $text;
    }
}
