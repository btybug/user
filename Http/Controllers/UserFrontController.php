<?php

namespace App\Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use  App\Modules\Users\Repository\User;

use Illuminate\Http\Request;
use App\Models\Home;
use Auth;
use Carbon\Carbon;


class UserFrontController extends Controller
{

    /**
     * UserController constructor.
     *
     * @param User $user
     */
    protected $notifymodel = null;

    public function __construct(User $user, Home $homemodel)
    {
        $this->user_repo = $user;
        $this->homemodel = $homemodel;
        $this->home = "/my_account/notifications";
    }


    public function myAccount(Request $request)
    {
        $user = $this->user_repo->logeduser();
        $url = $request->path();
        $data = $this->homemodel->renderStuff($url);
        $data['user'] = $user;

        return view("layouts.master", $data);
    }

    public function myAccountEdit(Request $request)
    {
        $url = $request->path();
        $data = $this->homemodel->renderStuff($url);

        return view("layouts.master", $data);
    }

    public function notifications(Request $request)
    {
       /* $userNotified = Auth::user();
        $notifications = $userNotified->getNotifications();
        $content = $this->formatNotifications(
            $notifications->toArray()
        );

        $user = Auth::user();
        $url = $request->path();
        $data = $this->homemodel->renderStuff($url, $content);

        return view("layouts.master", $data);*/
    }

    /**
     *
     */
    public function getMarkReadAll()
    {
        Notification::where("to_id", Auth::user()->id)->update(["read" => "1"]);

        return redirect($this->home);
    }

    public function postMarkRead(Request $request)
    {
        $vals = $request->get('vals');
        $ids = explode(",", $vals);
        foreach ($ids as $id) {
            Notification::where("id", $id)->update(["read" => "1"]);
        }

    }

    public function postNotifyDelete(Request $request)
    {
        $vals = $request->get('vals');
        $ids = explode(",", $vals);
        foreach ($ids as $id) {
            Notification::destroy($id);
        }

    }

    public function formatNotifications($data)
    {
        $r_data = [];
        foreach ($data as $rec) {
            $extra = $rec['extra'];
            $class = '';
            if ($extra) {
                $class = $rec['extra']['class'];
            }

            $r_data[] = [
                'class' => $class,
                'id' => $rec['id'],
                'text' => $rec['text'],
                'time' => Carbon::parse($rec['updated_at'])->format('M d Y, g:i A'),
                'read' => $rec['read']
            ];
        }

        return $r_data;
    }

}
