@extends('layout')

@section('content')
<div class="row-fluid">
    <div class="span12">
        <?php
        if (!empty($debug)) {
            var_dump($debug);
        } else {
            echo "<p>Debug is empty</p>";
        }
        ?>
    </div>
</div>
@stop