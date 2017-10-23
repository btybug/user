<?php

use App\Modules\Users\Groups;
use App\Modules\Users\Models\Roles;
use App\Modules\Users\User;

if (!function_exists('BBAdminRoles')) {
    /**
     *
     * Provides a list of all system admin roles
     *
     */
    function BBAdminRoles()
    {
        return Roles::get();
    }
}

if (!function_exists('BBAdminRolesList')) {
    /**
     *
     * Provides a list of all system admin roles in list style
     *
     */
    function BBAdminRolesList()
    {
        return Roles::all()->lists('name', 'id');

    }
}


if (!function_exists('BBAdminRole')) {
    /**
     *
     * Provides a list of all system admin roles
     *
     * @param $id
     * @return $role object | null
     */
    function BBAdminRole($id = null)
    {
        $role = null;
        if ($id) {
            $rs = Roles::find($id);
            if ($rs) {
                $role = $rs;
            }
        }

        return $role;
    }
}

if (!function_exists('BBAdminRoleUsers')) {
    /**
     *
     * Provides a list of all system admin users related to some specific rold
     *
     * @param Roll $id |null
     * @return $role users | null
     */
    function BBAdminRoleUsers($id = null)
    {
        $users = null;
        if ($id) {
            $rs = Roles::find($id);
            if ($rs) {
                $users = @$rs->role_user;
            }
        }
        return $users;
    }
}


if (!function_exists('BBAdminUsers')) {
    /**
     * Provides a list of all system users
     */
    function BBAdminUsers()
    {
        $user = new User;
        $users = $user->getAdmins();

        return $users;
    }
}

if (!function_exists('BBAdminUser')) {
    /**
     * Provides Details of single User
     *
     * @param null $id
     * @return User|null
     */
    function BBAdminUser($id = null)
    {
        $user = null;
        if ($id) {
            if ($rs = User::find($id)) {
                if (!$rs->isUser()) {
                    $user = $rs;
                }
            }
        }

        return $user;
    }
}


if (!function_exists('BBAuthUser')) {
    /**
     * @return User|null
     */
    function BBAuthUser()
    {
        if (Auth::check()) {
            return Auth::user();
        } else {
            return null;
        }
    }
}

if (!function_exists('BBUserGroups')) {
    /**
     * @return User|null
     */
    function BBUserGroups()
    {
        return Groups::get();
    }
}

if (!function_exists('BBUserGroup')) {
    /**
     * @return User|null
     */
    function BBUserGroup($id = null)
    {
        $group = null;
        if ($id) {
            $rs = Groups::find($id);
            if ($rs) {
                $group = $rs;
            }
        }
        return $group;
    }
}

if (!function_exists('BBUsers')) {
    /**
     * Provides a list of all system users
     */
    function BBUsers()
    {
        $user = new User;
        $users = $user->getSiteUsers();

        return $users;
    }
}

if (!function_exists('BBUser')) {
    /**
     * Provides Details of single User
     *
     * @param null $id
     * @return User|null
     */
    function BBUser($id = null)
    {
        $user = null;
        if ($id) {
            if ($rs = User::find($id)) {
                if ($rs->isUser()) {
                    $user = $rs;
                }
            }
        }

        return $user;
    }
}


if (!function_exists('BBGetUserName')) {
    /**
     * @param null $id
     * @return mixed|null|string
     */
    function BBGetUserName($id = null)
    {
        if ($id) {
            if ($user = User::find($id)) {
                if (!isset($user->profile)) {
                    return $user->username;
                }

                return ($user->profile->first_name || $user->profile->last_name) ?
                    $user->profile->first_name . ' ' . $user->profile->last_name : $user->username;
            }
        } else {
            if (Auth::check()) {
                if (!isset(Auth::user()->profile)) {
                    return Auth::user()->username;
                }

                return (Auth::user()->profile->first_name || Auth::user()->profile->last_name) ? Auth::user()->profile->first_name . ' ' . Auth::user()->profile->last_name : Auth::user()->username;
            }
        }

        return null;
    }
}

if (!function_exists('BBGetUserEmail')) {
    /**
     * @param null $id
     * @return mixed|null|string
     */
    function BBGetUserEmail($id = null)
    {
        if ($id) {
            if ($user = User::find($id)) {
                return $user->email;
            }
        } else {
            if (Auth::check()) {
                return Auth::user()->email;
            }
        }
        return null;
    }
}


if (!function_exists('BBGetUserJoin')) {
    /**
     * @param null $id
     * @return mixed|null|string
     */
    function BBGetUserJoin($id = null)
    {
        if ($id) {
            if ($user = User::find($id)) {
                return $user->created_at->format('m/d/Y');
            }
        } else {
            if (Auth::check()) {
                return Auth::user()->created_at->format('m/d/Y');
            }
        }
        return null;
    }
}

if (!function_exists('BBGetUserCover')) {
    /**
     * @param null $id
     * @return URL|null|string
     */
    function BBGetUserCover($id = null)
    {
        if ($id) {
            if ($user = User::find($id)) {
                return ($user->profile->cover) ? url($user->profile->cover) : '/resources/assets/images/profile.jpg';
            }
        } else {
            if (Auth::check()) {
                return (Auth::user()->profile->cover) ? url(Auth::user()->profile->cover) : '/resources/assets/images/profile.jpg';
            }
        }

        return null;
    }
}

if (!function_exists('BBGetUserRole')) {
    /**
     * @param null $id
     * @return null
     */
    function BBGetUserRole($id = null)
    {
        if ($id) {
            if ($user = User::find($id)) {
                return ($user->role->name);
            }
        } else {
            if (Auth::check()) {
                return (Auth::user()->role->name);
            }
        }

        return null;
    }
}

