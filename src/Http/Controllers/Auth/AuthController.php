<?php

namespace Sahakavatar\User\Http\Controllers\Auth;

//use App\Events\sendEmailEvent;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Notify;
use Redirect;
use Sahakavatar\Console\Repository\AdminPagesRepository;
use Sahakavatar\Modules\Models\AdminPages;
use Validator;

//use Sahakavatar\Settings\Repository\AdminsettingRepository as Settings;
//use App\Repositories\EmailsRepository;


/**
 * Class AuthController
 *
 * @package App\Modules\Users\Http\Controllers\Auth
 */
class AuthController extends Controller
{

    /**
     * Create a new authentication controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\Guard $auth
     * @return void
     */
    public $settings;
    private $emailsRepository;
    private $adminUrl;

    /**
     * AuthController constructor.
     *
     * @param Guard $auth
     * @param Settings $settings
     */
    public function __construct(
        Guard $auth
//        Settings $settings,
//        EmailsRepository $emailsRepository,
//        SendEmail $sendEmail
    )
    {
        $this->auth = $auth;
//        $this->settings = $settings;
//        $this->emailsRepository = $emailsRepository;
//        $this->sendEmail = $sendEmail;
//        $this->adminUrl = AdminPages::where('slug', 'admin-login')->first()->url;
//        $this->middleware('guest', ['except' => 'getLogout']);

    }

    /**
     * Redirect user to login page
     *
     * @return redirect
     */
    public function getAuth()
    {
        return redirect('login');

    }

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
//        if (!$this->settings->getSystemLoginReg('enable_login')) {
//            redirect("/");
//        }

        $enable_reg = true;//$this->settings->getSystemLoginReg('enable_registration');
        return view('frontend.login', compact('enable_reg'));
    }

    public function getAdminLogin()
    {
        return view('admin.login');
    }

    public function postAdminLogin(
        Request $request,
        AdminPagesRepository $adminPagesRepository
    )
    {
        $adminUrl = $adminPagesRepository->getBy('slug', 'admin-login')->url;
        $field = filter_var($request->input('usernameOremail'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $request->merge([$field => $request->input('usernameOremail')]);
        $error = trans('These credentials do not match our records.');
        $user = User::where($field, $request->$field)->first();
        if ($user && \Hash::check($request->password, $user->password) && $user->role) {
            if ($this->auth->attempt(array_add($request->only($field, 'password'), 'status', 'active'), $request->has('remember'))) {
                $user = \Auth::getProvider()->retrieveByCredentials($request->only($field, 'password'));
                return redirect('/admin');
            }
        } else {
            $error = trans('Access denied');
        }
        if ($user->status != 'active') {
            $error = trans('Please activate your account first.');
        }

        return redirect(adminUrl)
            ->withInput($request->only('username', 'remember'))
            ->with(
                [
                    'flash' => [
                        'message' => $error,
                        'class' => 'alert-danger'
                    ]
                ]
            );
    }

    /**
     * Show the application register form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRegister()
    {
        if (!$this->settings->getSystemLoginReg('enable_registration')) {
            return redirect($this->loginPath());
        }

        return view('frontend.register');
    }

    /**
     * Get the path to the login route.
     *
     * @return string
     */
    public function loginPath()
    {
        return property_exists($this, 'loginPath') ? $this->loginPath : 'login';
    }

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request)
    {
        if (!$this->settings->getSystemLoginReg('enable_registration')) {
            redirect($this->loginPath());
        }

        $vdata = $request->except('_token');
        $data = $request->except('_token', 'password_confirmation');

        $rules = array_merge([
            'username' => 'required|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'membership_id' => 'required|exists:memberships,id',
            'password' => 'required|min:6|max:255',
            'password_confirmation' => 'min:6|max:255|same:password',
        ]);
        $validator = \Validator::make($vdata, $rules);
        if ($validator->fails()) return redirect()->back()->with('errors', $validator->errors())->withInput();

        $user = new User();
        $data['status'] = 'inactive';
        $data['token'] = str_replace("/", "", bcrypt(uniqid()));
        $user = User::create($data);
        if ($user && $user->addProfile()) {
            $emailData = [
                'template' => 'emails.auth.activate',
                'data' => ['username' => $user->username, 'token' => $user->token, 'subject' => 'Activate account.'],
                'usage' => $user,
                'subject' => 'Activate account.'
            ];
            if ($this->settings->getSystemLoginReg('email_activation')) {
                try {
                    \Mail::queue('emails.auth.activate', $emailData['data'], function ($message) use ($user) {
                        $message->to($user->email);
                    });
                } catch (\Exception $exception) {
                    dd($exception);
                }
            }

            return redirect($this->loginPath())->with(
                [
                    'flash' => [
                        'message' => 'Please check your e-mail to activate account',
                        'class' => 'alert-success'
                    ]
                ]
            );
        }

        return redirect()->back()->with(
            [
                'flash' => [
                    'message' => 'Something went wrong, please try later',
                    'class' => 'alert-warning'
                ]
            ]
        );
    }

    public function activate($username, $token)
    {
        $user = User::checkUserActivation($username, $token);

        if ($user) {
            $user->update(['status' => 'active', 'token' => '']);
            $emailData = [
                'template' => 'emails.auth.welcome',
                'data' => ['username' => $user->username, 'subject' => 'Welcome to site.'],
                'usage' => $user,
                'subject' => 'Welcome to site.'
            ];

            if ($this->settings->getSystemLoginReg('email_on_register')) {
                try {
                    \Mail::queue($emailData['template'], $emailData['data'], function ($message) use ($user) {
                        $message->to($user->email);
                    });
                } catch (\Exception $exception) {
                    dd($exception);
                }
            }

            return redirect('login')->with(
                [
                    'flash' => [
                        'message' => trans('Account is activated, try to Log in'),
                    ]
                ]
            );

        } else {
            return redirect('register');
        }

    }

    public function createPassword($username, $token, Request $request)
    {
        $user = User::checkUserActivation($username, $token);

        if ($user) {
            return view('frontend.password')->with('username', $username)->with('token', $token);
        } else {
            return redirect('register')->with(
                [
                    'flash' => [
                        'message' => trans('try to create later'),
                        'class' => 'alert-danger'
                    ]
                ]
            );;
        }
    }

    public function postCreatePassword($username, $token, Request $request)
    {
        $user = User::checkUserActivation($username, $token);
        if ($user) {
            $validator = Validator::make($request->all(), ['password' => 'required|confirmed|min:6']);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors());
            }

            $user->token = '';
            $user->password = bcrypt($request->get('password'));
            $user->status = "active";
            $user->save();

            return redirect('login')->with(
                [
                    'flash' => [
                        'message' => trans('You have successfully created password, try to Log In'),
                        'class' => 'alert-success'
                    ]
                ]
            );
        } else {
            return redirect('register')->with(
                [
                    'flash' => [
                        'message' => trans('Not Found'),
                        'class' => 'alert-danger'
                    ]
                ]
            );
        }
    }

    /**
     * Show the application forgot password form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getForgot()
    {
        return view('frontend.forgot');
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout(Request $request)
    {

        $redirect = null;
        if ($request->get('redirect')) {
            $redirect = $request->get('redirect');
        }

        if ($this->auth->check()) {

//            Notify::add(
//                [
//                    'category' => 'logout',
//                    'extra' => ['class' => 'success']
//                ]
//            );

            $this->auth->logout();
            if ($redirect == 'back') {
                return Redirect::back();
            }

            if ($redirect) {
                return redirect($redirect);
            }

            return redirect('/');
        }
    }

    /**
     * Reset user's password and send it by e-mail
     *
     * @return \Illuminate\Http\Response
     */
    public function getPassword($username, $token)
    {
        $user = User::where('username', '=', $username)->first();

        // if token exists in database, activate account
        if (isset($user) && !empty($user->token)) {
            $encryptedToken = unserialize(base64_decode($user->token));

            if (isset($encryptedToken['token']) &&
                $encryptedToken['reason'] == 'lost' &&
                $encryptedToken['token'] == $token
            ) {

                $user->token = '';
                $random = generated_password();

                $user->password = bcrypt($random);

                $user->save();

                $emailData = [
                    'template' => 'emails.auth.password',
                    'data' => ['username' => $user->username, 'password' => $random],
                    'usage' => $user,
                    'subject' => 'New password.'
                ];
                \Event::fire(new sendEmailEvent($user, $emailData));

                return redirect('login')
                    ->with(
                        [
                            'flash' => [
                                'message' => trans('Please check your e-mail inbox to receive new password.'),
                            ]
                        ]
                    );
            }
        }

        return redirect('login')
            ->with(
                [
                    'flash' => [
                        'message' => trans('Token is not valid.'),
                        'class' => 'alert-danger'
                    ]
                ]
            );
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        $field = filter_var($request->input('usernameOremail'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $request->merge([$field => $request->input('usernameOremail')]);

        if ($this->auth->attempt(array_add($request->only($field, 'password'), 'status', 'active'), $request->has('remember'))) {

//            Notify::add(
//                [
//                    'category' => 'login',
//                    'extra' => ['class' => 'success']
//                ]
//            );

            if ($this->auth->user()->membership) {
                return redirect('/');
            } else {
                $user = \Auth::getProvider()->retrieveByCredentials($request->only($field, 'password'));
                return redirect('/admin');
            }
        }

        $error = trans('These credentials do not match our records.');
        if ($this->auth->validate($request->only($field, 'password'))) {
            $error = trans('Please activate your account first.');
        }

        return back()
            ->withInput($request->only('username', 'remember'))
            ->with(['message' => $error, 'class' => 'alert-danger']);
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        if (property_exists($this, 'redirectPath')) {
            return \Config::get('paths.loginRedirectPath');
        }

        return \Config::get('paths.loginRedirectPath');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  Request $request
     * @return Response
     */
    public function postForgot(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);
        $user = User::where('email', '=', $request->only('email'))->active()->first();

        if (!isset($user)) {
            return redirect('forgot')->with(
                [
                    'flash' => [
                        'message' => trans('E-mail address is not connected to any active account.'),
                        'class' => 'alert-danger'
                    ]
                ]
            );
        }

        // generate token
        $random = str_random(64);
        $arr = ['reason' => 'lost', 'token' => $random, 'generated_at' => date('Y-m-d H:i:s')];
        $user->token = base64_encode(serialize($arr));
        $user->save();

        $emails = $this->emailsRepository->findAllBy('event_code', 'forgot.password');
        foreach ($emails as $email) {
            $this->sendEmail->sendEmail($email, $user);
        }

        // send e-mail with confirmation link
        /* $emailData = [
             'template' => 'emails.4',
             'data' =>  ['username' => $user->username, 'token' => $random],
             'usage' => $user,
             'subject' => trans('Forgot password.')
         ];
         \Event::fire(new sendEmailEvent($user,$emailData));*/

        return redirect('forgot')
            ->with(
                [
                    'flash' => [
                        'message' => trans('Please check your e-mail inbox to recover the password.'),
                    ]
                ]
            );
    }

}
