<?php
/**
 * Created by PhpStorm.
 * User: Comp1
 * Date: 12/5/2016
 * Time: 4:39 PM
 */

namespace Sahakavatar\User\Models;

use Illuminate\Database\Eloquent\Model;


class Membership extends Model
{
    protected $table = 'memberships';
    protected $guarded = ['id'];

    public function users()
    {
        return $this->hasMany('App\Modules\Users\User', 'membership');
    }

    public function permissions()
    {
        return $this->belongsToMany('App\Modules\Users\Models\Permissions', 'membership_permission','membership_id','permission_id');
    }

}