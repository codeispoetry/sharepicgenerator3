<form method="post" action="/index.php/frontend/reset_password">
    <input type="hidden" name="token" value="<?php echo $token;?>">
    <input type="password" name="password" id="password" placeholder="<?php  echo _('password');?>">
    <input type="password" name="password_repeat" id="password_repeat" placeholder="<?php  echo _('password repeat');?>">
    
    <input type="submit" name="submit" value="<?php  echo _('reset password');?>">
</form>