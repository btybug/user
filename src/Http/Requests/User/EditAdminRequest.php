<?php
/**
 * Created by PhpStorm.
 * User: Ara Arakelyan
 * Date: 7/20/2017
 * Time: 4:28 PM
 */

namespace Btybug\User\Http\Requests\User;

use Btybug\btybug\Http\Requests\Request;

class EditAdminRequest extends Request
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
                'username' => 'required|max:255|unique:users,username,' . $this->id, ',id',
                'email' => 'required|email|max:255|unique:users,email,' . $this->id, ',id',
                'role_id' => 'required|exists:roles,id',
                'membership_id' => 'sometimes|exists:memberships,id',
                'status' => 'required',
                'password' => 'sometimes|nullable|min:6|max:255',
                'password_confirmation' => 'sometimes|nullable|min:6|max:255|same:password',
            ];
        }
        return [];
    }

}