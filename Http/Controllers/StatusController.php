<?php
/**
 * Created by PhpStorm.
 * User: Ara Arakelyan
 * Date: 6/13/2017
 * Time: 11:58 AM
 */

namespace App\Modules\Users\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\Users\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{

    public function getIndex()
    {
        $statuses = Status::all();
        return view('users::status.index', compact(['statuses']));
    }

    public function getCreate() {
        return view('users::status.create');
    }

    public function postCreate(Request $request) {
        $data = $request->except('_token');
        $rules = array_merge([
            'name' => 'required|max:100'
        ]);
        $validator = \Validator::make($data, $rules);
        if ($validator->fails()) return redirect()->back()->with('errors',$validator->errors());

        Status::create($data);
        return redirect('/admin/users/statuses')->with('message','Status has been created successfully');
    }

    public function getEdit(Request $request){
        $status = Status::find($request->id);
        if(! $status) {
            abort(404);
        }
        return view('users::status.edit',compact('status'));
    }

    public function postEdit(Request $request){
        $status = Status::find($request->id);

        if(! $status){
            abort(404);
        }

        $data = $request->except('_token');
        $rules = array_merge([
            'name' => 'required|max:100'
        ]);
        $validator = \Validator::make($data, $rules);
        if ($validator->fails()) return redirect()->back()->with('errors',$validator->errors());

        $status->update($data);
        return redirect('/admin/users/statuses')->with('message','Status has been updated successfully');
    }

    public function postDelete(Request $request) {
        $status = Status::where('id', $request->slug)->where('is_core', 0)->first();
        $success = $status && $status->delete() ? true : false;
        return \Response::json(['success' => $success]);
    }

}