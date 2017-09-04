<?php
/**
 * Created by PhpStorm.
 * User: muzammal
 * Date: 8/8/2016
 * Time: 1:31 PM
 */

namespace Sahakavatar\User\Repository;

use Sahakavatar\Cms\Repositories\GeneralRepository;
use Sahakavatar\User\Models\Roles;

class RoleRepository extends GeneralRepository
{
    const ACCESS_TO_BOTH = 0;
    const ACCESS_TO_BACKEND = 1;
    const ACCESS_TO_FRONTEND = 2;

    public function model()
    {
        return new Roles();
    }

    public function getAccessList()
    {
        $model = $this->model();
        return $model::$accessList;
    }

    public function getRolesSeperetedWith()
    {
        return $this->model()->where('slug', '!=', 'superadmin')->pluck('slug', 'slug')->toArray();
    }

}