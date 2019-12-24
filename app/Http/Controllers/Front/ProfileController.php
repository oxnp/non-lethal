<?php

namespace App\Http\Controllers\Front;

use App\Http\Models\Front\Contents\ProductsPageCategory;
use App\Http\Models\Front\Profile\Profile;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function profile()
    {
        $user = Profile::getUser();
        $data_buyers = Profile::getBuyers();

        $buyers  = array();
        if (!empty($data_buyers)){
            $buyers['first'] = $data_buyers[0]['first'];
            $buyers['last'] = $data_buyers[0]['last'];
            $buyers['email'] = $data_buyers[0]['email'];
            $buyers['company'] = $data_buyers[0]['company'];
            $buyers['phone'] = $data_buyers[0]['phone'];
            $buyers['website'] = $data_buyers[0]['website'];
            $buyers['street1'] = $data_buyers[0]['street1'];
            $buyers['street2'] = $data_buyers[0]['street2'];
            $buyers['zip'] = $data_buyers[0]['zip'];
            $buyers['city'] = $data_buyers[0]['city'];
            $buyers['state'] = $data_buyers[0]['state'];
            $buyers['country'] = $data_buyers[0]['country'];
        }else{
            $buyers['first'] = '';
            $buyers['last'] = '';
            $buyers['email'] = '';
            $buyers['company'] = '';
            $buyers['phone'] = '';
            $buyers['website'] = '';
            $buyers['street1'] = '';
            $buyers['street2'] = '';
            $buyers['zip'] = '';
            $buyers['city'] = '';
            $buyers['state'] = '';
            $buyers['country'] = '';
        }

        $categories = ProductsPageCategory::getCategoriesTolist();

        return view('Front.profile')->with([
            'user' => $user,
            'buyers'=>$buyers,
            'categories'=>$categories
        ]);
    }

    public function updateProfile(Request $request)
    {
        Profile::updateUser($request->all());
        return redirect()->back();
    }

    public function mylicenses()
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
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
