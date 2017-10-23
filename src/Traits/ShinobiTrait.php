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

namespace Sahakavatar\User\Traits;

/**
 * Class ShinobiTrait
 * @package App\Modules\Users\Traits
 */
trait ShinobiTrait
{
    /*
    |----------------------------------------------------------------------
    | Role Trait Methods
    |----------------------------------------------------------------------
    |
    */


    /**
     * Get all user role permissions.
     *
     * @return array|null
     */
    public function getPermissions()
    {
        $permissions = [[], []];

        $permissions[] = $this->role->getPermissions();

        return call_user_func_array('array_merge', $permissions);
    }
    /*
    |----------------------------------------------------------------------
    | Permission Trait Methods
    |----------------------------------------------------------------------
    |
    */

    /**
     * Check if user has at least one of the given permissions.
     *
     * @param array $permissions
     *
     * @return bool
     */
    public function canAtLeast(array $permissions)
    {
        $can = false;

        if ($this->role->special === 'no-access') {
            return false;
        }

        if ($this->role->special === 'all-access') {
            return true;
        }

        if ($this->role->canAtLeast($permissions)) {
            $can = true;
        }

        return $can;
    }

    /**
     * Magic __call method to handle dynamic methods.
     *
     * @param string $method
     * @param array $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments = [])
    {
        // Handle isRoleslug() methods
        if (starts_with($method, 'is') and $method !== 'is') {
            $role = substr($method, 2);

            return $this->isRole($role);
        }

        // Handle canDoSomething() methods
        if (starts_with($method, 'can') and $method !== 'can') {
            $permission = substr($method, 3);

            return $this->can($permission);
        }

        return parent::__call($method, $arguments);
    }

    /**
     * Checks if the user has the given role.
     *
     * @param string $slug
     *
     * @return bool
     */
    public function isRole($slug)
    {
        $slug = strtolower($slug);

        if ($this->role->slug == $slug) {
            return true;
        }

        return false;
    }

    /*
    |----------------------------------------------------------------------
    | Magic Methods
    |----------------------------------------------------------------------
    |
    */

    /**
     * Check if user has the given permission.
     *
     * @param string $permission
     * @param array $arguments
     *
     * @return bool
     */
    public function can($permission, $arguments = [])
    {
        $can = false;

        if ($this->role->special === 'no-access') {
            return false;
        }

        if ($this->role->special === 'all-access') {
            return true;
        }

        if ($this->role->can($permission)) {
            $can = true;
        }

        return $can;
    }
}
