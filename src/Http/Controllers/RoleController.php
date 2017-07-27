<?php
/**
 * Copyright (c) 2016.
 * *
 *  * Created by PhpStorm.
 *  * User: Edo
 *  * Date: 10/3/2016
 *  * Time: 10:44 PM
 *
 */

namespace Sahakavatar\User\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Sahakavatar\Cms\Helpers\helpers;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Users\Http\Requests\RoleRequest;
use App\Modules\Users\Models\Roles;
use App\Modules\Users\User;
use DB,File,Config,Response,Entrust;

/**
 * Class RoleController
 * @package App\Modules\Users\Http\Controllers
 */
class RoleController extends Controller
{
	/**
	 * RoleController constructor.
	 * @param Guard $auth
	 * @param User $user
     */
	public function __construct(Guard $auth, User $user) {
		$this->auth = $auth;
		$this->user = $user;
		$this->middleware('auth');
	}

	/**
	 * @param RoleRequest $request
	 * @return \Illuminate\Http\RedirectResponse
     */
	public function store(RoleRequest $request){
		if($request->get('id')){
            $id = $request->get('id');
            if($id == User::ROLE_SUPERADMIN || $id == User::ROLE_USER)
                return redirect('/admin/users/roles-configuration')->with(['flash' => [
                        'message' => 'Role can\'t be updated',
                        'class' => 'alert-danger']]);

			$role = Roles::find($id);
		}else{
			$role = new Roles();
		}

		$data = $request->all();
		$role->fill($data);
		$role->save();

		return redirect('/admin/users/roles-configuration')->with([
			'flash' => [
				'message' => 'Role successfully added',
				'class' => 'alert-success'
			]
		]);
	}

	/**
	 * @param RoleRequest $request
	 * @return \Illuminate\Http\RedirectResponse
     */
	public function destroy(RoleRequest $request){

		if(in_array($request->id,User::$defaultRoles)){
			return redirect()->back()->with([
				'flash' => [
					'message' => 'Role can not be removed',
					'class' => 'alert-danger'
				]
			]);
		}

		$role = Roles::find($request->id);
		$role->delete();
		return redirect()->back()->with([
			'flash' => [
				'message' => 'Role successfully removed',
				'class' => 'alert-success'
			]
		]);
	}

	/**
	 * @param $id
	 * @param RoleRequest $request
     */
	public function inlineUpdate($id, RoleRequest $request) {
         
		$role = new Roles();
		$user = $role->find($id);
	    
		$column_name = $request->get('name');
		  if($column_name=="name") {
			  $user->name = $request->get('value');    	
			}
		  if($column_name=="slug") {
			  $user->slug = $request->get('value');    	
			}
		
	    $user->save();
		
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
     */
	public function postShowEdit(Request $request){
		$id = $request->get('id',null);

		if($id){
			$html = \View::make('users::roles.forms._new_role_form')->with('update',true)->with('raw_id',$id)->render();
		}else{
			$html = \View::make('users::roles.forms._new_role_form')->render();
		}


		return Response::json(['data' => $html]);
	}

}
