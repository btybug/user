<?php
/**
 * Created by PhpStorm.
 * User: Comp2
 * Date: 12/26/2016
 * Time: 4:42 PM
 */

namespace Sahakavatar\User\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;


class Sessions extends Model
{
    public $timestamps = false;
    protected $table = 'sessions';

    public static function authUsers()
    {
        return self::whereNotNull('user_id')->where('user_id', '!=', Auth::id())->paginate(15);
    }

    public function user()
    {
        return $this->belongsTo('App\Modules\Users\User', 'user_id');
    }

}