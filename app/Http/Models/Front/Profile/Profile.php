<?php

namespace App\Http\Models\Front\Profile;

use App\Http\Models\Front\Buyers\Buyers;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class Profile extends Model
{

    public static function getUser(){
        $user = User::find(Auth::ID());
    return $user;
    }

    public static function getBuyers(){
        $buyers = Buyers::whereUserId(Auth::ID())->get()->toArray();
        return $buyers;
    }

    public static function updateUser($request){

        $user = User::find(Auth::ID());
        $user->username = $request['username'];
        $user->name = $request['name'];

        if($request['password'] != '') {
            $user->password = Hash::make($request['password']);
        }
        $user->save();
        $request['user_id'] = Auth::ID();
        $request['first'] = $request['first'];
        $request['last'] = $request['last'];
        $request['email'] = Auth::user()->email;

        unset($request['username']);
        unset($request['name']);
        unset($request['password']);
        unset($request['password_confirmation']);
        unset($request['_method']);
        unset($request['_token']);

        $buyers = self::getBuyers();

        if(!empty($buyers)){
            $buyer = Buyers::whereUserId(Auth::ID());
           // dd($request);
            try {
                $buyer->update($request);
            } catch (QueryException $exception) {
            dd($exception);
        }
        }else{
            try {
            Buyers::create($request);
            } catch (QueryException $exception) {
                dd($exception);
            }
        }
    }
}
