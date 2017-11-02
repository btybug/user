<?php
/**
 * Created by PhpStorm.
 * User: muzammal
 * Date: 8/8/2016
 * Time: 1:31 PM
 */

namespace Btybug\User\Repository;

use Sahakavatar\Cms\Repositories\GeneralRepository;
use Btybug\User\Models\Permissions;

class PermissionRepository extends GeneralRepository
{

    public function model()
    {
        return new Permissions();
    }

}