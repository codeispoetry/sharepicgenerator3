<nav>
    <a href="/"><?php  echo _('Home');?></a>

    <div class="dropdown">
        <span><?php echo _( 'Templates' ); ?></span>
        <div class="dropdown-content">
            <?php
            $last = 'users/' . $this->user->get_username() . '/workspace/sharepic.html';
            if (file_exists($last)) {
                echo '<button data-load="' . $last . '">' . _('latest') . '</button>';
            }
            ?>
            <?php
            /*
            <button data-load="tenants/mint/start.html"><?php  echo _('Mint');?></button>
            <button data-load="tenants/vorort/start.html"><?php  echo _('Vor Ort');?></button>
            */
            ?>
            <?php
                foreach( $this->user->get_tenants() as $tenant ) {
                    printf( '<button data-load="tenants/%s/start.html">%s</button>', $tenant, ucfirst( $tenant ) );
                }
            ?>
        </div>
    </div>

    <div class="dropdown">
        <span><?php echo _( 'My sharepics' ); ?></span>
        <div class="dropdown-content" id="my-sharepics">
            <?php
               $savings = $this->user->get_savings();
               foreach( $savings as $dir => $name ){
                    echo '<div class="dropdown-item-double">';
                        printf( '<button class="did-1" data-load="users/%s/save/%s/sharepic.html">%s</button>', $this->user->get_username(), $dir, $name );
                        printf( '<button class="did-2" data-delete="%s"><img src="assets/icons/delete.svg"></button>', $dir);
                    echo '</div>';
               }
            ?>
        </div>
    </div>

</nav>