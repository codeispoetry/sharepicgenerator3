<form method="post" action="index.php?c=frontend&m=create">
    <input type="email" name="username" placeholder="<?php  echo _('username');?>" value="" autocomplete="email"><br>
    <input type="password" name="password" placeholder="<?php  echo _('password');?>" value="" autocomplete="current-password"><br>
    <button type="submit" name="submit"><?php  echo _('login');?></button>
</form>