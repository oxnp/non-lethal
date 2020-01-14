<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Front\Subscribers\Subscribers;
class SubscribeController extends Controller
{
    public function sendMSubscribeMail(Request $request){

        $result = Subscribers::addSubscriber($request->email);
        return response($result);
    }
}
