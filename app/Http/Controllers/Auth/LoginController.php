<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Models\Front\Contents\ProductsPageCategory;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Request;
use App\Http\Middleware\LocaleMiddleware;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    /**
     * Login username to be used by the controller.
     *
     * @var string
     */
    protected $username;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/licenses';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->username = $this->findUsername();
    }

    public function logout(Request $request) {
        Auth::logout();
        return redirect('/'.LocaleMiddleware::getLocale());
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function findUsername()
    {
        $login = request()->input('login');
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$fieldType => $login]);
        return $fieldType;
    }

    /**
     * Get username property.
     *
     * @return string
     */
    public function username()
    {
        return $this->username;
    }

    public function showLoginForm ()
    {
        $categories = ProductsPageCategory::getCategoriesTolist();
        $breadcrumbs = array();

        $breadcrumbs[0]['url'] = '/login';
        $breadcrumbs[0]['text'] = trans('main.my_account');
        return view('auth.login')->with([
            'categories'=>$categories,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    protected function authenticated(\Illuminate\Http\Request $request, $user)
    {
        return response()->json($user);
    }
}
