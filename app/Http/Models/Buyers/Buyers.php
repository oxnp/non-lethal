<?php

namespace App\Http\Models\Buyers;

use App\User;
use Carbon\Carbon;
use http\Env\Request;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Hash;

class Buyers extends Model
{
    protected $fillable = ['first','last','email','company','phone','website','street1','street2','zip','city','state','country','bcc_emails','notes','user_id'];
    /**
     * Get buyers
     * @param   array  $per_page
     * @return collection
     */
    public static function getBuyers($request){
        $per_page = 20;
        if ($request->per_page){
           $per_page =  $request->per_page;
        }

        Builder::macro('whereLike', function($attributes, string $searchTerm) {
            foreach(array_wrap($attributes) as $attribute) {
                $this->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
            }

            return $this;
        });
        $filter = array();

        $buyers  =  Buyers::select(DB::raw('count(l.id) as licensecount'), DB::raw('sum(l.seats)  as seatcount'),'buyers.*')
            ->leftjoin('licenses as l','l.buyer_id','buyers.id')
            ->groupby('buyers.id');

        if($request->searchstring != null){
            $filter['search_string'] = $request->searchstring;
            $buyers->whereLike(['buyers.last','buyers.first','buyers.email',DB::raw("CONCAT(buyers.last,' ',buyers.first)")],$filter['search_string']);
        }

        $buyers = $buyers->paginate($per_page);
//dd($filter['search_string']);
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
        $rand_string = str_random(10);
        $pwd = Hash::make($rand_string);
        $new_client = 0;
        $user = User::where('email',$request->email)->get();
        if ($user->isEmpty()) {
            $user_laravel = new User();
            $user_laravel->name = $request->first;
            $user_laravel->username = $request->email;
            $user_laravel->email = $request->email;
            $user_laravel->password = $pwd;
            $user_laravel->registerDate = Carbon::now();
            $user_laravel->activation = '';
            $user_laravel->params = '{}';
            $user_laravel->otpKey = '';
            $user_laravel->otep = '';
            $user_laravel->requireReset = 0;

            $user_laravel->save();
            $user_id = $user_laravel->id;
            $new_client = 1;
        }else{
            $user_id = $user[0]->id;
            $new_client = 0;
        }

        $buyer = Buyers::create([
            'user_id'=>$user_id,
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


        $data_return = array();
        $data_return['password'] = $rand_string;
        $data_return['email'] = $buyer->email;
        $data_return['first'] = $buyer->first;
        $data_return['user_id'] = $user_id;
        $data_return['new_client'] = $new_client;

        return $data_return;
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
