<?php

namespace App\Http\Models\EmailsTemplates;

use Illuminate\Database\Eloquent\Model;

class EmailsTemplates extends Model
{
    protected $table = 'emails_templates';
    protected $fillable = ['name','alias_name','body_html','from_name','from_addres','reply_to_name','reply_to_addres','subject'];
    public $timestamps = false;

    public static function getTemplates(){
        $templates = EmailsTemplates::select('*')->paginate(20);
        return $templates;
    }

    public static function getTemplate($id){
        $template = EmailsTemplates::find($id);
        return $template;
    }

    public static function updateTemplate($request, $id){
        EmailsTemplates::find($id)->update([
            'alias_name'=>$request->alias_name,
            'body_html'=>$request->body_html,
            'name'=>$request->name,
            'subject'=>$request->subject,
            'from_name'=>$request->from_name,
            'from_addres'=>$request->from_addres,
            'reply_to_name'=>$request->reply_to_name,
            'reply_to_addres'=>$request->reply_to_addres
        ]);
    }
}
