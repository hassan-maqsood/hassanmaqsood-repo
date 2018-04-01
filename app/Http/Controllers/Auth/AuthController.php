<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Validator;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Support\Facades\Storage;
class AuthController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

	use AuthenticatesAndRegistersUsers;

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
	 * @return void
	 */
	public function __construct(Guard $auth, Registrar $registrar)
	{
		$this->auth = $auth;
		$this->registrar = $registrar;

		$this->middleware('guest', ['except' => 'getLogout']);
	}

    /**
     * get login view
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
        return view('auth.login');
    }

    /**
     * get registration view.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function getRegistration()
    {
        return view('auth.register');
    }

    /**
     * logout user
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->to('auth/login');
    }

    public function postLogin(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email|exists:users',
                'password' => 'required|min:6',

            ],
            [
                'name.required' => 'Name is required',
                'email.required' => 'Email is required',
                "email.email" => 'Email is not valid',
            ]
        );
        if ($validator->fails()) {
            return redirect()->to('/auth/login')->withInput()->withErrors($validator->errors());
        } else {
            $email = $request->input('email');
            $password = $request->input('password');
            $remember = $request->input('remember');
            $auth = Auth::attempt(['email' => $email, 'password' => $password], $remember);

            if ($auth) {
                //$status_obj = $user->checkAccountStatus($email);
                //$status_aray = get_object_vars($status_obj);
                //$status = $status_aray['status'];
//                if ($status == 'pending') {
//                    Auth::logout();
//                    return redirect()->to('/auth/login#auth')->withInput()->with('login_fail', 'Your account hasn\'t approved yet.');
//                } else if ($status == 'rejected') {
//                    Auth::logout();
//                    return redirect()->to('/auth/login#auth')->withInput()->with('login_fail', 'account_rejected'));
//                }
                if (1 || Auth::check() && Auth::user()->role->role == 'admin') {

                    return redirect()->to('/');
                }
            } else {
                Session::put('login_fail', 'Incorrect email or password');
                return redirect()->to('/auth/login')->withInput();
            }
        }
    }

    public function postRegister(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email|unique:users',
                "name" => 'required',
                'school_name' => 'required|regex:/[A-Za-z0-9]/',
                'address' => 'required|min:5regex:/[A-Za-z0-9]/',
            ],
            [
                'name.required' => 'Name is required',
                'school_name.required' => 'School name is required',
                'email.required' => 'Email is required',
                "email.email" => 'Enter valid email',
                'address.required' => 'Please enter Address',
            ]
        );

        if ($validator->fails()) {
            return redirect()->to('/auth/register')->withInput()->withErrors($validator->errors());
        } else {

            $user = new User();

            //create user
            $user->name = $input['name'];
            $user->email = $input['email'];
            $user->address = $input['address'];
            $user->school_name = $input['school_name'];
            $user->status = 'pending';
            $role = $user->getUserRoleId();
            $user->role_id = $role->id;

            if ($user->save()) {
                $count = $user->sendPendingRequestEmail($user->email, $user->name);
                if ($count) {
                    return redirect()->to('auth/login')->with('global', 'Thank you For registration. Please check Email for further notice');
                } else {
                    return redirect()->to('auth/login')->with('global-error', 'Some random problem occurred');
                }
            } else {
                return redirect()->to('auth/register')->with('global-error', 'User registration failed. Try again');
            }
        }
    }
}
