<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Models\EmailsTemplates\EmailsTemplates;
use App\Http\Models\Helper\Helper;
use App\Notifications\MailRegisterUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Buyers\Buyers;
use Illuminate\Notifications\Messages\MailMessage;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
class BuyersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    if ($request->searchstring != null){
        $buyers = Buyers::getBuyers($request)->appends(['searchstring'=>$request->searchstring]);
    }else{
        $buyers = Buyers::getBuyers($request);
    }

    return view('AdminPanel.buyers.buyers_list')->with([
            'buyers' => $buyers
    ]);
    }

    public function export(Request $request){

        $cid = $request->cid;
        $csv_file = Buyers::getBuyersForExport($cid);

        return response($csv_file)
            ->withHeaders([
                'Content-Type' => 'text/plain',
                'Cache-Control' => 'no-store, no-cache',
                'Content-Disposition' => 'attachment; filename="buyers.csv"',
            ]);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('AdminPanel.buyers.buyer_add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Helper::sendSerialMail(2258,false);


        $buyer = Buyers::addBuyer($request);
        $user = User::find($buyer['user_id']);


        if($buyer['new_client'] == 1) {
            $data = [
                'email'=> $buyer['email'],
                'name'=> $buyer['first'],
                'username'=> $buyer['email'],
                'password'=>$buyer['password']
            ];
            $user->notify(new MailRegisterUser($data));
        }

        return redirect(route('buyers.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $buyer = Buyers::getBuyerById($id);

        return view('AdminPanel.buyers.buyer_show')->with([
           'buyer'=> $buyer->toArray()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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
        Buyers::updateBuyerById($request,$id);
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
}
