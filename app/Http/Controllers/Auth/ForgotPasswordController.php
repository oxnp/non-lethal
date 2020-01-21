<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Models\Front\Contents\ProductsPageCategory;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showLinkRequestForm ()
    {
        $breadcrumbs = array();

        $breadcrumbs[0]['url'] = '/password/reset';
        $breadcrumbs[0]['text'] = trans('main.my_account');
        $categories = ProductsPageCategory::getCategoriesTolist();
        return view('auth.passwords.email')->with([
            'categories'=>$categories,
            'breadcrumbs' => $breadcrumbs
        ]);
    }
}
