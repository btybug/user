<?php
/**
 * Created by PhpStorm.
 * User: muzammal
 * Date: 8/8/2016
 * Time: 1:31 PM
 */

namespace Sahakavatar\User\Repository;

use Sahakavatar\Cms\Repositories\GeneralRepository;
use Sahakavatar\User\Models\UsersProfile;


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