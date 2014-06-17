<div class="span6">
    <h3 class="met_leave_a_reply met_no_margin_top">Sign up</h3>

    <form method="post" class="met_contact_form">
        <div class="met_long_container">
            <input type="text" name="username" required class="met_input_text" placeholder="* Username">
        </div>
        <div class="met_long_container">
            <input type="password" name="password" required class="met_input_text" placeholder="* Password">
        </div>
        <div class="met_long_container">
            <input type="email" name="email" required class="met_input_text" id="met_contact_email" placeholder="* E-mail Address">
        </div>
        <input type="submit" class="met_button pull-right" value="Sign up">
        <div>{{ link_to('account', 'Already have an account?') }}</div>
    </form>
</div>