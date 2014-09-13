@if (!empty($display) && !empty($anime_id) && !empty($anime_name) && !empty($anime_img))
@if ($display == "latest")
<a href="{{ URL::to('/watch/anime/' . $anime_id . '/' . str_replace(array(" ", "/", "?"), '_', $anime_name) . '/' . $anime_episode) }}">
@elseif ($display == "chart" || $display == "list")
<a href="{{ URL::to('/anime/' . $anime_id . '/' . str_replace(array(" ", "/", "?"), '_', $anime_name)) }}">
@endif
<div class="span3 anime_card" data-toggle="tooltip" title="{{ $anime_name }}">
    @if ($display == "latest")
    <div class="top_title">ep. {{ $anime_episode }} - {{ Latest::time_elapsed_string($time) }}</div>
    @elseif ($display == "chart")
    <div class="top_title">{{ $time }}</div>
    @endif
    @if ($display == "list")
    <img class="lazy" data-original="{{$anime_img}}" alt="{{$anime_name}}">
    @else
    <img src="{{$anime_img}}" alt="{{$anime_name}}">
    @endif
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