<?php
/**
 * Created by PhpStorm.
 * User: Ara Arakelyan
 * Date: 7/20/2017
 * Time: 4:28 PM
 */

namespace Sahakavatar\User\Http\Requests\User;

use Sahakavatar\Cms\Http\Requests\Request;

class DeleteAdminRequest extends Request
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
                'slug' => 'required|exists:users,id'
            ];
        }
        return [];
    }

}