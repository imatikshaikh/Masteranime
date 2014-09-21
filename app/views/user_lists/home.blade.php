@extends('layout', ["title" => "Anime lists", "description" => "All user-made anime lists compiled in one library so you can find new awesome anime!"])

@section('custom-css')
@parent
<style type="text/css">
    .row-fluid {
        margin-bottom: 30px;
    }

    .mod_buttons {
        float: right;
        margin: 8px 0;
    }

    .mod_buttons > a {
        margin-bottom: 20px;
    }

    .mod_buttons > a:hover {
        text-decoration: none;
    }

    .user_list {
        margin: 0;
        border-bottom: 1px solid #494b4b;
    }

    .user_list > div > .title {
        margin-top: 15px;
        font-size: 22px;
    }

    .user_list > div > .title > .anime_amount {
        font-size: 14px;
    }

    .user_list > div > .title > .anime_amount:hover {
        text-decoration: none;
        font-size: 14px;
        color: #595959;
    }

    .user_list > div > .title > span {
        margin-right: 5px;
        font-size: 14px;
    }

    .user_list > div > .description {
        margin-top: 10px;
        font-size: 12px;
    }

    .user_list > .covers {
        float: right;
    }

    .user_list > div > .cover {
        float: inherit;
        max-width: 58px;
        max-height: 87px;
        margin: 5px;
    }

    @media (max-width: 767px) {
        .search_form > .met_button {
            width: 30%;
        }

        .search_form > #search {
            width: 70%;
        }
    }

    @media (min-width: 767px) {
        .search_form > .met_button {
            width: 10%;
        }

        .search_form > #search {
            width: 90%;
        }
    }
</style>
@stop
@section('content')
@if (isset($update_msg))
<div class="row-fluid">
    <div class="span12">
        {{ $update_msg }}
    </div>
</div>
@endif
<div class="row-fluid">
    <div class="span12">
        <div class="clearfix" style="margin-bottom: 15px;">
            <h3 class="met_title_with_childs pull-left">USER ANIME LISTS
                <span class="met_subtitle">LISTS TO HELP YOU FIND NEW ANIMES</span>
            </h3>

            <div class="mod_buttons">
                <a href="{{{ URL::to('/lists/create/new') }}}" type="button" class="met_button border-radius-right border-radius-left">create new list</a>
            </div>
        </div>
        <form class="search_form met_contact_form" method="get" action="{{ URL::to('/lists/search') }}">
            <input id="search" name="query" autocomplete="off" type="text" size="50" class="met_input_text" placeholder="search for a list (min. 3 chars)"><input class="met_button" type="submit">
        </form>
    </div>
</div>
<div class="row-fluid">
    @if (isset($search))
    @if (!empty($search))
    <h1>Search results:</h1>
    <hr>
    @else
    <h1>Did not find any search results!</h1>
    <hr>
    @endif
    @endif
    <?php $lists = isset($search) && !empty($search) ? $search : DB::table('user_lists')->orderby('updated_at', 'DESC')->paginate(10); ?>
    @if (!empty($lists))
    @foreach($lists as $list)
    <?php
    $anime_ids = explode(",", $list->anime_ids);
    $first_8 = array_slice($anime_ids, 0, 8);
    ?>
    <div class="row-fluid user_list">
        <div class="span6">
            <div class="title">
                <a href="{{ URL::to('/lists/' . $list->id . '/' . str_replace(array(" ", "/", "?"), '_', $list->title)) }}">{{{ $list->title }}}</a>
                <?php
                try {
                    $user = Sentry::findUserById($list->user_id);
                    echo '<span>by ' . $user->username . '</span>';
                } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
                    echo '<span>by Unknown</span>';
                }
                ?>
                <a class="anime_amount" href="{{ URL::to('/lists/' . $list->id . '/' . str_replace(array(" ", "/", "?"), '_', $list->title)) }}"><i class="icon-th-list icon-white"> {{{ count($anime_ids) }}}</i></a>
            </div>
            <p class="description">{{{ $list->description }}}</p>
        </div>
        <div class="span6 covers">
            @foreach ($first_8 as $anime_id)
            <?php $anime = Anime::findOrFail($anime_id, array('id', 'name', 'cover', 'mal_image')); ?>
            <div class="cover">
                <a data-toggle="tooltip" title="{{{ $anime->name }}}" href="{{ URL::to('/anime/' . $anime->id . '/' . str_replace(array(" ", "/", "?"), '_', $anime->name)) }}">
                <img src="{{ Anime::getCover($anime) }}">
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
    <div class="row-fluid">
        <div class="span12">
            {{ $lists->links(); }}
        </div>
    </div>
    @endif
</div>
@stop