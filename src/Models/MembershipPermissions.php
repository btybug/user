<?php
/**
 * Created by PhpStorm.
 * User: Comp1
 * Date: 12/5/2016
 * Time: 4:25 PM
 */

namespace App\Modules\Users\Models;
use App\Modules\Users\Traits\ShinobiTrait;
use Illuminate\Database\Eloquent\Model;

class MembershipPermissions extends Model
{
    use ShinobiTrait;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'membership_permission';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected   $guarded = ['id'];

    /**
     * The attributes which using Carbon.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    public function permissions(){
        return $this->hasMany('App\Modules\Users\Models\Permissions','id','membership_id');
    }

    public function membership(){
        return $this->belongsTo('App\Modules\Users\Models\Membership','membership_id','id');
    }

    public static function optimizePageMemberships($page,$data){
        if($data){
            $memberships = explode(',',$data);
            $mIDs = [];
            foreach($memberships as $membership) {
                if($r = Membership::where('slug',$membership)->first()){
                    $mIDs[] = $r->id;
                }
            }
            self::optimizeMultyLevelChilds($page->permission_membership,$page->childs,$mIDs);
            $page->permission_membership()->whereNotIn('membership_id',$mIDs)->delete();
            if(! empty($mIDs)){
                foreach($mIDs as $value){
                    if(! $page->permission_membership()->where('membership_id',$value)->first()){
                        self::create(['page_id'=>$page->id,'membership_id' => $value]);
                    }
                }
            }
        }else{

            self::optimizeMultyLevelChilds($page->permission_membership,$page->childs);
            $page->permission_membership()->delete();
        }

        return $page->permission_membership;
    }

    public static function optimizeMultyLevelChilds($permission_membership,$childPages,$mIDs = null)
    {
        if(count($permission_membership)){
            foreach($permission_membership as $pr){
                if(count($childPages)){
                    foreach($childPages as $cp){
                        if($cp->permission_membership()->where('membership_id',$pr->membership_id)->first()){
                            $permission_membership = $cp->permission_membership;
                            if($mIDs){
                                $cp->permission_membership()->whereNotIn('membership_id',$mIDs)->delete();
                                self::optimizeMultyLevelChilds($permission_membership,$cp->childs,$mIDs);
                            }else{
                                $cp->permission_membership()->where('membership_id',$pr->membership_id)->delete();
                                self::optimizeMultyLevelChilds($permission_membership,$cp->childs);
                            }
                        }
                    }
                }
            }
        }
    }
}