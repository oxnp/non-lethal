<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Models\Precode\Precode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PrecodeController extends Controller
{

    public function generateFeaturePreCodeAJAX(Request $request) {

        $productID = intval($request->productid);
        $prefix = $request->prefix;
        $licenseCount = intval($request->amount);
        $featureName = $request->featurename;

        // Get the model and create precodes
        $result = Precode::generateFeaturePreActivationCodes($productID, $prefix, $licenseCount, $featureName);
        if(!$result) {
            throw new Exception('Error creating feature pre-codes!', 500);
        }
        return redirect()->back();
    }

    public function generate(Request $request)
    {
        // Get form data
        if($request->cid){
            $selectedPIDs = $request->cid;
        }else{
            $selectedPIDs = array();
        }

        // Pre checks
        if($request->license_type){
            if(($request->license_type < 1) || ($request->license_type > 3)) {
                //$this->setMessage('Please choose a license type for code generation!', 'error');
                return false;
            }else{
                $licenseType = $request->license_type;
            }
        }else{
            $licenseType = 0;
        }

        if($request->temp_days){
            if($request->temp_days < 1) {
                // $this->setMessage('Please enter a valid value for temp license duration!', 'error');
                return false;
            }else{
                $tempDays = $request->temp_days;
            }
        }else{
            $tempDays = 30;
        }

        if($request->license_count){
            if($request->license_count < 1) {
                // $this->setMessage('Please enter a valid license batch count!', 'error');
                return false;
            }else{
                $licenseCount = $request->license_count;
            }
        }else{
            $licenseCount = 5;
        }


        // Assign temp days
        $codeData = array();
        if($licenseType == 3) {
            $codeData['temp_days'] = $tempDays;
        }

        // Get reference text

        if( $request->reference){
            $reference =  $request->reference;
        }else{
            $reference = '';
        }
        // Get the model and create precodes
        $result = Precode::generatePreActivationCodes($selectedPIDs, $licenseType, $licenseCount, $codeData, $reference);

        return redirect()->back();
    }

    public  function exportPrecodes(Request $request){

       // dd($request->all());
        if($request->cid){
            $selectedPIDs = $request->cid;
        }else{
            $selectedPIDs = array();
        }
        $file_data = Precode::exportPreCodes($selectedPIDs);

        return response($file_data['content'])
            ->withHeaders([
                'Content-Type' => 'text/plain',
                'Cache-Control' => 'no-store, no-cache',
                'Content-Disposition' => 'attachment; filename="'.$file_data['name'].'"',
            ]);

    }

    public function purgeEmpty(){
        Precode::purgeEmpty();
        return redirect()->back();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = 20;
        if ($request->per_page != null){
            $per_page = $request->per_page;
        }
        $precodes = Precode::getListPrecodes($per_page)->appends(['per_page' => $per_page]);

         return view('AdminPanel.precodes.precodes_list')->with([
             'precodes'=> $precodes
         ]);
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
