<?php

/**
 * Created by PhpStorm.
 * User: Ara Arakelyan
 * Date: 7/19/2017
 * Time: 3:52 PM
 */

namespace Sahakavatar\User\Services;

use Sahakavatar\Cms\Services\GeneralService;
use Sahakavatar\Console\Repository\AdminPagesRepository;
use Sahakavatar\Console\Repository\FrontPagesRepository;
use Sahakavatar\User\Repository\PermissionRepository;
use Sahakavatar\User\Repository\PermissionRoleRepository;
use Sahakavatar\User\Repository\RoleRepository;

class PermissionService extends GeneralService
{

    private $roleRepository;
    private $adminPagesRepository;
    private $frontPagesRepository;
    private $permissionRoleRepository;
    private $permissionRepository;

    public function __construct(
        RoleRepository $roleRepository,
        AdminPagesRepository $adminPagesRepository,
        FrontPagesRepository $frontPagesRepository,
        PermissionRoleRepository $permissionRoleRepository,
        PermissionRepository $permissionRepository
    )
    {
        $this->roleRepository = $roleRepository;
        $this->adminPagesRepository = $adminPagesRepository;
        $this->frontPagesRepository = $frontPagesRepository;
        $this->permissionRoleRepository = $permissionRoleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    public function storePermission(array $requestData)
    {
        $newRole = $this->roleRepository->find($requestData['roleID']);
        switch ($requestData['page_type']) {
            case 'back':
                $page = $this->adminPagesRepository->find($requestData['pageID']);
                $rolesString = $this->adminPagesRepository->getRolesByPage($page->id, false);
                if ($requestData['isChecked'] == 'yes') {
                    $rolesString[] = $newRole->slug;
                } else {
                    if (($key = array_search($newRole->slug, $rolesString)) !== false) {
                        unset($rolesString[$key]);
                    }
                }

                $this->permissionRoleRepository->optimizePageRoles($page, $rolesString);
                $data = $this->adminPagesRepository->getGroupedWithModule();
                $role = $this->roleRepository->findBy('slug', $requestData['role_slug']);
                $html = view('users::roles._partials.perm_list', compact(['role', 'data']))->render();
                return $html;
                break;
            case 'front':
                $page = $this->frontPagesRepository->find($requestData['pageID']);
                $rolesString = $this->frontPagesRepository->getRolesByPage($page->id, false);
                if ($requestData['isChecked'] == 'yes') {
                    $rolesString[] = $newRole->slug;
                } else {
                    if (($key = array_search($newRole->slug, $rolesString)) !== false) {
                        unset($rolesString[$key]);
                    }
                }
                $this->permissionRoleRepository->optimizePageRoles($page, $rolesString, 'front');
                $dataFront = $this->frontPagesRepository->getGroupedWithModule();
                $role = $this->roleRepository->findBy('slug', $requestData['role_slug']);
                $html = view('users::roles._partials.front_perm_list', compact(['role', 'dataFront']))->render();
                return $html;
                break;
        }
    }

    public function assignPermissions(array $data)
    {
        $permissions = $data['permission'];
        if (isset($data['access'])) {
            $permissionRole = $this->permissionRoleRepository->findBy('role_id', $data['role_id']);
            $this->permissionRoleRepository->delete($permissionRole->id);
        } else {
            $this->permissionRoleRepository->model()->truncate();
        }
        if ($permissions != '' && $permissions != null)
            foreach ($permissions as $r_key => $permission) {
                foreach ($permission as $p_key => $per) {
                    $this->permissionRoleRepository->create([
                        'permission_id' => $p_key,
                        'role_id' => $r_key
                    ]);
                }
            }
    }

    public function toggleChild(array $data)
    {
        $permission = $this->permissionRepository->find($data['permID']);
        $current = $this->permissionRepository->getBy('parent', $data['currentID']);
        if ($permission) {
            $children = $this->permissionRepository->getBy('parent', $permission->id);
            if (isset($data['access'])) {
                $roles = $this->roleRepository->getBy('id', $data['roleID']);
            } else {
                $roles = $this->roleRepository->getBy('id', '!=', User::ROLE_USER);
                //TODO ???? User::ROLE_USER
            }
            $permissions = $this->permissionRepository->model()->orderBy('name')->get();
            $permission_role = [];
            if (isset($data['permList'])) {
                $permission_role = $data['permList'];
            }

            if ($data['isChecked'] == "yes") {
                if (count($current) > 0) {
                    $permission_role[] = $data['roleID'] . '-' . $data['permID'];
                    foreach ($children as $child) {
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
                    foreach ($children as $child) {
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
            return View::make('users::roles.forms._permissions_form')->with(['permissions' => $permissions, 'roles' => $roles, 'permission_role' => $permission_role])->render();
        } else {
            return '';
        }
    }

}