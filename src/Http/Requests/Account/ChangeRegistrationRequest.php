<?php

/**
 * Created by PhpStorm.
 * User: Ara Arakelyan
 * Date: 7/19/2017
 * Time: 3:40 PM
 */

namespace Sahakavatar\User\Http\Requests\Account;

use Sahakavatar\Cms\Http\Requests\Request;

class ChangeRegistrationRequest extends Request
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
                'email' => 'required|email|max:255|unique:users,email,' . $this->id . ',id',
                'username' => 'required|max:255|unique:users,username,' . $this->id . ',id',
                'password' => 'required|confirmed|different:current_password|min:6',
                'password_confirmation' => 'required|different:current_password|min:6|same:password'
            ];
        }
        return [];
    }

}