{!! Form::hidden('perm-list',json_encode($permission_role),['id' => 'permList']) !!}
@foreach($permissions as $parent)
    @if($parent->parent == null)
        <div class="row perm-header">
            <div class="col-md-3 perm-header-name">
                {!! $parent->name !!}
            </div>
            <div class="col-md-9">
                @foreach($roles as $role)
                    @if(!in_array($role->id,\App\Modules\Users\User::$defaultRoles))
                    <div class="checkbox pull-left perm-role">
                        <label>
                            <input type="checkbox" data-current="{!! $parent->id !!}" data-roleid="{!! $role->id !!}"
                                   data-permid="{!! $parent->id !!}" class="show-child-perm"
                                   name="permission[{!!$role->id!!}][{!!$parent->id!!}]"
                                   value='1' {!! (in_array($role->id.'-'.$parent->id,$permission_role)) ? 'checked' : '' !!}> {!! $role->name !!}
                        </label>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>

        @foreach($permissions as $permission)
            @if($parent->id == $permission->parent)
                <div class="row p-5">
                    <div class="col-md-3 text-left f-s-16 f-w-700 p-5 bg-grey-lighter">
                        {!! ucwords($permission->name) !!}
                    </div>
                    <div class="col-md-9">
                        @foreach($roles as $role)
                            @if(!in_array($role->id,\App\Modules\Users\User::$defaultRoles))
                            <div class="checkbox pull-left perm-role">
                                @if(in_array($role->id.'-'.$parent->id,$permission_role))
                                    <label>
                                        <input type="checkbox" data-current="{!! $permission->id !!}"
                                               data-roleid="{!! $role->id !!}" data-permid="{!! $permission->id !!}"
                                               class="show-child-perm"
                                               name="permission[{!!$role->id!!}][{!!$permission->id!!}]"
                                               value='1' {!! (in_array($role->id.'-'.$permission->id,$permission_role)) ? 'checked' : '' !!}> {!! $role->name !!}
                                    </label>
                                @endif
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                @foreach($permissions as $inside)
                    @if($permission->id == $inside->parent)
                        <div class="row p-5">
                            <div class="col-md-2 col-md-offset-1 text-right f-s-16 p-5 border1">
                                {!! ucwords($inside->name) !!}
                            </div>
                            <div class="col-md-9">
                                @foreach($roles as $role)
                                    @if(!in_array($role->id,\App\Modules\Users\User::$defaultRoles))
                                        <div class="checkbox pull-left perm-role">
                                            @if(in_array($role->id.'-'.$permission->id,$permission_role))
                                                <label style="color:white;">
                                                    <input type="checkbox" data-current="{!! $inside->id !!}"
                                                           data-roleid="{!! $role->id !!}"
                                                           data-permid="{!! $permission->id !!}" class="show-child-perm"
                                                           name="permission[{!!$role->id!!}][{!!$inside->id!!}]"
                                                           value='1' {!! (in_array($role->id.'-'.$inside->id,$permission_role)) ? 'checked' : '' !!}> {!! $role->name !!}
                                                </label>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        @endforeach
    @endif
@endforeach
