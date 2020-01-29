<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Models\Subscribers\SubscribersGroups;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Subscribers\Subscribers;

class SubscribeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $offset = 0;
        if ($request->page){
            $offset = $request->page;
        }
        $data_subscriber = Subscribers::getSubscribers($offset);
        return view('AdminPanel.subscriber.subscriber_list')->with(['data_subscriber'=>$data_subscriber]);

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subscriber_groups = SubscribersGroups::all()->toArray();
        return view('AdminPanel.subscriber.subscriber_add')->with(['subscriber_groups'=>$subscriber_groups]);;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = Subscribers::createUserSubscribe($request);
        //dd($result);
        return redirect(route('subscribers.index'));
        //return redirect(route('subscribers.show',$result->id));
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subscriber = Subscribers::getSubscriberById($id);
        $subscriber_groups = SubscribersGroups::all()->toArray();
        return view('AdminPanel.subscriber.subscriber_show')->with(['subscriber' => $subscriber,'subscriber_groups'=>$subscriber_groups]);
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
        Subscribers::updateUserSubscribe($request, $id);


        if ($request->redirect != 0){
            return redirect(route('subscribers.index'));
        }else{
            return redirect(route('subscribers.show',$id));
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Subscribers::deleted($id);
        return redirect(route('subscribers.index'));
    }
}
