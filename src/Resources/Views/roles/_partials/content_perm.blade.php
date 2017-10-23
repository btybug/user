@if(count($list))
    @foreach($list as $item)
        @if($role->can($item->slug))
            <li><strong>{!! $item->name !!}:</strong> <a data-perm="{!! $item->id !!}" data-role="{!! $role->id !!}"
                                                         data-child="1" href="javascript:void(0);"
                                                         class="btn btn-danger deactivate-item">Deactivate</a></li>
        @else
            <li><strong>{!! $item->name !!}:</strong> <a data-perm="{!! $item->id !!}" data-role="{!! $role->id !!}"
                                                         data-child="1" href="javascript:void(0);"
                                                         class="btn btn-success activate-item">Activate</a></li>
        @endif
    @endforeach
@else
    <li><strong> There are no Permissions </strong></li>
@endif