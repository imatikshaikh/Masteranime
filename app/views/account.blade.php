@extends('layout', ['footer' => false])

@section('custom-css')
@parent
<style type="text/css">
    .met_leave_a_reply {
        font-size: 18px;
    }
</style>
@stop
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
            echo '<div class="row-fluid"><div class="span12"><h3 class="met_title_with_childs">ANIME MANAGE PANEL<span class="met_subtitle">SUPERUSER WEBSITE PANEL</span></h3><hr>';
            echo '' .
                Form::open(array('action' => 'add_scrapeurl', 'class' => 'form-inline')) . '
                    <label style="margin-right: 10px;">add scraper suffix</label>' .
                Form::text('anime_id', $value = null, array('class' => 'input-large', 'placeholder' => 'anime id', 'style' => 'margin-right: 10px;')) . '' .
                Form::text('suffix_animerush', $value = null, array('class' => 'input-large', 'placeholder' => 'suffix ar', 'style' => 'margin-right: 10px;')) . '' .
                Form::text('suffix_rawranime', $value = null, array('class' => 'input-large', 'placeholder' => 'suffix ra', 'style' => 'margin-right: 10px;')) . '' .
                Form::text('othername', $value = null, array('class' => 'input-large', 'placeholder' => 'othername', 'style' => 'margin-right: 10px;')) . '' .
                Form::submit('update', array('class' => 'btn btn-success btn-lg', 'style' => 'margin-right: 10px;')) . '' .
                Form::close() . '</div>';
            echo '<div class="met_splitter"></div><div class="btn-group">
                    <a href="' . URL::to('anime/update/thumbnails') . '" type="button" data-toggle="tooltip" title="update thumbnails" class="met_button border-radius-right border-radius-left"><span class="icon-picture"></span></a>
                </div></div>';
        }
        echo '<div class="row-fluid"><div class="span12"><h3 class="met_title_with_childs">ANIMELIST ACCOUNTS<span class="met_subtitle">MYANIMELIST/HUMMINGBIRD</span></h3><hr></div>
<p>If you connect with myanimelist or hummingbird all anime you view will be auto added to your list, so you have don\'t have to worry about managing your list.</p>
                <div class="row-fluid">
                    <div class="span5">
    <h3 class="met_leave_a_reply met_no_margin_top">Connect <a target="_blank" href="http://myanimelist.net/">myanimelist.net</a> account</h3>

    <form id="connect-mal" class="met_contact_form">';
        echo '<div id="loading" style="text-align: center; margin-bottom: 10px;">';
        if ($user->mal_username != null) {
            echo '<div class="alert alert-success alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong>Success!</strong> Connected to MAL as ' . $user->mal_username . '
</div>';
        }
        echo '</div>';
        echo '<div class="met_long_container">
            <input type="text" name="username" required="" class="met_input_text" placeholder="* Username">
        </div>
        <div class="met_long_container">
            <input type="password" name="password" required="" class="met_input_text" placeholder="* Password">
        </div>
        <input type="submit" class="met_button" value="Connect with MAL">
    </form>
</div>
                    <div class="offset2 span5">
    <h3 class="met_leave_a_reply met_no_margin_top">Connect <a target="_blank" href="http://hummingbird.me/">hummingbird.me</a> account</h3>

    <form id="connect-hum" class="met_contact_form">';
        echo '<div id="loading" style="text-align: center; margin-bottom: 10px;">';
        if ($user->hum_username != null) {
            echo '<div class="alert alert-success alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong>Success!</strong> Connected to hummingbird as ' . $user->hum_username . '
</div>';
        }
        echo '</div>';
        echo '<div class="met_long_container">
            <input type="text" name="username" required="" class="met_input_text" placeholder="* Username">
        </div>
        <div class="met_long_container">
            <input type="password" name="password" required="" class="met_input_text" placeholder="* Password">
        </div>
        <input type="submit" class="met_button" value="Connect with hummingbird">
    </form>
</div>
                </div>
        </div>';

        echo '<script type="text/javascript">
            function connect(site, username, password, div) {
                    if (username.length > 0 && password.length > 0 && username !== " " && password !== " ") {
                        $(div + " #loading").empty().append(\'' . HTML::image('img/ajax-loader.gif', 'loading...') . '\');
                        $.ajax({
                            type: "POST",
                            url: "anime/managelistaccount",
                            data: { site: site, username: username, password: password },
                            timeout: 2000,
                            success: function (data) {
                                $(div + " #loading").empty().append(data);
                            },
                            error: function(data) {
                                $(div + " #loading").empty().append("Unknown error.");
                            }
                        });
                    }
            }
            $(document).ready(function() {
                $("#connect-mal input.met_button").click(function(e) {
                    e.preventDefault();
                    var username = $(\'#connect-mal input[name="username"]\').val();
                    var password = $(\'#connect-mal input[name="password"]\').val();
                    connect("myanimelist", username, password, "#connect-mal");
                });
                $("#connect-hum input.met_button").click(function(e) {
                    e.preventDefault();
                    var username = $(\'#connect-hum input[name="username"]\').val();
                    var password = $(\'#connect-hum input[name="password"]\').val();
                    connect("hummingbird", username, password, "#connect-hum");
                });
            });
        </script>';

    } else {
        Redirect::to('account');
    }
    ?>
    @endif
</div>
@stop