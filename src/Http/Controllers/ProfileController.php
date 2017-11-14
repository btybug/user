<?php

namespace Sahakavatar\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Sahakavatar\User\Http\Requests\Profile\ChangePasswordRequest;
use Sahakavatar\User\Repository\UserRepository;
use Sahakavatar\User\Services\AccountService;

class ProfileController extends Controller
{

    public function getIndex(
        Request $request,
        Guard $auth,
        UserRepository $userRepository
    )
    {
        if ($request->id) {
            $user = $userRepository->find($request->id);
            if (!$user) return redirect()->back();
        } else {
            $user = $auth->user();
        }
        return view('users::profile', compact('user'));
    }

    public function getView(
        Guard $auth
    )
    {
        $user = $auth->user();
        return view('users::profile', compact('user'));
    }

    public function getEditProfile()
    {
        return view('users::profile.edit');
    }

    public function getLoginDetails()
    {
        $model = Auth::user();
        return view('users::profile.edit_login_details', compact(['model']));
    }

    public function changePassword(
        ChangePasswordRequest $request,
        AccountService $accountService
    )
    {
        $requestData = $request->only('email', 'password');
        $accountService->changePassword($requestData);

        return redirect()->back()->with([
            'flash' => [
                'message' => trans('Password has been changed successfully.'),
            ]
        ]);
    }
}
