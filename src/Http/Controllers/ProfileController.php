<?php

namespace Sahakavatar\User\Http\Controllers;

use App\helpers\dbhelper;
use App\Http\Controllers\Controller;
use App\Models\FormSettings;
use App\Modules\Create\Forms;
use App\Modules\Users\Models\UsersProfile;
use App\Modules\Users\User;
use Auth;
use File;
use Hash;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Validator;

class ProfileController extends Controller
{
    public function __construct(Guard $auth, User $user, UsersProfile $profile)
    {
        $this->auth = $auth;
        $this->user = $user;
        $this->profile = $profile;
        $this->dhelper = new dbhelper;
        $this->middleware('auth');
    }

    public function getIndex($id = null)
    {
        if ($id) {
            $user = User::find($id);
            if (!$user) return redirect()->back();
        } else {
            $user = $this->auth->user();
        }
        return view('users::profile', compact('user'));
    }

    public function getView()
    {
        $user = $this->auth->user();

        return view('users::profile', compact('user'));
    }

    public function getEditProfile()
    {
        return view('users::profile.edit');
    }

    public function getLoginDetails()
    {
        $model = Auth::user();
        $formSettings = FormSettings::where('form_id', '58e21be5a8bd8')->first();
        if (!$formSettings) {
            abort(404);
        }
        $formSettings = $formSettings->additional_settings;

        return view('users::profile.edit_login_details',compact('model','formSettings'));
    }

    public function postEditProfile(Request $request)
    {
        dd($request->all());
    }

    public function postLoginDetails(Request $request)
    {
        dd($request->all());
    }

    private function createDir($path)
    {

        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

    }

    public function changePassword(Request $request, User $user)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255|unique:users',
            'current_password' => 'required|min:6',
            'password' => 'required|confirmed|different:current_password|min:6',
            'password_confirmation' => 'required|different:current_password|min:6|same:password'
        ]);

        $validator->after(function ($validator) use ($user, $request) {
            if (!Hash::check($request->get('current_password'), Auth::user()->password)) {
                $validator->errors()->add('current_password', 'Current password is incorrect!');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput()->with('active', 'profile');
        }

        $credentials = $request->only(
            'password', 'password_confirmation'
        );

        $user = Auth::user();
        $user->email = $request->get('email');
        $user->password = bcrypt($credentials['password']);
        $user->save();

        return redirect()->back()->with([
            'flash' => [
                'message' => trans('Password changed successfully.'),
            ]
        ]);
    }
}
