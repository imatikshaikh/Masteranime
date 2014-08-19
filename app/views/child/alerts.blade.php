@if (!empty($msg_type) && $msg_type == 'success')
<div class="alert alert-success alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong>Success!</strong> {{$msg or 'Unknown success ATLEAST IT WORKED!'}}
</div>
@elseif (!empty($msg_type) && $msg_type == 'info')
<div class="alert alert-info alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong>Info!</strong> {{$msg or 'Unknown info WUT?'}}
</div>
@else
<div class="alert alert-danger alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong>Warning!</strong> {{$msg or 'Unknown warning WE SUCK'}}
</div>
@endif