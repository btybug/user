<?php

namespace Sahakavatar\User\Models;

use Illuminate\Database\Eloquent\Model;

class UsersProfile extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users_profile';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes which using Carbon.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    public static function EditRules()
    {
        return [
            'first_name' => 'sometimes|max:50',
            'last_name' => 'sometimes|max:100',
            'avatar' => 'image:jpeg,bmp,png|max:' . self::MAX_UPLOAD_FILE_SIZE . '|mimes:jpeg,bmp,png',
            'cover' => 'image:jpeg,bmp,png|max:' . self::MAX_UPLOAD_FILE_SIZE . '|mimes:jpeg,bmp,png',
            'zip' => 'sometimes|regex:/\b\d{5}\b/',
            'phone' => 'sometimes|numeric'
        ];
    }

    public function user()
    {
        return $this->belongsTo('App\Modules\Users\User', 'user_id', 'id');
    }

    public function updateProfile($id, $data, $meta = null)
    {
        $profile = self::where('user_id', $id)->first();

        if (!$profile) return false;
        $profile->update($data);

        if ($meta) {
            $profile->meta_data = serialize($meta);
            $profile->save();
        }

        return $profile;
    }
}
