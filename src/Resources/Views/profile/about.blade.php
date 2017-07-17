<div>
    <p>
        <strong>Email :</strong> {!! $user->email !!}
    </p>
    <p>
        <strong>Username :</strong> {!! $user->username !!}
    </p>
    @if(isset($user->profile->meta_data) && $user->profile->meta_data)
        @foreach(unserialize($user->profile->meta_data) as $key => $value)
            <p>
                <strong>{{ $key }} :</strong> {!! $value !!}
            </p>
        @endforeach
    @endif
</div>
