<?php

namespace App\Http\Controllers\Front;

use App\Http\Models\EmailsTemplates\EmailsTemplates;
use App\Jobs\SendEmail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Front\Contents\ProductsPageCategory;
class GetInTouchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = array();

        $breadcrumbs[0]['url'] = '/support/get-in-touch';
        $breadcrumbs[0]['text'] = trans('main.get_in_touch');

        $categories = ProductsPageCategory::getCategoriesTolist();
        return view('Front.support')->with([
            'categories'=>$categories,
            'breadcrumbs' => $breadcrumbs
        ]);
    }
    /**
     * Send message to mail.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendMessage(Request $request)
    {
       //dd($request->all());
        $storage_image = array();
        $sender_info = array();
        $recepient_info = array();
        $links ='';
        if ($request->file('attachment')) {
            foreach($request->file('attachment') as $file){
                $storage = $file->store('file_from_get_in_touch');
                $name_file = explode('/', $storage);
                $storage_image[] = '/storage/app/file_from_get_in_touch/'. $name_file[1];
            }

        }
        if(!empty($storage_image)){

            $i = 1;
            foreach($storage_image as $image){
                $links .= '<br>Files<a href="'.env('APP_URL').$image.'">File-'.$i.'</a><br>';
                $i++;
            }

        }

        $template = EmailsTemplates::where('alias_name','get_in_touch')->get();
        $fields = ['[name]','[email]','[product]','[subject]','[message]','[link_attach_files]'];
        $fields_replace = [
            $request->name,
            $request->email,
            $request->product,
            $request->subject,
            $request->message,
            $links
        ];

        $recepient_info['body_html'] = str_replace($fields,$fields_replace,$template[0]->body_html);

        $recepient_info['email'] = env('MAIL_TO_GET_IN_TOUCH_MESSAGE');

        $sender_info['name_from'] = $request->name;
        $sender_info['email_from'] =  $request->email;
        $sender_info['email_reply'] = $request->email;
        $sender_info['subject'] = $template[0]->subject;

        dispatch(new SendEmail($recepient_info,$sender_info));
        return 'true';

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
