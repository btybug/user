<?php
/**
 * Created by PhpStorm.
 * User: Ara Arakelyan
 * Date: 7/20/2017
 * Time: 4:28 PM
 */

namespace Sahakavatar\User\Http\Requests\Role;

use Sahakavatar\Cms\Http\Requests\Request;

class EditRoleRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->isMethod('POST')) {
            return [
                'name' => 'required|max:100',
                'slug' => 'required|max:255|unique:roles,slug,' . $this->id . ",id",
            ];
        }
        return [];
    }

}