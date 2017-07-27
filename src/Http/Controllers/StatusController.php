<?php
/**
 * Created by PhpStorm.
 * User: Ara Arakelyan
 * Date: 6/13/2017
 * Time: 11:58 AM
 */

namespace Sahakavatar\User\Http\Controllers;


use App\Http\Controllers\Controller;
use Psy\Test\TabCompletion\StaticSample;
use Sahakavatar\User\Http\Requests\Status\CreateStatusRequest;
use Sahakavatar\User\Http\Requests\Status\EditStatusRequest;
use Sahakavatar\User\Repository\StatusRepository;
use Illuminate\Http\Request;

class StatusController extends Controller
{

    public function getIndex(
        StatusRepository $statusRepository
    )
    {
        $statuses = $statusRepository->getAll();
        return view('users::status.index', compact(['statuses']));
    }

    public function getCreate() {
        return view('users::status.create');
    }

    public function postCreate(
        CreateStatusRequest $request,
        StatusRepository $statusRepository
    ) {
        $requestData = $request->except('_token');
        $statusRepository->create($requestData);
        return redirect('/admin/users/statuses')->with('message','Status has been created successfully');
    }

    public function getEdit(
        Request $request,
        StatusRepository $statusRepository
    ){
        $status = $statusRepository->find($request->id);
        if(!$status) {
            abort(404);
        }
        return view('users::status.edit',compact('status'));
    }

    public function postEdit(
        EditStatusRequest $request,
        StatusRepository $statusRepository
    ){
        $status = $statusRepository->find($request->id);
        if(! $status){
            abort(404);
        }
        $requestData = $request->except('_token');
        $statusRepository->update($request->id, $requestData);
        return redirect('/admin/users/statuses')->with('message','Status has been updated successfully');
    }

    public function postDelete(
        Request $request,
        StatusRepository $statusRepository
    ) {
        $status = $statusRepository->findOneByMultiple([
            'id' => $request->slug,
            'is_core' => 0
        ]);
        $success = $status && $statusRepository->delete($status->id) ? true : false;
        return \Response::json(['success' => $success]);
    }

}