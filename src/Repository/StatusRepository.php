<?php
/**
 * Created by PhpStorm.
 * User: muzammal
 * Date: 8/8/2016
 * Time: 1:31 PM
 */

namespace Sahakavatar\User\Repository;

use Sahakavatar\Cms\Repositories\GeneralRepository;
use Sahakavatar\User\Models\Status;


class StatusRepository extends GeneralRepository
{


    /**
     * @return Status
     */
    protected function model()
    {
        return new Status();
    }

}