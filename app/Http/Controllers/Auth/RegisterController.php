<?php

namespace App\Http\Controllers\Auth;

use App\Http\Models\Front\Contents\ProductsPageCategory;
use App\Http\Models\Subscribers\Subscribers;
use App\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Notifications\UserRegisteredNotification;
use App\Notifications\MailRegisterUser;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/my-license';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' =>  $data['email'],
            'last_name' =>  $data['last_name'],
            'registerDate' => Carbon::now(),
            'lastvisitDate' => Carbon::now(),
            'password' => Hash::make($data['password']),
        ]);
    }

    public function showRegistrationForm ()
    {
        $breadcrumbs = array();

        $breadcrumbs[0]['url'] = '/register';
        $breadcrumbs[0]['text'] = trans('main.my_account');

        $categories = ProductsPageCategory::getCategoriesTolist();

        return view('auth.register')->with([
            'categories'=>$categories,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    protected function registered(Request $request, $user)
    {

        $data['name'] = $request->name;
        $data['user_id'] = $user->id;
        $data['email'] = $request->email;
        $group = '5';

        Subscribers::addSubscriberAfterRegister($data,$group);

        $user->notify(new MailRegisterUser($request->all(),$user));
        return response()->json($user);
    }


}
