<nav>
    <a href="index.php"><?php  echo _('Home');?></a>

    <div class="dropdown">
        <span><?php echo _( 'Edit' ); ?></span>
        <div class="dropdown-content">
            <button data-click="ui.undo" id="undo"><?php echo _( 'Undo' ); ?></button>
        </div>
    </div>

    <div class="dropdown">
        <span><?php echo _( 'Templates' ); ?></span>
        <div class="dropdown-content">
            <?php
                $last = 'users/' . $this->user->get_username() . '/workspace/sharepic.html';
                if (file_exists($last)) {
                    echo '<button data-load="' . $last . '">' . _('latest') . '</button>';
                }
          
                foreach( $templates as $path => $label ) {
                    printf( '<button data-load="templates/%s/start.html">%s</button>', $path, $label );
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