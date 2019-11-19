<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Models\Buyers\Buyers;
use App\Http\Models\License\License;
use App\Http\Models\Products\Products;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LicenseController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $result = License::getLicenses($request);
        $licenses = $result['licenses'];
        $filter = $result['filter'];
        $buyers = Buyers::all();

        return view('AdminPanel.licenses.licenses_list')->with([
            'licenses'=> $licenses,
            'filter'=> $filter,
            'buyers'=> $buyers
            ]);
    }

    public function transferLicense(Request $request){
        License::transferLicense($request->licenses_id, $request->buyer_id);
        return redirect(route('licenses.index'));
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
        $products = Products::getProductListToLicense();
        $license =  License::getLicense($id);
        $seats = License::getSeatsToLicense($id);
        return view('AdminPanel.licenses.license')->with([
            'products'=>$products,
            'license'=>$license,
            'seats'=>$seats
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
        License::updateLicense($request,$id);
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
