<?php
/**
 * Copyright (c) 2016.
 * *
 *  * Created by PhpStorm.
 *  * User: Edo
 *  * Date: 10/3/2016
 *  * Time: 10:44 PM
 *
 */

namespace Sahakavatar\User\Http\Controllers;

use App\Modules\Manage\Models\FrontendPage;
use App\Modules\Modules\Models\AdminPages;
use Caffeinated\Shinobi\Models\Permission;
use Caffeinated\Shinobi\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use App\Modules\Users\Models\Roles;
use App\Modules\Users\User;
use App\Modules\Users\Models\Permissions;
use App\Modules\Users\Models\PermissionRole;
use App\Modules\Users\Groups;
use Auth, DB, File, Config, Entrust, View;

class RolesController extends Controller
{
    private $modules;
    private $extra_modules;
    public function __construct(Guard $auth, User $user)
    {
        $this->modules = json_decode(\File::get(storage_path('app/modules.json')));
        $this->extra_modules = json_decode(\File::get(storage_path('app/plugins.json')));
        $this->auth = $auth;
        $this->user = $user;
        $this->middleware('auth');
    }

    public function getIndex()
    {
        $roles = Roles::all();
        return view('users::roles.index', compact(['roles']));
    }

    public function getCreate(){
        return view('users::roles.create');
    }

    public function postCreate(Request $request){
        $data = $request->except('_token');
        $rules = array_merge([
            'name' => 'required|max:100',
            'slug' => 'required|max:255|unique:roles,slug',
        ]);
        $validator = \Validator::make($data, $rules);
        if ($validator->fails()) return redirect()->back()->with('errors',$validator->errors())->withInput();

        Roles::create($data);
        return redirect('/admin/users/roles')->with('message','Role successfully created');
    }

    public function getEdit($slug){
        $role = Roles::where('slug',$slug)->first();
        if(! $role) return redirect()->back();

        return view('users::roles.edit',compact('role'));
    }

    public function postEdit($slug,Request $request){
        $role = Roles::where('slug',$slug)->first();

        if(! $role) return redirect()->back();

        $data = $request->except('_token');
        $rules = array_merge([
            'name' => 'required|max:100',
            'slug' => 'required|max:255|unique:roles,slug,'.$role->id.",id",
        ]);
        $validator = \Validator::make($data, $rules);
        if ($validator->fails()) return redirect()->back()->with('errors',$validator->errors())->withInput();

        $role->update($data);
        return redirect('/admin/users/roles')->with('message','Role successfully updated');
    }

    public function postDelete(Request $request)
    {
        $result = false;
        if($request->slug) {
            $result = Roles::find($request->slug)->delete();
        }
        return \Response::json(['success' => $result]);
    }

    public function getPermissions($slug)
    {
        $role = Roles::where('slug',$slug)->first();

        if(! $role) abort(404);

        switch($role->access) {
            case Roles::ACCESS_TO_BACKEND:
                $data = AdminPages::where('parent_id',0)->groupBy("module_id")->get();
                return view("users::roles.permissions",compact(['role','data','slug']));
                break;
            case Roles::ACCESS_TO_FRONTEND:
                $dataFront = FrontendPage::where('parent_id',NULL)->groupBy("module_id")->get();
                return view("users::membership.permissions",compact(['role','dataFront','slug']));
                break;
            case Roles::ACCESS_TO_BOTH:
                $data = AdminPages::where('parent_id',0)->groupBy("module_id")->get();
                $dataFront = FrontendPage::where('parent_id',NULL)->groupBy("module_id")->get();
                return view("users::roles.role-permissions",compact(['role','data', 'dataFront','slug']));
                break;
        }

    }

    public function postPermissions(Request $request)
    {
        $data = $request->except('_token');

        $newRole = Roles::find($data['roleID']);
        switch($data['page_type']) {
            case 'back':
                $page = AdminPages::find($data['pageID']);
                $rolesString = AdminPages::getRolesByPage($page->id,false);
                if($data['isChecked'] == 'yes'){
                    $rolesString[] = $newRole->slug;
                }else{
                    if(($key = array_search($newRole->slug, $rolesString)) !== false) {
                        unset($rolesString[$key]);
                    }
                }
                $roles = (count($rolesString)) ? implode(',',$rolesString) : null;

                PermissionRole::optimizePageRoles($page,$roles);
                $data = AdminPages::where('parent_id',0)->groupBy("module_id")->get();
                $role = Roles::where('slug',$request->role_slug)->first();
                $html = view('users::roles._partials.perm_list',compact(['role','data']))->render();
                return \Response::json(['error' => false, 'html' => $html]);
                break;
            case 'front':
                $page = FrontendPage::find($data['pageID']);
                $rolesString = FrontendPage::getRolesByPage($page->id,false);
                if($data['isChecked'] == 'yes'){
                    $rolesString[] = $newRole->slug;
                }else{
                    if(($key = array_search($newRole->slug, $rolesString)) !== false) {
                        unset($rolesString[$key]);
                    }
                }
                $roles = (count($rolesString)) ? implode(',',$rolesString) : null;
                PermissionRole::optimizePageRoles($page,$roles, 'front');
                $dataFront = FrontendPage::where('parent_id',Null)->groupBy("module_id")->get();
                $role = Roles::where('slug',$request->role_slug)->first();
                $html = view('users::membership._partials.perm_list',compact(['role','dataFront']))->render();
                return \Response::json(['error' => false, 'html' => $html]);
                break;

        }
//        $page = AdminPages::find($data['pageID']);
//        $rolesString = AdminPages::getRolesByPage($page->id,false);

//        if($data['isChecked'] == 'yes'){
//            $rolesString[] = $newRole->slug;
//        }else{
//            if(($key = array_search($newRole->slug, $rolesString)) !== false) {
//                unset($rolesString[$key]);
//            }
//        }
//        $roles = (count($rolesString)) ? implode(',',$rolesString) : null;
//
//        PermissionRole::optimizePageRoles($page,$roles);
//        $data = AdminPages::where('parent_id',0)->groupBy("module_id")->get();
//        $role = Roles::where('slug',$request->role_slug)->first();
//        $html = view('users::roles._partials.perm_list',compact(['role','data']))->render();

//        return \Response::json(['error' => false, 'html' => $html]);
    }

    private function validateModule ($basename)
    {
        if (isset($this->modules->$basename)) {
            return $this->modules->$basename;
        } else {
            if (isset($this->extra_modules->$basename)) {
                return $this->extra_modules->$basename;
            }
        }

        return false;
    }

    public function assignPermissions(Request $request, Permissions $permissions, PermissionRole $permissionRole)
    {
        $input = $request->all();
        $permissions = array_get($input, 'permission');

        if(isset($input['access'])){
            PermissionRole::where('role_id',$input['role_id'])->delete();
        }else{
            DB::table('permission_role')->truncate();
        }

        if ($permissions != '' && $permissions != null)
            foreach ($permissions as $r_key => $permission) {
                foreach ($permission as $p_key => $per) {
                    $role = Roles::find($r_key);
                    $p_r = new PermissionRole();
                    $p_r->permission_id = $p_key;
                    $p_r->role_id = $r_key;
                    $p_r->save();
                }
            }

        return redirect('/admin/users/roles-configuration')->with([
            'flash' => [
                'message' => 'Permisiions successfuly assigned',
                'class' => 'alert-success'
            ]
        ]);
    }

    public function toggleChild(Request $request)
    {
        $data = $request->except('_token');

        $permission = Permission::find($data['permID']);
        $current = Permission::where('parent', $data['currentID'])->get();
        if ($permission) {
            $childs = Permission::where('parent', $permission->id)->get();
            if(isset($data['access'])){
                $roles = Roles::where('id',$data['roleID'])->get();
            }else{
                $roles = Roles::where('id','!=',User::ROLE_USER)->get();
            }

            $permissions = Permissions::orderBy('name')->get();
            $permission_role = [];
            if (isset($data['permList'])) {
                $permission_role = $data['permList'];
            }

            if ($data['isChecked'] == "yes") {
                if (count($current) > 0) {
                    $permission_role[] = $data['roleID'] . '-' . $data['permID'];

                    foreach ($childs as $child) {
                        $permission_role[] = $data['roleID'] . '-' . $child->id;
                    }
                } else {
                    $permission_role[] = $data['roleID'] . '-' . $data['currentID'];
                }
            } else {
                if (count($current) > 0) {
                    if (($key = array_search($data['roleID'] . '-' . $data['permID'], $permission_role)) !== false) {
                        unset($permission_role[$key]);
                    }
                    foreach ($childs as $child) {
                        $deepChildes = Permission::where('parent', $child->id)->get();
                        foreach ($deepChildes as $c) {
                            if (($key = array_search($data['roleID'] . '-' . $c->id, $permission_role)) !== false) {
                                unset($permission_role[$key]);
                            }
                        }
                        if (($key = array_search($data['roleID'] . '-' . $child->id, $permission_role)) !== false) {
                            unset($permission_role[$key]);
                        }
                    }
                } else {
                    $key = array_search($data['roleID'] . '-' . $data['currentID'], $permission_role);
                    unset($permission_role[$key]);
                }
            }
            $html = View::make('users::roles.forms._permissions_form')->with(['permissions' => $permissions, 'roles' => $roles, 'permission_role' => $permission_role])->render();

            return \Response::json(['data' => $html, 'code' => 200, 'error' => false]);
        }
    }

    public function getAccess($id){
        $role = Role::find($id);
        if(! $role)
            return redirect()->back();

        $permissions = Permissions::orderBy('name')->get();

        $permission_role = DB::table('permission_role')
            ->select(DB::raw('CONCAT(role_id,"-",permission_id) AS detail,id'))
            ->lists('detail', 'id');

        $menus = json_decode(File::get('appdata/resources/menus/admin/1.json'),true);


//        dd($role->getPermissions());
        return view('users::roles.access',compact(['role','permissions','permission_role','menus']));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postAddAccess(Request $request)
    {
        if ($request->ajax()) {
            $perm_id = $request->get('permID');
            $roleID = $request->get('roleID');
            $child = $request->get('child');

            if (Permissions::find($perm_id) && $r = Role::find($roleID)) {
                $main = json_decode(File::get('appdata/resources/menus/admin/1.json'), true);
                $f = File::get('appdata/resources/menus/admin/5.json');
                $p = Permissions::find($perm_id);
                $r->assignPermission($perm_id);
                $this->makeJson($r);
                $new = Role::find($roleID);

                $html = View::make('users::roles._partials.original_menu')->with('menus', $main)->with('role', $new)->render();

                if($child == 0){
                    return \Response::json(['data' => $html, 'code' => 200, 'error' => false]);
                }else{
                    $list = Permissions::where('parent',$p->parent)->get();
                    $rigth_html = View::make('users::roles._partials.content_perm')->with('list', $list)->with('role', $new)->render();

                    return \Response::json(['data' => $html, 'right_html' => $rigth_html ,'code' => 200, 'error' => false]);
                }

            }

            return \Response::json(['code' => 500, 'error' => true]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postRemoveAccess(Request $request)
    {
        if ($request->ajax()) {
            $perm_id = $request->get('permID');
            $roleID = $request->get('roleID');
            $child = $request->get('child');

            $main = json_decode(File::get('appdata/resources/menus/admin/1.json'), true);
            if ($p = Permissions::find($perm_id) && $r = Role::find($roleID)) {
                $list = Permissions::getChilds($perm_id);
                $list[] = $perm_id;
                $r->permissions()->detach($list);
                $this->makeJson($r);

                $new = Role::find($roleID);
                $html = View::make('users::roles._partials.original_menu')->with('menus', $main)->with('role', $new)->render();

                if($child == 0){
                    return \Response::json(['data' => $html, 'code' => 200, 'error' => false]);
                }else{
                    $list = Permissions::where('parent',$perm_id)->get();
                    $rigth_html = View::make('users::roles._partials.content_perm')->with('list', $list)->with('role', $new)->render();

                    return \Response::json(['data' => $html, 'right_html' => $rigth_html ,'code' => 200, 'error' => false]);
                }
            }

            return \Response::json(['code' => 500, 'error' => true]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postShowEdit(Request $request){
        $perm_id = $request->get('permID');
        $roleID = $request->get('roleID');

        if($p = Permissions::find($perm_id) && $r = Role::find($roleID)){
            $list = Permissions::where('parent',$perm_id)->get();
            $html = View::make('users::roles._partials.content_perm')->with('list',$list)->with('role',$r)->render();

            return \Response::json(['data' => $html, 'code' => 200, 'error' => false]);
        }

        return \Response::json(['code' => 500, 'error' => true]);
    }

    private function item($menu,$item,$p = false){
        foreach($menu as $k => $v){
            if($v['title'] == $item){
                if($p){
                    unset($menu[$k]['children']);
                }
                return $menu[$k];
            }
        }

        return false;
    }

    public function makeJson($r){
        //using shinobi package models
        $data = array();
        foreach($r->permissions()->where('parent',0)->get() as $perm){
            $data[] = [
                'title' => $perm->name,
                'custom-link' => $perm->slug,
                "icon" => "fa fa-dashboard fa-fw",
                'is_core' => 'yes'
            ];
        }

        foreach($r->permissions()->where('parent','!=',0)->get() as $perm){
            $parent = Permissions::find($perm->parent);
            foreach($data as $k => $v){
                if($v['title'] == $parent->name){
                    $data[$k]['children'][] = [
                        'title' => $perm->name,
                        'custom-link' => "/admin/".str_replace('.','/',$perm->slug),
                        "icon" => "fa fa-dashboard fa-fw",
                        'is_core' => 'yes'
                    ];
                }
            }
        }

        //using our model for get menus
        $role = Roles::find($r->id);
        $menu = $role->menus()->where('menus_id',1)->first();
        File::put(config('paths.ADMIN_MENU_VARIATION').'/'.$menu->id.'.json',json_encode($data,true));
        return $data;
    }
}
