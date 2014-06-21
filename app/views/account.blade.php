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
            echo '<div class="span12"><h3 class="met_title_with_childs">ANIME MANAGE PANEL<span class="met_subtitle">SUPERUSER WEBSITE PANEL</span></h3><hr>';
            echo '' .
                Form::open(array('action' => 'add_scrapeurl', 'class' => 'form-inline')) . '
                    <label style="margin-right: 10px;">add scraper suffix</label>' .
                Form::text('anime_id', $value = null, array('class' => 'input-large', 'placeholder' => 'anime id', 'style' => 'margin-right: 10px;')) . '' .
                Form::text('suffix_animerush', $value = null, array('class' => 'input-large', 'placeholder' => 'suffix ar', 'style' => 'margin-right: 10px;')) . '' .
                Form::text('suffix_rawranime', $value = null, array('class' => 'input-large', 'placeholder' => 'suffix ra', 'style' => 'margin-right: 10px;')) . '' .
                Form::text('othername', $value = null, array('class' => 'input-large', 'placeholder' => 'othername', 'style' => 'margin-right: 10px;')) . '' .
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