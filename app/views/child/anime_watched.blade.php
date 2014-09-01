@if (!empty($series) && count($series) > 0)
<ul class="last-watched">
    @foreach ($series as $watched)
    <?php
    $anime = Anime::findOrFail($watched->anime_id, array('id', 'name'));
    $next = MasterAnime::getNextEpisode($anime->id, $watched->last_watched_episode);
    ?>
    <li id="{{$anime->id}}" class="item{{{ $next ? '' : ' finished'}}}">
        <p>{{{ $anime->name }}} - ep. {{{ $watched->last_watched_episode}}}</p>
        <p class="time hidden-phone">seen {{{ Latest::time_elapsed_string($watched->last_watched_time) }}} ago</p>
        <ul class="nav nav-pills">
            <li><a data-toggle="tooltip" title="index eps" class="met_button" href="{{{ URL::to('/anime/' . $anime->id . '/' . str_replace(array(" ", "/", "?"), '_', $anime->name)) }}}">-</a></li>
            @if ($next)
            <li><a data-toggle="tooltip" title="next ep" class="met_button" href="{{{ URL::to('/watch/anime/' . $anime->id . '/' . str_replace(array(" ", "/", "?"), '_', $anime->name) . '/' . $next) }}}">></a></li>
            @endif
            <li class="space"><a id="drop-anime" data-toggle="tooltip" title="drop anime" class="met_button" href="#"><input name="anime_id" type="hidden" value="{{{$anime->id}}}">x</a></li>
        </ul>
    </li>
    @endforeach
</ul>
<script type="text/javascript">
    $(document).ready(function() {
        $('li[class=space] a[id=drop-anime]').on('click', function(e) {
            e.preventDefault()
            if (confirm('Do you wish to DROP this anime?')) {
                var anime_id = $(this).find("input[name='anime_id']").val();
                $.ajax({
                    type: "POST",
                    url: "{{ URL::to('/userlib/drop') }}",
                    data: {  anime_id: anime_id },
                    timeout: 10000
                }).done(function(data){
                    console.log("Dropped anime: " + anime_id);
                    $("ul li[id='"+anime_id+"']").hide();
                });
            }
        });
    });
</script>
<div class="row-fluid">
    <div class="span12">{{ $series->links(); }}</div>
</div>
@else
<p>
    You haven't seen any anime yet. (Anime will be added to the list after being on the video page for 7mins)
</p>
@endif