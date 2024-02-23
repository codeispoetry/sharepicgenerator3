<style>
    form {
        display: flex;
        flex-direction: column;
        justify-content: left;
        align-items: left;
    }
    input {
        margin: 2px;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }
</style>
<form method="post" action="index.php?c=frontend&m=reset_password">
    <input type="hidden" name="token" value="<?php echo $token;?>">
    <input type="password" name="password" id="rp_password" placeholder="<?php  echo _('password');?>" autocomplete="new-password"><br>
    <input type="password" name="password_repeat" id="rp_password_repeat" placeholder="<?php  echo _('password repeat');?>"><br>
    <button type="submit" name="submit"><?php echo $submit_value; ?></button>
</form>

<script>
    var rp_password = document.getElementById("rp_password");
    var rp_password_repeat = document.getElementById("rp_password_repeat");

    function validatePassword() {
        if (rp_password.value != rp_password_repeat.value) {
            rp_password_repeat.setCustomValidity("Passwords Don't Match");
        } else {
            rp_password_repeat.setCustomValidity('');
        }
    }

    rp_password.onchange = validatePassword;
    rp_password_repeat.onkeyup = validatePassword;
</script>