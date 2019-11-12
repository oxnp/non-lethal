<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Models\IlokCodes\IlokCodes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class IlokCodesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $per_page = 20;
        if ($request->per_page != null) {
            $per_page = $request->per_page;
        }

        $ilok_codes = IlokCodes::getListIlokCodes($per_page)->appends(['per_page' => $per_page]);
        return view('AdminPanel.ilokcodes.ilokcodes_list')
            ->with([
                'ilok_codes' => $ilok_codes
            ]);

    }

    public function remove(Request $request)
    {
        IlokCodes::remove($request->cid);
        return redirect()->back();
    }

    public function import(Request $request){


        dd($request->all());
       // $contents = Storage::get('123.csv');
        $contents = file_get_contents($request->file('import_file')->getRealPath());

        // Get batch creation date
        $batchDateRegex = "/Batch Creation Date:,([^\n]*)/";
        preg_match($batchDateRegex, $contents, $matches);



        if ($matches && $matches[1])
        {
            $batchDate = Date('Y-m-d H:i:s',strtotime($matches[1]));
        }

        // Get batch size
        $batchSizeRegex = "/Code Count:,(\d*)/";
        preg_match($batchSizeRegex, $contents, $matches);

        $batchSize = -1;
        if ($matches && $matches[1])
        {
            $batchSize = intval($matches[1]);
        }

        // Get codes
        $ilokCodesRegex = "/(?:\d{4}-){7}\d{2}/";
        preg_match_all($ilokCodesRegex, $contents, $matches);

        //dd($matches);


        $ilokCodes = [];
        if ($matches)
        {
            $ilokCodes = $matches[0];
        }

        // Compare code count with batch size
        if (count($ilokCodes) !== $batchSize)
        {
            //JFactory::getApplication()->enqueueMessage(JText::_('Parsed ilok code count differs from stated code count!'), 'error');
            return false;
        }

        $data_insert = array();

        foreach ($ilokCodes as $key => $ilokCode) {
            $data_insert[] = array(
                'product_id'=>$request->product_id,
                'ilok_code'=>$ilokCode,
                'batchtime'=>$batchDate
            );

        }
        dd($data_insert);

        try
        {
            IlokCodes::import($data_insert);
        } catch (Exception $exception)
        {
           // JFactory::getApplication()->enqueueMessage(JText::sprintf('iLok code DB insert failed: %s', $exception->getMessage()), 'error');
            return false;
        }
        //JFactory::getApplication()->enqueueMessage(JText::sprintf('Added %s iLok codes to database...', $batchSize), 'success');
        return true;


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

    }
}
