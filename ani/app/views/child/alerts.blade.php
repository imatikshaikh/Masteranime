@if (!empty($msg_type) && $msg_type == 'success')
<div class="alert alert-success alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong>Success!</strong> {{$msg or 'Unknown success'}}
</div>
@else
<div class="alert alert-danger alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong>Warning!</strong> {{$msg or 'Unknown warning'}}
</div>
@endif