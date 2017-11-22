<?php
/**
 * Created by PhpStorm.
 * User: muzammal
 * Date: 8/8/2016
 * Time: 1:31 PM
 */

namespace Btybug\User\Repository;

use Btybug\btybug\Repositories\GeneralRepository;
use Btybug\User\Models\PermissionRole;

class PermissionRoleRepository extends GeneralRepository
{

    public function optimizePageRoles($page, array $roles, string $pageType = 'back')
    {
        if (count($roles)) {
            $roleIDs = [];
            foreach ($roles as $role) {
                $roleRepo = new RoleRepository();
                if ($r = $roleRepo->findBy('slug', $role)) {
                    $roleIDs[] = $r->id;
                }
            }
            $this->optimizeMultiLevelChildren($page->permission_role, $page->childs, $roleIDs);

            $page->permission_role()->whereNotIn('role_id', $roleIDs)->delete();
            if (!empty($roleIDs)) {
                foreach ($roleIDs as $value) {
                    if (!$page->permission_role()->where('role_id', $value)->first()) {
                        $this->model->create(['page_id' => $page->id, 'role_id' => $value, 'page_type' => $pageType]);
                    }
                }
            }
        } else {

            $this->optimizeMultiLevelChildren($page->permission_role, $page->childs);
            $page->permission_role()->delete();
        }

        return $page->permission_role;
    }

    public function optimizeMultiLevelChildren($permission_roles, $childPages, $roleIDs = null)
    {
        if (count($permission_roles)) {
            foreach ($permission_roles as $pr) {
                if (count($childPages)) {
                    foreach ($childPages as $cp) {
                        if ($cp->permission_role()->where('role_id', $pr->role_id)->first()) {
                            $permission_roles = $cp->permission_role;
                            if ($roleIDs) {
                                $cp->permission_role()->whereNotIn('role_id', $roleIDs)->delete();
                                $this->optimizeMultiLevelChildren($permission_roles, $cp->childs, $roleIDs);
                            } else {
                                $cp->permission_role()->where('role_id', $pr->role_id)->delete();
                                $this->optimizeMultiLevelChildren($permission_roles, $cp->childs);
                            }
                        }
                    }
                }
            }
        }
    }

    public function getBackendPagesWithRoleAndPage($roleID, $pageID)
    {
        return $this->model()->where('role_id', $roleID)->where('page_id', $pageID)->where('page_type', 'back')->first();
    }

    public function model()
    {
        return new PermissionRole();
    }

    public function getFrontPagesWithRoleAndPage($roleID, $pageID)
    {
        return $this->model()->where('role_id', $roleID)->where('page_id', $pageID)->where('page_type', 'front')->first();
    }
}