@if(isset($update))
    {!! sc('getCoreForm',["id" =>8,"update" => 'true', 'raw_id' => $raw_id]) !!}
@else
    {!! sc('getCoreForm',["id" =>8]) !!}
@endif

