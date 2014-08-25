@if (!empty($anime_id) && !empty($anime_name) && !empty($anime_episode) && !empty($anime_img) && !empty($time))
<a href="{{ URL::to('/watch/anime/' . $anime_id . '/' . str_replace(array(" ", " / ", " ? "), '_', $anime_name) . '/' . $anime_episode) }}">
<div class="span3 anime_card" data-toggle="tooltip" title="{{ $anime_name }}">
    <div class="top_title">ep. {{ $anime_episode }} - {{ Latest::time_elapsed_string($time) }}</div>
    {{ HTML::image($anime_img, $anime_name) }}
    <p>
        @if (strlen($anime_name) > 23)
        {{ substr($anime_name, 0, 23) }}
        @else
        {{ $anime_name }}
        @endif
    </p>
</div>
</a>
@endif