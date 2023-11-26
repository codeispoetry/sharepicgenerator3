<nav>
    <a href="/"><?php  echo _('Home');?></a>

    <div class="dropdown">
        <span><?php echo _('Load template'); ?></span>
        <div class="dropdown-content">
            <button data-load="users/<?php echo $this->user->get_username(); ?>/workspace/sharepic.html"><?php  echo _('latest');?></button>
            <button data-load="tenants/einigungshilfe/start.html">Einigungshilfe</button>
            <button data-load="tenants/free/start.html"><?php  echo _('Free');?></button>
        </div>
    </div>

</nav>