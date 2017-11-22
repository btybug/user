<?php
/**
 * Created by PhpStorm.
 * User: Ara Arakelyan
 * Date: 7/20/2017
 * Time: 4:28 PM
 */

namespace Btybug\User\Http\Requests\User;

use Btybug\btybug\Http\Requests\Request;

class CreateAdminRequest extends Request
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
                'username' => 'required|max:255|unique:users,username',
                'email' => 'required|email|max:255|unique:users,email',
                'role_id' => 'required|exists:roles,id',
                'status' => 'required',
                'password' => 'required|min:6|max:255',
                'password_confirmation' => 'min:6|max:255|same:password',
            ];
        }
        return [];
    }

}