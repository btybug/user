<?php
/**
 * Created by PhpStorm.
 * User: Comp2
 * Date: 12/26/2016
 * Time: 4:42 PM
 */
namespace App\Modules\Users\Models;
use Auth;
use Illuminate\Database\Eloquent\Model;



class Sessions extends Model
{
    protected $table = 'sessions';

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Modules\Users\User','user_id');
    }

    public static function authUsers(){
        return self::whereNotNull('user_id')->where('user_id','!=', Auth::id())->paginate(15);
    }

}