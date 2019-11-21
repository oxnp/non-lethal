<?php

namespace App\Http\Models\Buyers;

use App\User;
use Carbon\Carbon;
use http\Env\Request;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Hash;

class Buyers extends Model
{
    protected $fillable = ['first','last','email','company','phone','website','street1','street2','zip','city','state','country','bcc_emails','notes'];
    /**
     * Get buyers
     * @param   array  $per_page
     * @return collection
     */
    public static function getBuyers($per_page){
        $buyers  =  Buyers::select(DB::raw('count(l.id) as licensecount'), DB::raw('sum(l.seats)  as seatcount'),'buyers.*')
            ->leftjoin('licenses as l','l.buyer_id','buyers.id')
            ->groupby('buyers.id')
            ->paginate($per_page);
        return $buyers;
    }
    /**
     * Get buyer By ID
     * @param   array   $request
     * @return collection
     */
    public static function getBuyerById($id){
        $buyer  =  Buyers::find($id);
        return $buyer;
    }
    /**
     * Add buyers and register new user on system
     * @param   Request   $request
     * @return
     */
    public static function addBuyer($request){
        $pwd = str_random(10);
        $user_laravel = new User();
        $user_laravel->name = $request->first;
        $user_laravel->username = $request->email;
        $user_laravel->email = $request->email;
        $user_laravel->email = $request->email;
        $user_laravel->password = $pwd;
        $user_laravel->registerDate = Carbon::now();
        $user_laravel->activation = '';
        $user_laravel->params = '{}';
        $user_laravel->otpKey = '';
        $user_laravel->otep = '';
        $user_laravel->requireReset = 0;

        $user_laravel->save();

        Buyers::insert([
            'user_id'=>$user_laravel->id,
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
    /**
     * Update buyers by ID
     * @param   Request   $request
     * @return
     */
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
    /**
     * Get data of selected buyers
     * @param   array   $ids        Array with buyer ids
     * @return  mixed   Text separate coma
     */
    public static function getBuyersForExport($ids){
        $buyers = Buyers::whereIn('id',$ids)->select(DB::raw("CONCAT_WS(' ',first ,last) as full"),'company','email')->get()->toArray();
        $text = '';
        foreach($buyers as $buyer){
            $text .= $buyer['full'].','.$buyer['company'] .','.$buyer['email']." \r\n";
        }
        return $text;
    }
}
