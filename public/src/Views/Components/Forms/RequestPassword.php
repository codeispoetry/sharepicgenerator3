<form method="post" action="index.php?c=frontend&m=request_password_reset">
    <input type="email" name="username" placeholder="<?php  echo _('username');?>" value="" autocomplete="email"><br>
    <button type="submit" name="submit"><?php  echo _('request reset link');?></button>
</form>