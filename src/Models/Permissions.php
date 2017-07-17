<?php

namespace App\Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Caffeinated\Shinobi\Traits\ShinobiTrait;
use Auth;

class Permissions extends Model
{
    use ShinobiTrait;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'name', 'slug', 'parent', 'description'];

    /**
     * The attributes which using Carbon.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    public function role_user(){
        return $this->hasMany('App\Modules\Users\Models\RoleUser','role_id','id');
    }

    public function permission_role(){
        return $this->hasMany('App\Modules\Users\Models\PermmisionRole','permission_id','id');
    }
    public function memberships(){
        return $this->belongsToMany('App\Modules\Users\Models\Membership','membership_permission','permission_id','membership_id');
    }

    public static function getPermissionID($slug){
        $p = self::where('slug',$slug)->first();

        if($p){
            return $p->id;
        }else{
            return false;
        }
    }

    public static function getChilds($perm_id){
        $childs = self::where('parent',$perm_id)->get();
        $data = [];

        foreach($childs as $child){
            $data[] = $child->id;
            $data = @array_merge($data,self::getChilds($child->id));
        }

        return $data;
    }
}
