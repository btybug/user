<?php
/**
 * Created by PhpStorm.
 * User: muzammal
 * Date: 8/8/2016
 * Time: 1:31 PM
 */

namespace Btybug\User\Repository;

use Btybug\Cms\Repositories\GeneralRepository;
use Btybug\User\Models\UsersProfile;


class UserProfileRepository extends GeneralRepository
{
    public function createProfile(int $id)
    {
        return $this->model()->create([
            'user_id' => $id
        ]);
    }

    public function model()
    {
        return new UsersProfile();
    }

}