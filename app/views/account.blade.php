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
        if ($user->isSuperUser()) {
            echo '<div class="center-text">' .
                Form::open(array('action' => 'add_scrapeurl', 'class' => 'form-inline')) . '
                    <label style="margin-right: 10px;">anime_id, suffix_animerush, suffix_rawranime & othername</label>' .
                Form::text('anime_id', $value = null, array('class' => 'input-small', 'style' => 'margin-right: 10px;')) . '' .
                Form::text('suffix_animerush', $value = null, array('class' => 'input-large', 'style' => 'margin-right: 10px;')) . '' .
                Form::text('suffix_rawranime', $value = null, array('class' => 'input-large', 'style' => 'margin-right: 10px;')) . '' .
                Form::text('othername', $value = null, array('class' => 'input-large', 'style' => 'margin-right: 10px;')) . '' .
                Form::submit('update', array('class' => 'btn btn-success btn-lg', 'style' => 'margin-right: 10px;')) . '' .
                Form::close() . '</div>';
        }
    } else {
        Redirect::to('account');
    }
    ?>
@endif
</div>
@stop