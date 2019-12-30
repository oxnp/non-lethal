<?php

namespace App\Http\Models\Subscribers;

use Illuminate\Database\Eloquent\Model;
use App\Http\Models\Subscribers\SubscribersGroups;

class Subscribers extends Model
{
    protected $table = 'subscribers';
    public static function getSubscribers($offset){
        $subscribers= Subscribers::select('*')->offset($offset)->limit($offset + 20)->paginate(1);
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
    }
}
