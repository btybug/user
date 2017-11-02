<?php
/**
 * Created by PhpStorm.
 * User: Ara Arakelyan
 * Date: 7/20/2017
 * Time: 4:28 PM
 */

namespace Btybug\User\Http\Requests\Profile;

use Illuminate\Support\Facades\Auth;
use Sahakavatar\Cms\Http\Requests\Request;

class ChangePasswordRequest extends Request
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
                'email' => 'required|email|max:255|unique:users',
                'current_password' => 'required|min:6',
                'password' => 'required|confirmed|different:current_password|min:6',
                'password_confirmation' => 'required|different:current_password|min:6|same:password'
            ];
        }
        return [];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!Hash::check($this->input('current_password'), Auth::user()->password)) {
                $validator->errors()->add('current_password', 'Current password is incorrect!');
            }
        });
    }

}