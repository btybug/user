<?php

/**
 * Created by PhpStorm.
 * User: Ara Arakelyan
 * Date: 7/19/2017
 * Time: 3:52 PM
 */

namespace Sahakavatar\User\Services;

use Illuminate\Support\Facades\Auth;
use Sahakavatar\Cms\Services\GeneralService;
use Sahakavatar\User\Repository\RoleRepository;

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

}