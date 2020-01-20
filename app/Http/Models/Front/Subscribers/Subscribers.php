<?php

namespace App\Http\Models\Front\Subscribers;

use App\User;
use Illuminate\Database\Eloquent\Model;


class Subscribers extends Model
{
    protected $table = 'subscribers';
    protected $fillable = ['email','name','user_id','enabled','confirmed','receive_html','subscription_group_ids','token'];

    public static function addSubscriber($email){

        $name_parse = explode('@',$email);

        $search = Subscribers::where('email',$email)->get();
        $token = rand(1,99999999);
        $usr = User::where('email',$email)->get();
        if ($search->isEmpty()){
            Subscribers::create([
                'name'=>$name_parse[0],
                'email'=>$email,
                'enabled'=>1,
                'token'=>$token,
                'confirmed'=>0,
                'user_id'=> !$usr->isEmpty() ? $usr[0]->id : null,
                'receive_html'=>1,
                'subscription_group_ids'=> !$usr->isEmpty() ? '1,2' : '1'
            ]);
            return 'true';
        }else{
            return 'false';
        }


    }
}
