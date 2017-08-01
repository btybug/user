<?php
/**
 * Created by PhpStorm.
 * User: muzammal
 * Date: 8/8/2016
 * Time: 1:31 PM
 */

namespace Sahakavatar\User\Repository;

use Sahakavatar\Cms\Repositories\GeneralRepository;
use Sahakavatar\User\Models\Permissions;
use Sahakavatar\User\Models\Roles;

class PermissionRepository extends GeneralRepository
{

    public function model()
    {
        return new Permissions();
    }

}