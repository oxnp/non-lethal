<?php

namespace App\Http\Models\Subscribers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Auth;

class SubscribersNewsLetter extends Model
{
    protected $table = 'subscribers_newsletters';
    public $timestamps = false;
    protected $fillable = ['subject','alias','published','visible','send_html_version','summary','sender','body_html','from_name','from_adress','send_date','created_at','reply_to_name','reply_to_adress','subscription_group_ids'];

    public static function getSubscriberNewsLetters(){
        $newsletters = SubscribersNewsLetter::select('*')->paginate(20);

        return $newsletters;
    }

    public static function getSubscriberNewsLetter($id){
        $newsletter = SubscribersNewsLetter::find($id);

        return $newsletter;
    }

    public static function updateSubscriberNewsLetter($request, $id){
        $newsletter = SubscribersNewsLetter::find($id);

        $newsletter->update([
            'subject'=>$request->subject,
            'alias'=>$request->alias,
            'published'=>$request->published,
            'visible'=>$request->visible,
            'send_html_version'=>$request->send_html_version,
            'summary'=>$request->summary,
            'sender'=>$request->sender,
            'body_html'=>$request->body_html,
            'from_name'=>$request->from_name,
            'from_adress'=>$request->from_adress,
            'reply_to_name'=>$request->reply_to_name,
            'reply_to_adress'=>$request->reply_to_adress,
            'subscription_group_ids'=> $request->subscription_group_ids != '' ? implode(',',$request->subscription_group_ids) : ''
        ]);
    }

    public static function createSubscriberNewsLetter($request){

        SubscribersNewsLetter::create([
            'subject'=>$request->subject,
            'alias'=>$request->alias,
            'published'=>$request->published,
            'visible'=>$request->visible,
            'send_html_version'=>$request->send_html_version,
            'summary'=>$request->summary,
            'sender'=>Auth::user()->name,
            'body_html'=>$request->body_html,
            'from_name'=>Auth::user()->name,
            'from_adress'=>Auth::user()->email,
           // 'send_date'=>Carbon::now(),
            'created_at'=>Carbon::now(),
            'reply_to_name'=>Auth::user()->name,
            'reply_to_adress'=>Auth::user()->email,
            'subscription_group_ids'=> $request->subscription_group_ids != '' ? implode(',',$request->subscription_group_ids) : ''
        ]);

    }
}
