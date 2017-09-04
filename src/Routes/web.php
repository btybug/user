<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your module. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

//    Route::get('/social/login/redirect/{provider}', ['uses' => 'Auth\AuthController@redirectToProvider', 'as' => 'social.login'])->where('provider', 'facebook|twitter|google|github');
//    Route::get('/social/login/{provider}', 'Auth\AuthController@handleProviderCallback')->where('provider', 'facebook|twitter|google|github');
//    Route::post('profile/update', array('as' => 'profile.update', 'uses' => 'ProfileController@postSaveProfile'));
//    Route::post('profile/changePassword', array('as' => 'profile.changePassword', 'uses' => 'ProfileController@changePassword'));
//    Route::post('account/changeRegistration', array('as' => 'account.changeRegistration', 'uses' => 'AccountController@changeRegistration'))->middleware(['admin:Users']);

//        Route::group(['prefix' => '/profile'], function () {
//            Route::get('/edit', 'ProfileController@getEditProfile');
//            Route::get('/view', 'ProfileController@getView');
//            Route::get('/login-details', 'ProfileController@getLoginDetails');
//            Route::post('/save-profile', 'ProfileController@postEditProfile');
//            Route::post('/login-details', 'ProfileController@postLoginDetails');
//            Route::get('/{id?}', 'ProfileController@getIndex');
//        });
//        Route::get('account', 'AccountController@getIndex');
//            Route::controller('account/notifications', 'NotificationsController');

        // Route::controller('/profile/{id?}', 'ProfileController');
//        Route::get('/fake', function () {
//            return view('fake');
//        });
//        Route::post('/fake', function (\Illuminate\Http\Request $request) {
//            $helper = new \Sahakavatar\Cms\Helpers\helpers();
//            $count = $request->get('count_users');
//
//            if ($count) {
//                BBGenerateFakeUsers($count);
//                $helper->updatesession('Fake users are generated, count : ' . $count);
//
//                return redirect()->back();
//            }
//
//            $helper->updatesession('Count is Required !!! ', 'alert-danger');
//
//            return redirect()->back();
//        });

        //admins

        //site users
        Route::get('/', array('as' => 'admin.users.list', 'uses' => 'UserController@getIndex'));
        Route::get('/create', array('as' => 'admin.users.getCreate', 'uses' => 'UserController@getCreate'));
        Route::post('/create', array('as' => 'admin.users.postCreate', 'uses' => 'UserController@postCreate'));
        Route::get('/edit/{id}', array('as' => 'admin.users.getEdit', 'uses' => 'UserController@getEdit'));
        Route::post('/edit/{id}', array('as' => 'admin.users.postEdit', 'uses' => 'UserController@postEdit'));
        Route::post('/delete', array('as' => 'admin.users.delete', 'uses' => 'UserController@postDelete'));
        Route::get('/show/{id}', array('as' => 'admin.users.show', 'uses' => 'UserController@getShow'));
        Route::get('/settings', array('as' => 'admin.users.settings', 'uses' => 'UserController@getSettings'));
        Route::post('/settings', array('as' => 'admin.users.postSettings', 'uses' => 'UserController@postSettings'));
        Route::get('/profile', 'UserController@getProfile');
        Route::get('/registration', 'UserController@getRegistration');

        Route::group(['prefix' => '/admins'], function () {
            Route::get('/', 'UserController@getAdmins');
            Route::get('/create', 'UserController@getCreateAdmin');
            Route::post('/create', 'UserController@postCreateAdmin');
            Route::get('/edit/{id}', 'UserController@getEditAdmin');
            Route::post('/edit/{id}', 'UserController@postEditAdmin');
            Route::post('/delete', 'UserController@postDeleteAdmin');
        });


//            Route::get('/site-user', 'UserController@getSiteUsers')->middleware('userHasPerm:users.site_users');
//        Route::get('/send-password/{id}', array('as' => 'admin.users.sendPassword', 'uses' => 'UserController@sendPassword'));
//        Route::any('/edit-users/{id}', array('as' => 'admin.users.editSiteUsers', 'uses' => 'UserController@getEditUsers'));
//        Route::post('/editMemberPass/{id}', array('as' => 'admin.users.editMemberPass', 'uses' => 'UserController@editMemberPass'));
//        Route::any('/deleteMember/{id}', array('as' => 'admin.users.deleteMember', 'uses' => 'UserController@deleteMember'))->middleware('userHasPerm:users.site_users.delete');
//        Route::any('/showMember/{id}', array('as' => 'admin.users.showMember', 'uses' => 'UserController@showMember'))->middleware('userHasPerm:users.site_users.view');
//        //admin members
//        Route::any('/edit-admins/{id}', array('as' => 'admin.users.editAdmins', 'uses' => 'UserController@editAdmins'))->middleware('userHasPerm:users.admins.edit');
//        Route::any('/deleteAdmin/{id}', array('as' => 'admin.users.deleteAdmin', 'uses' => 'UserController@deleteMember'))->middleware('userHasPerm:users.admins.delete');
//        Route::any('/showAdmin/{id}', array('as' => 'admin.users.showAdmin', 'uses' => 'UserController@showAdmin'))->middleware('userHasPerm:users.admins.view');
//        Route::post('/saveMeta/{id}', array('as' => 'admin.users.saveMeta', 'uses' => 'UserController@saveUserMeta'));


        //memberships
        Route::group(['prefix' => '/memberships'], function () {
            Route::get('/', array('as' => 'admin.users.membership.list', 'uses' => 'MembershipController@getIndex'));
            Route::get('/create', array('as' => 'admin.users.membership.getCreate', 'uses' => 'MembershipController@getCreate'));
            Route::post('/create', array('as' => 'admin.users.membership.postCreate', 'uses' => 'MembershipController@postCreate'));
            Route::get('/edit/{slug}', array('as' => 'admin.users.membership.getEdit', 'uses' => 'MembershipController@getEdit'));
            Route::get('/permissions/{slug}', array('as' => 'admin.users.membership.getPermissions', 'uses' => 'MembershipController@getPermissions'));
            Route::post('/permissions/{slug}', array('as' => 'admin.users.membership.postPermissions', 'uses' => 'MembershipController@postPermissions'));
            Route::post('/edit/{slug}', array('as' => 'admin.users.membership.postEdit', 'uses' => 'MembershipController@postEdit'));
            Route::post('/delete', array('as' => 'admin.users.membership.delete', 'uses' => 'MembershipController@postDelete'));
        });

        //roles
        Route::group(['prefix' => '/roles'], function () {
            Route::get('/', 'RolesController@getIndex');
            Route::get('/create', 'RolesController@getCreate');
            Route::post('/create', 'RolesController@postCreate');
            Route::get('/edit/{id}', 'RolesController@getEdit');
            Route::get('/permissions/{slug}', 'RolesController@getPermissions');
            Route::post('/permissions/{slug}', 'RolesController@postPermissions');
            Route::post('/edit/{id}', 'RolesController@postEdit');
            Route::post('/delete', 'RolesController@postDelete');
        });

        //statuses
        Route::group(['prefix' => '/statuses'], function () {
            Route::get('/', 'StatusController@getIndex');
            Route::get('/create', 'StatusController@getCreate');
            Route::post('/create', 'StatusController@postCreate');
            Route::get('/edit/{id}', 'StatusController@getEdit');
            Route::post('/edit/{id}', 'StatusController@postEdit');
            Route::post('/delete', 'StatusController@postDelete');
        });


        //conditions
        Route::group(['prefix' => '/conditions'], function () {
            Route::get('/', 'ConditionController@getIndex');
        });