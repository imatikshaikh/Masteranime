@extends('layout', ["footer" => false, "title" => "Donate", "description" => "Donate and keep the Masterani servers running!"])

@section('content')
<div class="row-fluid">
    <div class="span6">
        <h3>Help us grow by donating!</h3>

        <p>
            We want to keep improving masterani.me and become the best anime streaming service available but this might bring a few extra costs such as server upgrades, licsense costs,...
            <br>
            If you want to help us improve the services we offer you can always donate with Paypal or BTC every little cent helps us!
        </p>
    </div>
    <div class="span6 center-text">
        <h1>Paypal:</h1>

        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="hosted_button_id" value="QLHDYHXSN7ZZW">
            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
            <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
        </form>
        <h1>Bitcoin:</h1>

        <p>1Fd1HRKL1FixcE5u2xPWfuUWBnxfTgikuH</p>
    </div>
</div>
@stop
