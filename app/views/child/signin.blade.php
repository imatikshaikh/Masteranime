<div class="span6">
    <h3 class="met_leave_a_reply met_no_margin_top">Sign in</h3>

    <form method="post" class="met_contact_form">
        <div class="met_long_container">
            <input type="text" name="username" required="" class="met_input_text" placeholder="* Username">
        </div>
        <div class="met_long_container">
            <input type="password" name="password" required="" class="met_input_text" placeholder="* Password">
        </div>
        <input type="checkbox" name="remember" value="true"> Remember me.
        <input type="submit" class="met_button pull-right" value="Sign in">
        <div>{{ link_to('account/register', 'Need an account?') }}</div>
    </form>
</div>