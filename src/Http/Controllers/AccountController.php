<?php

namespace App\Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Create\FormFields;
use App\Modules\Create\Forms;
use App\Modules\Users\User;
use Auth;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Validator;

class AccountController extends Controller
{
    /**
     * @var \App\helpers\dbhelper|null
     */
    private $dhelp = null;

    /**
     * AccountController constructor.
     * @param Guard $auth
     * @param User $user
     */
    public function __construct(Guard $auth, User $user)
    {
        $this->auth = $auth;
        $this->user = $user;
        $this->dhelp = new \App\helpers\dbhelper;
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex()
    {
        $user = $this->auth->user();
        return view('users::account', compact('user'));
    }

    /**
     * Show Loged in User Notifications
     */
    public function getNotifications()
    {
        $data = $this->notirepo->adminListing();
        return view('users::notifications', $data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEditProfile()
    {
        return view('users::edit_profile');
    }

    /**
     * @param Request $request
     * @param User $user
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function changeRegistration(Request $request, User $user)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email|max:255|unique:users,email,' . $this->auth->user()->id . ',id',
                'username' => 'required|max:255|unique:users,username,' . $this->auth->user()->id . ',id',
//            'current_password' => 'required|min:6',
                'password' => 'required|confirmed|different:current_password|min:6',
                'password_confirmation' => 'required|different:current_password|min:6|same:password'
            ]
        );

//        $validator->after(function($validator) use ($user,$request) {
//            if (!Hash::check($request->get('current_password') , Auth::user()->password )) {
//                $validator->errors()->add('current_password', 'Current password is incorrect!');
//            }
//        });

        if ($validator->fails()) {
//                dd($validator->errors());
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $credentials = $request->only(
            'password',
            'password_confirmation'
        );

        $user = Auth::user();
        $user->email = $request->get('email');
        $user->password = bcrypt($credentials['password']);
        $user->save();

        return redirect()->back()->with(
            [
                'flash' => [
                    'message' => trans('Password changed successfully.'),
                ]
            ]
        );
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function saveAccount(Request $request)
    {
        $data = $request->except('_token');

        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email|max:255|unique:users,email,' . $this->auth->user()->id . ',id',
                'username' => 'required|max:255|unique:users,username,' . $this->auth->user()->id . ',id',
                'name' => 'required',
                'password' => 'sometimes|confirmed|min:6',
                'password_confirmation' => 'sometimes|min:6|same:password'
            ]
        );

        if ($validator->fails()) {
//                dd($validator->errors());
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $user = $this->user->find(Auth::id());
        if (isset($data['extra'])) {
            $data['meta_data'] = serialize($data['extra']);
        } else {
            $data['meta_data'] = '';
        }

        if ($data['password']) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->back()->with(
            [
                'flash' => [
                    'message' => trans('Account changed successfully.'),
                ]
            ]
        );
    }
}
