<nav>
    <a href="/"><?php  echo _('Home');?></a>

    <div class="dropdown">
        <span><?php echo _( 'Load template' ); ?></span>
        <div class="dropdown-content">
            <button data-load="users/<?php echo $this->user->get_username(); ?>/workspace/sharepic.html"><?php  echo _('latest');?></button>
            <button data-load="tenants/free/start.html"><?php  echo _('Free');?></button>

            <?php
                if( $tenant = $this->user->get_tenant() ){
                    printf( '<button data-load="tenants/%s/start.html">%s</button>', $tenant, ucfirst( $tenant ) );
                }
            ?>
        </div>
    </div>

    <div class="dropdown">
        <span><?php echo _( 'Load saved pics' ); ?></span>
        <div class="dropdown-content">
            <?php
               $savings = $this->user->get_savings();
               foreach( $savings as $dir => $name ){
                    echo '<div class="dropdown-item-double">';
                        printf( '<button class="did-1" data-load="users/%s/save/%s/sharepic.html">%s</button>', $this->user->get_username(), $dir, $name );
                        printf( '<button class="did-2" data-delete="%s"><img src="/assets/icons/delete.svg"></button>', $dir);
                    echo '</div>';
               }
            ?>
        </div>
    </div>

    <?php
        if( $this->user->is_admin() ){
            printf( ' <a href="/index.php/frontend/log">%s</a>', _( 'Show logs' ) );
            printf( ' <a href="/index.php/frontend/sharepics">%s</a>', _( 'Show latest sharepics' ) );

        }
    ?>

</nav>