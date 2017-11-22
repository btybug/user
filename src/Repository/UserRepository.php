<?php
/**
 * Created by PhpStorm.
 * User: muzammal
 * Date: 8/8/2016
 * Time: 1:31 PM
 */

namespace Btybug\User\Repository;

use Btybug\btybug\Repositories\GeneralRepository;
use Btybug\User\User;


class UserRepository extends GeneralRepository
{
    public function getDefaultRoles()
    {
        $model = $this->model();
        return $model::$defaultRoles;
    }

    public function model()
    {
        return new User();
    }

}