@if (!empty($anime_id) && !empty($anime_name) && !empty($anime_episode) && !empty($anime_img) && !empty($time))
@if (empty($chart))
<a href="{{ URL::to('/watch/anime/' . $anime_id . '/' . str_replace(array(" ", " / ", " ? "), '_', $anime_name) . '/' . $anime_episode) }}">
@else
<a href="{{ URL::to('/anime/' . $anime_id . '/' . str_replace(array(" ", " / ", " ? "), '_', $anime_name)) }}">
@endif
<div class="span3 anime_card" data-toggle="tooltip" title="{{ $anime_name }}">
    @if (empty($chart))
    <div class="top_title">ep. {{ $anime_episode }} - {{ Latest::time_elapsed_string($time) }}</div>
    @else
    <div class="top_title">{{ $time }}</div>
    @endif
    {{ HTML::image($anime_img, $anime_name) }}
    <p>
        @if (strlen($anime_name) > 23)
        {{ substr($anime_name, 0, 23) }}..
        @else
        {{ $anime_name }}
        @endif
    </p>
</div>
</a>
@endif