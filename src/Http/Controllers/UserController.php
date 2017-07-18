<?php

namespace Sahakavatar\User\Http\Controllers;

use App\Events\sendEmailEvent;
use App\helpers\dbhelper as dbhelper;
use App\helpers\helpers;
use App\Http\Controllers\Controller;
use App\Models\FormSettings;
use App\Modules\Users\Models\Roles;
use App\Modules\Users\Models\UserMeta;
use App\Modules\Users\User;
use Datatables;
use File;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Validator;
use View;

class UserController extends Controller
{

    /**
     * UserController constructor.
     * @param Guard $auth
     * @param User $user
     */
    public function __construct(Guard $auth, User $user)
    {
        $this->auth = $auth;
        $this->user = $user;
        $this->helpers = new helpers;
        $this->dhelper = new dbhelper;
        $this->middleware('auth');
    }

    /**
     * @return mixed
     */
    public function getIndex()
    {
        $users = $this->user->getSiteUsers();
        return view('users::users.list')->with('users', $users);
    }

    /**
     * @return View
     */
    public function getAdmins()
    {
        $admins = User::admins()->paginate();
        return view('users::admins.list', compact(['admins']));
    }

    /**
     * @return View
     */
    public function getCreateAdmin()
    {
        return view('users::admins.create');
    }

    public function postCreateAdmin(Request $request)
    {
        $vdata = $request->except('_token');
        $data = $request->except('_token', 'password_confirmation');

        $rules = array_merge([
            'username' => 'required|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'role_id' => 'required|exists:roles,id',
            'membership_id' => 'sometimes|exists:memberships,id',
            'status' => 'required',
            'password' => 'required|min:6|max:255',
            'password_confirmation' => 'min:6|max:255|same:password',
        ]);
        $validator = \Validator::make($vdata, $rules);
        if ($validator->fails()) return redirect()->back()->with('errors', $validator->errors())->withInput();

        $user = User::create($data);
        if ($user && $user->addProfile()) {
            return redirect('/admin/users/admins')->with('message', "Admin Created Successfully !!!");
        }

        return redirect()->back()->with('error', 'Admin not created,Please try again');
    }

    /**
     * @return View
     */
    public function getEditAdmin($id)
    {
        $admin = User::admins()->where('id', $id)->first();

        if (!$admin) abort(404);

        return view('users::admins.edit', compact(['admin']));
    }

    public function postEditAdmin($id, Request $request)
    {
        $admin = User::admins()->where('id', $id)->first();

        if (!$admin) abort(404);

        $vdata = $request->except('_token');
        $data = $request->except('_token', 'password_confirmation');

        $rules = array_merge([
            'username' => 'required|max:255|unique:users,username,' . $admin->id, ',id',
            'email' => 'required|email|max:255|unique:users,email,' . $admin->id, ',id',
            'role_id' => 'required|exists:roles,id',
            'membership_id' => 'sometimes|exists:memberships,id',
            'status' => 'required',
            'password' => 'sometimes|min:6|max:255',
            'password_confirmation' => 'sometimes|min:6|max:255|same:password',
        ]);
        $validator = \Validator::make($vdata, $rules);
        if ($validator->fails()) return redirect()->back()->with('errors', $validator->errors())->withInput();

        if (empty($data['password'])) $data['password'] = $admin->password;

        $user = $admin->update($data);
        if ($user) {
            return redirect('/admin/users/admins')->with('message', "Admin updated Successfully !!!");
        }

        return redirect()->back()->with('error', 'Admin not created,Please try again');
    }

    public function postDeleteAdmin(Request $request)
    {
        $result = false;
        if ($request->slug) {
            $result = User::admins()->where('id', $request->slug)->first();
            if ($result) {
                $result = $result->delete();
            }
        }
        return \Response::json(['success' => $result]);
    }

    /*Need to make it with new email sent*/
    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendPassword($id, Request $request)
    {
        $user = User::find($id);

        if ($user) {
            $random = str_random(10);
            $user->password = bcrypt($random);
            $user->save();

            $emailData = [
                'template' => 'emails.auth.password',
                'data' => ['username' => $user->username, 'password' => $random],
                'usage' => $user,
                'subject' => 'New password sent to member.'
            ];
            \Event::fire(new sendEmailEvent($this->auth->user(), $emailData));

            return redirect()->back()->with([
                'flash' => [
                    'message' => trans('New Password sent successfully.'),
                ]]);
        } else {
            return redirect()->back()->with([
                'flash' => [
                    'message' => trans('Not Found'),
                    'class' => 'alert-danger'
                ]
            ]);
        }
    }

    /**
     * @param Request $request
     * @return View
     */
    public function getCreate(Request $request)
    {
        $formSettings = FormSettings::where('form_id', '58e21be5a8bd8')->first();
        if (!$formSettings) {
            abort(404);
        }
        $formSettings = $formSettings->additional_settings;
        $user_id = null;

        return view('users::users.create', compact('formSettings', 'user_id'));
    }

    /**
     * @param Request $request
     * @return View
     */
    public function postCreate(Request $request)
    {
        $vdata = $request->except('_token');
        $data = $request->except('_token', 'password_confirmation', 'custom', 'plugin', 'form_setting_id', 'customs');
        $customData = $request->custom;
        $rules = array_merge([
            'username' => 'required|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'membership_id' => 'required|exists:memberships,id',
            'status' => 'required',
            'password' => 'required|min:6|max:255',
            'password_confirmation' => 'min:6|max:255|same:password',
        ]);
        $validator = \Validator::make($vdata, $rules);
        if ($validator->fails()) return redirect()->back()->with('errors', $validator->errors())->withInput();

        if ($data['role_id'] == '') {
            $data['role_id'] = 0;
        }
        $pluginData = $request->plugin;
        $user = User::create($data);
        if ($user && $user->addProfile()) {

            if ($pluginData) {
                foreach ($pluginData as $pluginName => $plugin) {
                    if (is_array($plugin)) {
                        foreach ($plugin as $value) {
                            if ($value != '') {
                                $userMeta = new UserMeta;
                                $userMeta->user_id = $user->id;
                                $userMeta->key = $pluginName;
                                $userMeta->value = $value;
                                $userMeta->save();
                            }
                        }
                    } else {
                        if ($plugin != '') {
                            $userMeta = new UserMeta;
                            $userMeta->user_id = $user->id;
                            $userMeta->key = $pluginName;
                            $userMeta->value = $plugin;
                            $userMeta->save();
                        }
                    }
//                    BBAddMeta($pluginName, $user->id, $customData, 'post');
                }
            }

            if ($customData) {
                foreach ($customData as $key => $custom) {
                    if (is_array($custom)) {
                        foreach ($custom as $value) {
                            $userMeta = new UserMeta;
                            $userMeta->user_id = $user->id;
                            $userMeta->key = $key;
                            $userMeta->value = $value;
                            $userMeta->save();
                        }
                    } else {
                        $userMeta = new UserMeta;
                        $userMeta->user_id = $user->id;
                        $userMeta->key = $key;
                        $userMeta->value = $custom;
                        $userMeta->save();
                    }
//                    BBAddMeta($pluginName, $user->id, $customData, 'post');
                }
            }

            return redirect()->route('admin.users.list')->with('message', "New User has been created successfully !!!");
        }

        return redirect()->back()->with('error', 'New User not created,Please try again');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getEdit($id)
    {
        $user = User::find($id);
        $formSettings = FormSettings::where('form_id', '58e21be5a8bd8')->first();
        if ($formSettings) {
            $formSettings = $formSettings->additional_settings;
        }
        if (!$user)
            abort(404);

        return view('users::users.edit')->with(['user' => $user, 'formSettings' => $formSettings, 'user_id' => $id]);
    }

    public function postEdit(Request $request)
    {
        $user = User::find($request->id);
        //dd($validator->fails());
        if (!$user) abort(404);

        $vdata = $request->except('_token');
        $data = $request->except('_token', 'password_confirmation', 'custom', 'plugin', 'form_setting_id', 'customs');
        $customData = $request->custom;
        $pluginData = $request->plugin;

        $rules = array_merge([
            'username' => 'required|max:255|unique:users,username,' . $user->id, ',id',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id, ',id',
            'membership_id' => 'required|exists:memberships,id',
            'status' => 'required',
            'password' => 'sometimes|min:6|max:255',
            'password_confirmation' => 'sometimes|min:6|max:255|same:password',
        ]);
        $validator = \Validator::make($vdata, $rules);

        if ($validator->fails()) return redirect()->back()->with('errors', $validator->errors())->withInput();

        if ($data['role_id'] == '') {
            $data['role_id'] = 0;
        }

        if (empty($data['password'])) unset($data['password']);

        $user->update($data);

        UserMeta::where('user_id', $request->id)->delete();
        if ($pluginData) {
            foreach ($pluginData as $pluginName => $plugin) {
                if (is_array($plugin)) {
                    foreach ($plugin as $value) {
                        if ($value != '') {
                            $userMeta = new UserMeta;
                            $userMeta->user_id = $user->id;
                            $userMeta->key = $pluginName;
                            $userMeta->value = $value;
                            $userMeta->save();
                        }
                    }
                } else {
                    if ($plugin != '') {
                        $userMeta = new UserMeta;
                        $userMeta->user_id = $user->id;
                        $userMeta->key = $pluginName;
                        $userMeta->value = $plugin;
                        $userMeta->save();
                    }
                }
//                    BBAddMeta($pluginName, $user->id, $customData, 'post');
            }
        }

        if ($customData) {
            foreach ($customData as $key => $custom) {
                if (is_array($custom)) {
                    foreach ($custom as $value) {
                        $userMeta = new UserMeta;
                        $userMeta->user_id = $user->id;
                        $userMeta->key = $key;
                        $userMeta->value = $value;
                        $userMeta->save();
                    }
                } else {
                    $userMeta = new UserMeta;
                    $userMeta->user_id = $user->id;
                    $userMeta->key = $key;
                    $userMeta->value = $custom;
                    $userMeta->save();
                }
//                    BBAddMeta($pluginName, $user->id, $customData, 'post');
            }
        }

        if ($user) {
            return redirect()->route('admin.users.list')->with('message', "New User has been updated Successfully !!!");
        }

        return redirect()->back()->with('error', 'New User not updated,Please try again');
    }


    public function postDelete(Request $request)
    {
        $result = false;
        if ($request->slug) {
            $result = User::find($request->slug);

//            if (!$user)
//                return redirect()->back();
//
//            if (!User::ranking($user))
//                return redirect()->back();
            if ($result) {
//                $result->revokeAllRoles();
                File::deleteDirectory(base_path() . User::$uploadPath . $request->slug);
                $result = $result->delete();
            }
        }
        return \Response::json(['success' => $result]);
    }

    public function getShow(Request $request)
    {
        $user = User::find($request->id);
        if (!$user) {
            abort(404);
        }
        return view('users::users.show', compact('user'));
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|View
     */
    public function editAdmins($id, Request $request)
    {
        //get data for view
        $roles = Roles::all()->pluck('name', 'id');
        $user = User::find($id);
        if (!$user || !User::ranking($user, 'edit'))
            return redirect()->back();

        $user = $this->dhelper->formatCustomFld($user);
        /*THIS is system function for edit user quickly*/
        if ($request->ajax()) {
            $data = $request->except('extra');
            $metaData = [];

            $rules = array_merge($metaData, [
                'email' => 'required|email|max:255|unique:users,email,' . $user->id . ',id',
                'username' => 'required|max:255|unique:users,username,' . $user->id . ',id',
            ]);
            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                $account = unserialize($user['meta_data']);
                $html = View::make('users::_partials.edit_profile', compact('roles', 'user', 'account'))
                    ->withErrors($validator->errors())
                    ->render();
                return \Response::json(['data' => $html, 'code' => 200, 'error' => true]);
            }

            $roleID = (isset($data['role'])) ? $data['role'] : null;
            $userMeta = $request->get('extra', null);
            //save data
            $user = $this->user->updateUser($id, $data, $roleID, $userMeta);
            $account = unserialize($user['user_meta']);
            $html = View::make('users::_partials.edit_profile', compact('roles', 'user', 'profileFields', 'account'))->render();
            return \Response::json(['data' => $html, 'code' => 200, 'error' => false]);
        }

        return view('users::admins.edit', compact('user', 'roles'));
    }

    /**
     * @param $id
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function editMemberPass($id, Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make(
                $request->all(), User::editPasswordRule()
            );

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors());
            }

            User::changePassword($id, $request->get('password'));
            $users = $this->user->getAllUsers();
            return redirect('admin/users')->with('users', $users);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteMember($id)
    {
        $user = User::find($id);

        if (!$user)
            return redirect()->back();

        if (!User::ranking($user))
            return redirect()->back();

        $user->revokeAllRoles();
        $user->delete();
        File::deleteDirectory(base_path() . User::$uploadPath . $id);

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postResetPassword(Request $request)
    {
        $email = $request->get('email');
        if ($user = User::getUserByEmail($email)) {
            $emailData = [
                'template' => 'emails.auth.reset',
                'data' => ['username' => $user->username],
                'usage' => $user,
                'subject' => 'Reset Password'
            ];
            \Event::fire(new sendEmailEvent($this->auth->user(), $emailData));

            return \Response::json(['data' => true, 'code' => 200, 'error' => false]);
        }

        return \Response::json(['data' => false, 'code' => 500, 'error' => true]);
    }

    public function getSettings()
    {
        $formSettings = FormSettings::where('form_id', '58e21be5a8bd8')->first();
        if (!$formSettings) {
            abort(404);
        }
        $user_id = null;
        $formSettings = $formSettings->additional_settings;
//        $blogUnits = $blog->getNotSelectedUnits();
        $types = [
            'input' => 'Input',
            'selectbox' => 'Selectbox',
            'checkbox' => 'Checkbox',
            'radio' => 'Radio',
            'textarea' => 'Textarea',
        ];

//        return view('Blogs::edit', compact(['id', 'blog', 'types', 'blogUnits']));


        return view('users::admins.settings', compact(['formSettings', 'user_id']));
    }

    public function postSettings(Request $request)
    {
        $data['units'] = $request->custom['units'];
        if (isset($request->customs['units']) && is_array($request->customs['units'])) {
            $data['units'] = array_merge($request->customs['units'], $data['units']);
        }
        $formSettings = FormSettings::where('form_id', $request->form_setting_id)->first();
        if ($formSettings) {
            if (count($data)) {
                $extraData = [];
                foreach ($data as $key => $field) {
                    if ($key != 'units' && !empty($field)) {
                        $slug = BBGenerateSlug($field['name']);
                        if ($slug && !isset($extraData[$slug])) {
                            $extraData[$slug] = [
                                'name' => $slug,
                                'type' => $field['type'],
                                'label' => ucfirst($field['name'])
                            ];
                            if (isset($field['values'])) {
                                $extraData[$slug]['values'] = $field['values'];
                            }
                        }
                    } else {
                        $extraData[$key] = $field;
                    }

                }

                $formSettings->additional_settings = $extraData;

            } else {
                $formSettings->additional_settings = NULL;
            }
            $formSettings->save();
            return redirect()->back()->with('message', 'Form Settings saved');
        }

        abort(404);
    }

}
