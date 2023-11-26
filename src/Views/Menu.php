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
                   printf( '<button data-load="%s/sharepic.html">%s</button>', $dir, $name );
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