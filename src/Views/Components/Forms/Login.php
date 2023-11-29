<form method="post" action="index.php/frontend/create">
    <input type="email" name="username" placeholder="<?php  echo _('username');?>" value="" autocomplete="email"><br>
    <input type="password" name="password" placeholder="<?php  echo _('password');?>" value="" autocomplete="current-password"><br>
    <input type="submit" name="submit" value="<?php  echo _('login');?>">
</form>