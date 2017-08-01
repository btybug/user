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

use Sahakavatar\Console\Repository\AdminPagesRepository;
use Sahakavatar\Console\Repository\FrontPagesRepository;
use Sahakavatar\User\Http\Requests\Role\CreateRoleRequest;
use Sahakavatar\User\Http\Requests\Role\EditRoleRequest;
use Sahakavatar\User\Repository\PermissionRoleRepository;
use Sahakavatar\User\Repository\RoleRepository;
use Sahakavatar\User\Repository\UserRepository;
use Sahakavatar\User\Services\PermissionService;

class RolesController extends Controller
{
    private $modules;
    private $extra_modules;

    public function __construct(Guard $auth)
    {
        $this->modules = json_decode(\File::get(storage_path('app/modules.json')));
        $this->extra_modules = json_decode(\File::get(storage_path('app/plugins.json')));
        $this->auth = $auth;
        $this->middleware('auth');
    }

    public function getIndex(
        RoleRepository $roleRepository,
        UserRepository $userRepository
    )
    {
        $roles = $roleRepository->getAll();
        $defaultRoles = $userRepository->getDefaultRoles();
        return view('users::roles.index', compact(['roles', 'defaultRoles']));
    }

    public function getCreate(
        RoleRepository $roleRepository
    )
    {
        $accessList = $roleRepository->getAccessList();
        return view('users::roles.create', compact(['accessList']));
    }

    public function postCreate(
        CreateRoleRequest $request,
        RoleRepository $roleRepository
    )
    {
        $requestData = $request->except('_token');
        $roleRepository->create($requestData);
        return redirect('/admin/users/roles')->with('message', 'Role has been created successfully.');
    }

    public function getEdit(
        Request $request,
        RoleRepository $roleRepository
    )
    {
        $accessList = $roleRepository->getAccessList();
        $role = $roleRepository->find($request->id);
        if (!$role) return redirect()->back();

        return view('users::roles.edit', compact(['role', 'accessList']));
    }

    public function postEdit(
        EditRoleRequest $request,
        RoleRepository $roleRepository
    )
    {
        $role = $roleRepository->findOrFail($request->id);
        $requestData = $request->except('_token');

        $roleRepository->update($role->id, $requestData);
        return redirect('/admin/users/roles')->with('message', 'Role has been updated successfully.');
    }

    public function postDelete(
        Request $request,
        RoleRepository $roleRepository
    )
    {
        $result = false;
        if ($request->slug) {
            $role = $roleRepository->find($request->slug);
            $result = $roleRepository->delete($role->id);
        }
        return \Response::json(['success' => $result]);
    }

    public function getPermissions(
        Request $request,
        RoleRepository $roleRepository,
        AdminPagesRepository $adminPagesRepository,
        FrontPagesRepository $frontPagesRepository
    )
    {
        $role = $roleRepository->findBy('slug', $request->slug);
        if (!$role) abort(404);

        switch ($role->access) {
            case $roleRepository::ACCESS_TO_BACKEND:
                $data = $adminPagesRepository->getGroupedWithModule();
                return view("users::roles.permissions", compact(['role', 'data', 'slug']));
                break;
            case $roleRepository::ACCESS_TO_FRONTEND:
                $dataFront = $frontPagesRepository->getGroupedWithModule();
                return view("users::membership.permissions", compact(['role', 'dataFront', 'slug']));
                break;
            case $roleRepository::ACCESS_TO_BOTH:
                $data = $adminPagesRepository->getGroupedWithModule();
                $dataFront = $frontPagesRepository->getGroupedWithModule();
                return view("users::roles.role-permissions", compact(['role', 'data', 'dataFront', 'slug']));
                break;
        }

    }

    public function postPermissions(
        Request $request,
        PermissionService $permissionService
    )
    {
        $requestData = $request->except('_token');
        $responseHtml = $permissionService->storePermission($requestData);
        return \Response::json(['error' => false, 'html' => $responseHtml]);
    }

//    private function validateModule($basename)
//    {
    //TODO delete if needed
//        if (isset($this->modules->$basename)) {
//            return $this->modules->$basename;
//        } else {
//            if (isset($this->extra_modules->$basename)) {
//                return $this->extra_modules->$basename;
//            }
//        }
//
//        return false;
//    }

    public function assignPermissions(
        Request $request,
        PermissionService $permissionService
    )
    {
        $requestData = $request->except('_token');
        $permissionService->assignPermissions($requestData);
        return redirect('/admin/users/roles-configuration')->with([
            'flash' => [
                'message' => 'Permisiions successfuly assigned',
                'class' => 'alert-success'
            ]
        ]);
    }

    public function toggleChild(
        Request $request,
        PermissionService $permissionService
    )
    {
        $requestData = $request->except('_token');
        $responseHtml = $permissionService->toggleChild($requestData);
        return \Response::json(['data' => $responseHtml, 'code' => 200, 'error' => false]);

    }

    public function getAccess($id)
    {
        $role = Role::find($id);
        if (!$role)
            return redirect()->back();

        $permissions = Permissions::orderBy('name')->get();

        $permission_role = DB::table('permission_role')
            ->select(DB::raw('CONCAT(role_id,"-",permission_id) AS detail,id'))
            ->lists('detail', 'id');

        $menus = json_decode(File::get('appdata/resources/menus/admin/1.json'), true);


//        dd($role->getPermissions());
        return view('users::roles.access', compact(['role', 'permissions', 'permission_role', 'menus']));
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

                if ($child == 0) {
                    return \Response::json(['data' => $html, 'code' => 200, 'error' => false]);
                } else {
                    $list = Permissions::where('parent', $p->parent)->get();
                    $rigth_html = View::make('users::roles._partials.content_perm')->with('list', $list)->with('role', $new)->render();

                    return \Response::json(['data' => $html, 'right_html' => $rigth_html, 'code' => 200, 'error' => false]);
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

                if ($child == 0) {
                    return \Response::json(['data' => $html, 'code' => 200, 'error' => false]);
                } else {
                    $list = Permissions::where('parent', $perm_id)->get();
                    $rigth_html = View::make('users::roles._partials.content_perm')->with('list', $list)->with('role', $new)->render();

                    return \Response::json(['data' => $html, 'right_html' => $rigth_html, 'code' => 200, 'error' => false]);
                }
            }

            return \Response::json(['code' => 500, 'error' => true]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postShowEdit(Request $request)
    {
        $perm_id = $request->get('permID');
        $roleID = $request->get('roleID');

        if ($p = Permissions::find($perm_id) && $r = Role::find($roleID)) {
            $list = Permissions::where('parent', $perm_id)->get();
            $html = View::make('users::roles._partials.content_perm')->with('list', $list)->with('role', $r)->render();

            return \Response::json(['data' => $html, 'code' => 200, 'error' => false]);
        }

        return \Response::json(['code' => 500, 'error' => true]);
    }

    private function item($menu, $item, $p = false)
    {
        foreach ($menu as $k => $v) {
            if ($v['title'] == $item) {
                if ($p) {
                    unset($menu[$k]['children']);
                }
                return $menu[$k];
            }
        }

        return false;
    }

    public function makeJson($r)
    {
        //using shinobi package models
        $data = array();
        foreach ($r->permissions()->where('parent', 0)->get() as $perm) {
            $data[] = [
                'title' => $perm->name,
                'custom-link' => $perm->slug,
                "icon" => "fa fa-dashboard fa-fw",
                'is_core' => 'yes'
            ];
        }

        foreach ($r->permissions()->where('parent', '!=', 0)->get() as $perm) {
            $parent = Permissions::find($perm->parent);
            foreach ($data as $k => $v) {
                if ($v['title'] == $parent->name) {
                    $data[$k]['children'][] = [
                        'title' => $perm->name,
                        'custom-link' => "/admin/" . str_replace('.', '/', $perm->slug),
                        "icon" => "fa fa-dashboard fa-fw",
                        'is_core' => 'yes'
                    ];
                }
            }
        }

        //using our model for get menus
        $role = Roles::find($r->id);
        $menu = $role->menus()->where('menus_id', 1)->first();
        File::put(config('paths.ADMIN_MENU_VARIATION') . '/' . $menu->id . '.json', json_encode($data, true));
        return $data;
    }
}
