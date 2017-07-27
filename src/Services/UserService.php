<?php

/**
 * Created by PhpStorm.
 * User: Ara Arakelyan
 * Date: 7/19/2017
 * Time: 3:52 PM
 */

namespace Sahakavatar\User\Services;

use Illuminate\Support\Facades\Auth;
use Sahakavatar\Cms\Services\GeneralService;
use Sahakavatar\User\Models\Roles;
use Sahakavatar\User\Repository\UserRepository;
use Sahakavatar\User\User;

class UserService extends GeneralService
{
    public $uploadPath = '/resources/assets/images/users/';

    private $userRepository;
    private $authUser;

    public function __construct(
        UserRepository $userRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->authUser = Auth::user();
    }

    public function getSiteUsers()
    {
        return $this->userRepository->model()->whereHas('role', function ($query) {
            $query->where('access', Roles::ACCESS_TO_FRONTEND);
        });
    }

    public function getAdmins()
    {
        return $this->userRepository->model()->whereHas('role', function ($relationQuery) {
            $relationQuery->where('access', Roles::ACCESS_TO_BACKEND)
                ->orWhere('access', Roles::ACCESS_TO_BOTH);
        });
    }

    public function getAdmin(int $id)
    {
        return $this->userRepository->model()->whereHas('role', function ($relationQuery) {
            $relationQuery->where('access', Roles::ACCESS_TO_BACKEND)
                ->orWhere('access', Roles::ACCESS_TO_BOTH);
        })->where('id', $id)->first();
    }

    /**
     * @param $id
     * @param string $action
     * @return bool
     */
    public function ranking($id, $action = 'delete')
    {
        $current = $this->userRepository->find($id);

        if (!Auth::user()->isSuperadmin()) {
            if ($current->isSuperadmin())
                return false;

            if ($current->isAdministrator()) {
                if (Auth::user()->isAdministrator() && $action == 'edit') {
                    return true;
                }
                return false;
            }
        }
        return true;
    }

    public function sendPassword(User $user)
    {
        $random = str_random(10);
        $this->userRepository->update($user->id, [
            'password' => bcrypt($random)
        ]);
        $emailData = [
            'template' => 'emails.auth.password',
            'data' => ['username' => $user->username, 'password' => $random],
            'usage' => $user,
            'subject' => 'New password sent to member.'
        ];
        \Event::fire(new sendEmailEvent($this->authUser, $emailData));
    }

    public function resetPassword(string $email)
    {
        if ($user = $this->userRepository->findBy('email', $email)) {
            $emailData = [
                'template' => 'emails.auth.reset',
                'data' => ['username' => $user->username],
                'usage' => $user,
                'subject' => 'Reset Password'
            ];
            \Event::fire(new sendEmailEvent($this->authUser, $emailData));

            return ['data' => true, 'code' => 200, 'error' => false];
        }

        return ['data' => false, 'code' => 500, 'error' => true];
    }

    public function deleteUser(int $id)
    {
        $result = $this->userRepository->find($id);
        if ($result) {
            \File::deleteDirectory(base_path() . $this->uploadPath . $id);
            $result = $this->userRepository->delete($id) ? true : false;
        }
        return $result;
    }

    /**
     * @param string $page
     * @return array
     */
    public function getOptions($model)
    {
        $data = \Config::get('user_options');
        if ($data && !empty($data)) {
            foreach ($data as $key => $plugin) {
                if (isset($plugin) && count($plugin)) {
                    foreach ($plugin as $title => $item) {
                        if (isset($item['html'])) {
                            echo $this->replaceDynamicValuesWithModelAttributes($item['html'], $model);
                        }
                        if (isset($item['view']) && View::exists($item['view'])) {
                            (isset($item['data'])) ? $compact = $item['data'] : $compact = [];
                            echo $this->replaceDynamicValuesWithModelAttributes(view($item['view'])->with($compact)->render(), $model);
                        }
                    }
                }

            }
        }
    }

    private function replaceDynamicValuesWithModelAttributes($html, $model)
    {
        preg_match_all("/\[([^\]]*)\]/", $html, $matches);
        $keys = $matches[1];
        if (count($keys)) {
            foreach ($keys as $key) {
                if (isset($model->{$key})) {
                    $html = str_replace('[' . $key . ']', $model->{$key}, $html);
                }
            }
        }
        return $html;
    }

}