<?php
/**
 * Created by PhpStorm.
 * User: muzammal
 * Date: 8/8/2016
 * Time: 1:31 PM
 */

namespace Sahakavatar\User\Repository;

use Sahakavatar\Cms\Repositories\GeneralRepository;
use Sahakavatar\User\User;


class UserRepository extends GeneralRepository
{
    /**
     * Page constructor.
     */
    public function __construct()
    {
        $this->model = new User();
    }

}