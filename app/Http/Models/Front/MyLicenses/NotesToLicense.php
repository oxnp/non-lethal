<?php

namespace App\Http\Models\Front\MyLicenses;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class NotesToLicense extends Model
{
    protected $table = 'notes_to_license';
    protected $fillable = ['user_notes','license_id','user_id'];
    public $timestamps = false;

    public static function updateNotes($request){

        $isset = NotesToLicense::where('license_id',$request->license_id)->get();
        if ($isset->isEmpty()){
            NotesToLicense::create(['user_notes'=>$request->user_notes,'user_id'=>Auth::ID(),'license_id'=>$request->license_id]);
        }else{
            NotesToLicense::where('license_id',$request->license_id)->update(['user_notes'=>$request->user_notes]);
        }

    }
}
