<?php

namespace Sahakavatar\User\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Caffeinated\Shinobi\Traits\ShinobiTrait;
use Auth;

class PermissionRole extends Model
{
    use ShinobiTrait;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permission_role';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'page_id', 'role_id', 'page_type'];

    /**
     * The attributes which using Carbon.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    public function permission(){
        return $this->hasMany('App\Modules\Modules\Models\AdminPages','id','page_id');
    }

    public function roles(){
        return $this->hasMany('App\Modules\Users\Models\Roles','id','role_id');
    }

    public function role(){
        return $this->belongsTo('App\Modules\Users\Models\Roles','role_id','id');
    }

    public static function optimizePageRoles($page,$data, $pageType = 'back'){
        if($data){
            $roles = explode(',',$data);
            $roleIDs = [];
            foreach($roles as $role) {
                if($r = Roles::where('slug',$role)->first()){
                    $roleIDs[] = $r->id;
                }
            }
            self::optimizeMultyLevelChilds($page->permission_role,$page->childs,$roleIDs);

            $page->permission_role()->whereNotIn('role_id',$roleIDs)->delete();
            if(! empty($roleIDs)){
                foreach($roleIDs as $value){
                    if(! $page->permission_role()->where('role_id',$value)->first()){
                        self::create(['page_id'=>$page->id,'role_id' => $value, 'page_type' => $pageType]);
                    }
                }
            }
        }else{

            self::optimizeMultyLevelChilds($page->permission_role,$page->childs);
            $page->permission_role()->delete();
        }

        return $page->permission_role;
    }

    public static function optimizeMultyLevelChilds($permission_roles,$childPages,$roleIDs = null)
    {
        if(count($permission_roles)){
            foreach($permission_roles as $pr){
                if(count($childPages)){
                    foreach($childPages as $cp){
                        if($cp->permission_role()->where('role_id',$pr->role_id)->first()){
                            $permission_roles = $cp->permission_role;
                            if($roleIDs){
                                $cp->permission_role()->whereNotIn('role_id',$roleIDs)->delete();
                                self::optimizeMultyLevelChilds($permission_roles,$cp->childs,$roleIDs);
                            }else{
                                $cp->permission_role()->where('role_id',$pr->role_id)->delete();
                                self::optimizeMultyLevelChilds($permission_roles,$cp->childs);
                            }
                        }
                    }
                }
            }
        }
    }
}
