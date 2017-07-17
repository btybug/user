<?php 

namespace App\Http\Controllers\Auth;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Http\Response;
use App\Http\Requests\RegisterRequest;
use App\Http\Controllers\Controller;
use App\User;
use Mail;
use Carbon;
use Auth;
use Hash;
use Request;

class RegisterController extends Controller {

    /**
     * Create a new authentication controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\Guard  $auth
     * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
     * @return void
     */
    public function __construct(Registrar $registrar) {
        $this->registrar = $registrar;

        $this->middleware('guest', ['except' => 'getLogout']);
        $this->middleware('csrf');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRegister() {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(RegisterRequest $request) {
           
        // save additional data (address etc)
        $additional_data = [
            'firstname' => $request->input('firstname'),
            'lastname'  => $request->input('lastname'),
            'phone'     => $request->input('phone'),
            'company'   => $request->input('company'),
            'crm'       => $request->input('crm'),
            'ads'       => ($request->input('ads') !== null) ? $request->input('ads') : '0'
        ];

        // get random phrase to confirm e-mail address
        $random = str_random(32);

        // prepare user data
        $user = new User;

        $user->username        = $request->input('username');
        $user->password        = Hash::make($request->input('password'));
        $user->email           = $request->input('email');
        $user->active          = '0';
        $user->role            = '2';
        $user->additional_data = $additional_data;
        $user->token           = base64_encode(serialize([
            'reason'       => 'register',
            'email'        => $request->input('email'),
            'token'        => $random,
            'generated_at' => date('Y-m-d H:i:s')
        ]));

        // save user
        $user->save();

        // send confirmation e-mail
		$emailVariables = ['username' => $user->username, 'token' => $random];
		
		Mail::send('emails.auth.welcome', $emailVariables, function($message) use ($user)
		{
            $message->to($user->email)->subject(trans('Welcome!'));
		});

        return redirect('auth/register')
                        ->with([
                            'flash' => [
                                'message' => trans('Registration successful. Please check your e-mail inbox to confirm e-mail address and activate acconut.')
                            ]
        ]);
    }


    /**
     * Confirm user e-mail after registration
     *
     * @return \Illuminate\Http\Response
     */
    public function getConfirm($username, $token) {
        $user = User::where('username', '=', $username)->firstOrFail();

        // if token exists in database, activate account
        if (!empty($user->token)) {

            $encryptedToken = unserialize(base64_decode($user->token));

            if (isset($encryptedToken['token']) &&
                    $encryptedToken['reason'] == 'register' &&
                    $encryptedToken['token'] == $token) {

                $user->token = '';
                $user->active = '1';

                $user->save();

                Auth::loginUsingId($user->id);

                return redirect('/')
                                ->with([
                                    'flash' => [
                                        'message' => trans('Your account is now active.'),
                                    ]
                ]);
            }
        }

        return redirect('auth/login')
                        ->with([
                            'flash' => [
                                'message' => trans('Token is not valid.'),
                                'class' => 'alert-danger'
                            ]
        ]);
    }

}
