<?php
/**
 * Created by PhpStorm.
 * User: muzammal
 * Date: 8/8/2016
 * Time: 1:31 PM
 */

namespace Btybug\User\Repository;

use Btybug\btybug\Repositories\GeneralRepository;
use Btybug\User\Models\Roles;

class RoleRepository extends GeneralRepository
{
    const ACCESS_TO_BOTH = 0;
    const ACCESS_TO_BACKEND = 1;
    const ACCESS_TO_FRONTEND = 2;

    public function getAccessList()
    {
        $model = $this->model();
        return $model::$accessList;
    }

    public function model()
    {
        return new Roles();
    }

    public function getRolesSeperetedWith()
    {
        return $this->model()->where('slug', '!=', 'superadmin')->pluck('slug', 'slug')->toArray();
    }

    public function getFrontRoles()
    {
        return $this->model()->where('access', '!=', 1)->get();
    }

    public function getAllWithGuest()
    {
        $items = $this->getAll();
        $items->push([
            'id' => 0,
           'name' => 'Guests',
           'slug' => 'guests',
           'special' => 'no-access'
        ]);

        return $items->sort()->toArray();
    }
}