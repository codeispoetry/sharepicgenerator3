<form method="post" action="/index.php/frontend/request_password_reset">
    <input type="email" name="username" id="username" placeholder="<?php  echo _('username');?>" value=""><br>
    <input type="submit" name="submit" value="<?php  echo _('request reset link');?>">
</form>