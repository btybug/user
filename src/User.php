<?php

namespace Btybug\User;

use Auth;
use File;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Btybug\User\Models\Roles;
use Btybug\User\Models\UsersProfile;
use Btybug\User\Traits\ShinobiTrait;

/**
 * Class User
 * @package Btybug\User
 */
class User extends Authenticatable
{

    use CanResetPassword, ShinobiTrait;
    /**
     *
     */
    const ROLE_SUPERADMIN = 1;
    /**
     *
     */
    const ROLE_SUPERADMIN_SLUG = 'superadmin';
    /**
     *
     */
    const ROLE_ADMIN = 2;
    /**
     *
     */
    const MAX_UPLOAD_FILE_SIZE = 10240;
    /**
     *
     */
    const PER_PAGE = 40;
    /**
     * @var string
     */
    public static $uploadPath = '/resources/assets/images/users/';
    /**
     * @var array
     */
    public static $defaultRoles = [
        self::ROLE_SUPERADMIN,
        self::ROLE_ADMIN
    ];
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $guarded = ['id'];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    /**
     * The attributes which using Carbon.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $_SESSION[$this->getTable()] = &$this;
    }

    /**
     * @param $user_id
     * @param $password
     */
    public static function changePassword($user_id, $password)
    {
        $user = self::find($user_id);
        $user->update(['password' => bcrypt($password)]);
    }

    /**
     * @param $email
     * @return mixed
     */
    public static function getUserByEmail($email)
    {
        return self::where('email', $email)->first();
    }

    /**
     * @param $username
     * @param $token
     * @return mixed
     */
    public static function checkUserActivation($username, $token)
    {
        return self::where('username', $username)->where('token', $token)->first();
    }

    /**
     * @return mixed
     */
    public static function showAvatar()
    {
        return Auth::user()->avatar;
    }

    /**
     * @param $id
     * @param string $action
     * @return bool
     */
//    public static function ranking($id, $action = 'delete')
//    {
//        $current = self::find($id);
//
//        if (!Auth::user()->isSuperadmin()) {
//            if ($current->isSuperadmin())
//                return false;
//
//            if ($current->isAdministrator()) {
//                if (Auth::user()->isAdministrator() && $action == 'edit') {
//                    return true;
//                }
//                return false;
//            }
//        }
//        return true;
//    }

    /**
     * @param $avatar
     * @param $new
     */
    public static function saveAvatar($avatar, $new)
    {
        self::createDir(base_path() . self::$uploadPath . $new->id . '/avatar');
        $imageName = $new->id . sha1(time()) . '.' . $avatar->getClientOriginalExtension();
        File::deleteDirectory(base_path() . User::$uploadPath . $new->id . '/avatar', true);
        $avatar->move(
            base_path() . User::$uploadPath . $new->id . DIRECTORY_SEPARATOR . 'avatar/', $imageName
        );
        $new->avatar = $imageName;
        $new->save();
    }

    /**
     * @param $path
     */
    private static function createDir($path)
    {
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }

    /**
     * @param $cover
     * @param $new
     */
    public static function saveCover($cover, $new)
    {
        self::createDir(base_path() . self::$uploadPath . $new->id . '/cover');
        $imageName = $new->id . sha1(time()) . '.' . $cover->getClientOriginalExtension();
        File::deleteDirectory(base_path() . User::$uploadPath . $new->id . '/cover', true);
        $cover->move(
            base_path() . User::$uploadPath . $new->id . DIRECTORY_SEPARATOR . 'cover/', $imageName
        );
        $new->cover = $imageName;
        $new->save();
    }

    /**
     *
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->password = bcrypt($model->password);
        });
        static::deleting(function ($model) {
            $profile = UsersProfile::where('user_id', $model->id);
            if ($profile) $profile->delete();
        });
    }

    /**
     * @return array
     */
    public function getDates()
    {
        return ['created_at', 'updated_at'];
    }

    /**
     * @param array $data
     * @return $this
     */
    public function toAttr(array $data)
    {
        foreach ($data as $key => $value) {
            $this->setAttribute($key, $value);
        }

        return $this;
    }

    /**
     * @param $model
     * @return static
     */
    public function addProfile()
    {
        return UsersProfile::create([
            'user_id' => $this->id
        ]);
    }

    /**
     * @param $query
     */
    public function scopeActive($query)
    {
        $query->where('status', '=', 'active');
    }

    #region getUsersByRole

    /**
     * @return object
     */
    public function scopeAdmins($query)
    {

        return $query->whereHas('role', function ($relationQuery) {
            $relationQuery->where('access', Roles::ACCESS_TO_BACKEND)
                ->orWhere('access', Roles::ACCESS_TO_BOTH);
        });
    }
    #endregion getUsersByRole

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function role()
    {
        return $this->belongsTo('Btybug\User\Models\Roles', 'role_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne('Btybug\User\Models\UsersProfile');
    }

    public function usersActivity()
    {
        return $this->hasOne('Btybug\User\Models\UsersActivity');
    }

    public function membership()
    {
        return $this->hasOne(\Btybug\User\Models\Membership::class, 'id', 'membership_id');
    }

    /**
     * @param $slug
     * @return bool|int
     */
    public function getUsersByRole($slug)
    {
        $role = Roles::where('slug', $slug)->first();

        if ($role) {
            return $role->users;
        } else {
            return 404;
        }
    }

    /**
     * @return mixed
     */
    public function getAllUsers()
    {
        return self::where('id', '!=', Auth::id())->get();
    }

    /**
     * @return mixed
     */
    public function getSiteUsers()
    {
        return self::ranking('role', function ($query) {
            $query->where('access', Roles::ACCESS_TO_FRONTEND);
        })->paginate();


    }

    /**
     * @return array
     */
    public function getAuthor()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'url' => $this->url,  // Optional
            'avatar' => 'gravatar',
            'admin' => $this->role === 'admin', // bool
        ];
    }

    /**
     * @param $search
     * @return mixed
     */
    public function findUsers($search)
    {

        return self::where('name', 'like', '%' . $search['name'] . '%')
            ->where('username', 'like', '%' . $search['uname'] . '%')
            ->paginate(self::PER_PAGE);

    }
}

