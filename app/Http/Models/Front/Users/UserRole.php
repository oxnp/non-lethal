<?php

namespace App\Http\Models\Front\Users;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = 'user_role';

    public static function getAuthorisedViewLevels(){
        $levels = UserRole::all()->pluck('id')->toArray();
        return $levels;
    }
}
