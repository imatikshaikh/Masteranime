@extends('layout', ['footer' => false])

@section('content')
<div class="row-fluid">
{{ $update_msg or '' }}
@if (isset($sign_in_form))
    <div class="offset4 span7">
        {{ $sign_in_form }}
    </div>
@elseif (isset($register_form))
    <div class="offset4 span7">
        {{ $register_form }}
    </div>
@else
    <?php
    if (Sentry::check()) {
        $user = Sentry::getUser();
        var_dump($user->isSuperUser());
    } else {
        Redirect::to('account');
    }
    ?>
@endif
</div>
@stop