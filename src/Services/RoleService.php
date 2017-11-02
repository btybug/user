<?php

/**
 * Created by PhpStorm.
 * User: Ara Arakelyan
 * Date: 7/19/2017
 * Time: 3:52 PM
 */

namespace Btybug\User\Services;

use Sahakavatar\Cms\Services\GeneralService;
use Btybug\User\Repository\RoleRepository;

class RoleService extends GeneralService
{

    private $roleRepository;

    public function __construct(
        RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getRolesList()
    {
        return $this->roleRepository->model()->pluck('name', 'id')->toArray();
    }

    public function getFrontRolesSeperetedWith()
    {
        $both = $this->getRolesSeperetedWith();
        $front = $this->getRolesSeperetedWith(',', 2);

        return $both . "," . $front;
    }

    public function getRolesSeperetedWith($seperator = ',', $access = 0)
    {
        $data = $this->roleRepository->model()->where('access', $access)->pluck('slug', 'slug')->toArray();
        return implode($seperator, $data);
    }
}