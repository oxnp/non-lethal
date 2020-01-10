<?php

namespace App\Http\Models\Subscribers;

use Illuminate\Database\Eloquent\Model;
use App\Http\Models\Subscribers\SubscribersGroups;

class Subscribers extends Model
{
    protected $table = 'subscribers';
    protected $fillable = ['email','name','user_id','enabled','confirmed','receive_html','subscription_group_ids','token'];
    public static function getSubscribers($offset){
        $subscribers= Subscribers::select('*')->offset($offset)->limit($offset + 20)->paginate(20);
       // dd($subscribers);
       //$subscribers_paginate = Subscribers::select('*')->paginate(1);

        $groups = SubscribersGroups::select('*')->get()->toArray();

        $data = array();
        $data['paginate'] = $subscribers;
        foreach ($subscribers as $key => $value) {
            $data['subscribers'][$value['id']] = array(
                'id' => $value['id'],
                'email' => $value['email'],
                'name' => $value['name'],
                'user_id' => $value['user_id'],
                'enabled' => $value['enabled'],
                'confirmed' => $value['confirmed'],
                'receive_html' => $value['receive_html'],
            );

            $group_ids = explode(',',$value['subscription_group_ids']);

            foreach($groups as $group){
                if (in_array($group['id'], $group_ids)){
                    $data['subscribers'][$value['id']]['group_name'][] = $group['group_name'];
                }
            }
        }

        return $data;
    }

    public static function getSubscriberById($id){
        $subscriber = Subscribers::find($id);
        return $subscriber;
    }

    public static function updateUserSubscribe($request, $id)
    {

        $result = Subscribers::find($id)->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'enabled'=>$request->enabled,
            'confirmed'=>$request->confirmed,
            'receive_html'=>$request->receive_html,
            'subscription_group_ids'=> implode(',',$request->subscription_group_ids)
        ]);
       // dd($result);
    }
}
