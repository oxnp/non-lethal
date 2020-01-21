<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Models\Subscribers\SubscribersGroups;
use App\Http\Models\Subscribers\SubscribersNewsLetter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmail;
use App\Http\Models\Subscribers\Subscribers;
use Illuminate\Support\Facades\Artisan;

class SubscribeNewsletterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $newsletters = SubscribersNewsLetter::getSubscriberNewsLetters();

        return view('AdminPanel.subscriber.subscriber_newsletter_list')->with(['newsletters'=>$newsletters]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subscriber_groups = SubscribersGroups::all()->toArray();
        return view('AdminPanel.subscriber.subscriber_newsletter_add')->with(['subscriber_groups'=>$subscriber_groups]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        SubscribersNewsLetter::createSubscriberNewsLetter($request);
        return redirect(route('newsletters.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subscriber_groups = SubscribersGroups::all()->toArray();
        $newsletter = SubscribersNewsLetter::getSubscriberNewsLetter($id);

        return view('AdminPanel.subscriber.subscriber_newsletter_show')->with(['newsletter'=>$newsletter,'subscriber_groups'=>$subscriber_groups]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        SubscribersNewsLetter::updateSubscriberNewsLetter($request, $id);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function sendMessage($newsletter_id){




        $newsletter = SubscribersNewsLetter::getSubscriberNewsLetter($newsletter_id);

        $subscribers = Subscribers::all();

        $array_groups = explode(',',$newsletter->subscription_group_ids);
        $data_subscribers = array();
        $data_sender = array();

        $data_sender['name_from'] = $newsletter->from_name;
        $data_sender['email_from'] = $newsletter->from_adress;
        $data_sender['email_reply'] = $newsletter->reply_to_adress;
        $data_sender['subject'] = $newsletter->subject;


        foreach($array_groups as $id_group) {
            foreach ($subscribers as $subscriber) {
                $array_groups_subscriber = explode(',', $subscriber->subscription_group_ids);
                if (in_array($id_group,$array_groups_subscriber)){
                    $data_subscribers[$subscriber->id]['body_html'] = str_replace(array('{name}'),array(ucfirst($subscriber->name)),$newsletter->body_html);
                    $data_subscribers[$subscriber->id]['name'] = $subscriber->name;
                    $data_subscribers[$subscriber->id]['email'] = $subscriber->email;
                }

            }
        }


        foreach ($data_subscribers as $subscriber) {
            dispatch(new SendEmail($subscriber,$data_sender));
        }

       // Artisan::call('queue:work',['--stop-when-empty' => 'foo']);
       // dd($ed);
        //popen('php '.base_path().'/artisan queue:work database --sleep=3 --tries=3','r');
        return redirect()->back();
    }
}
