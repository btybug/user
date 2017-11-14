<?php
/**
 * Created by PhpStorm.
 * User: muzammal
 * Date: 8/8/2016
 * Time: 1:31 PM
 */

namespace Btybug\User\Repository;

use Btybug\Cms\Repositories\GeneralRepository;
use Btybug\User\Models\Status;


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