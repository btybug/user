<?php

namespace Sahakavatar\User\Models;

use Illuminate\Database\Eloquent\Model;
use Sahakavatar\Settings\Models\Settings;
use Sahakavatar\User\Traits\ShinobiTrait;

class Roles extends Model
{
    use ShinobiTrait;

    const SUPERADMIN = 'superadmin';
    const ACCESS_TO_BOTH = 0;
    const ACCESS_TO_BACKEND = 1;
    const ACCESS_TO_FRONTEND = 2;
    public static $accessList = [
        self::ACCESS_TO_BACKEND => 'Back End',
        self::ACCESS_TO_FRONTEND => 'Front End',
        self::ACCESS_TO_BOTH => 'Back and Front End',
    ];
    protected static $menus = [
        'Left Navbar Core' => 1,
        'User Menu Core' => 2,
        'Left Header Core' => 3,
        'Right Header Core' => 4,
    ];
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    /**
     * The attributes which using Carbon.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    public static function getDefaultFrontEndRole()
    {
        return Settings::where('settingkey', 'default_field_html')->first();
    }

    public static function getFrontendRoles()
    {
        return self::where('access', self::ACCESS_TO_FRONTEND)->pluck('name', 'id');
    }

    public function users()
    {
        return $this->hasMany('Sahakavatar\User\User', 'role_id');
    }

    public function permission_role()
    {
        return $this->hasMany('Sahakavatar\User\Models\PermissionRole', 'page_id', 'id');
    }

    public function menus()
    {
        return $this->hasMany('App\Modules\Backend\MenuVariation', 'user_role', 'id');
    }

    public function getAccessName()
    {
        return self::$accessList[$this->access];
    }
}
