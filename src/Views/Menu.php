<nav>
    <div class="dropdown" id="menu_file">
        <span><?php echo _( 'File' ); ?></span>
        <div class="dropdown-content">
             <a href="index.php" class="menu-link">
                <?php  echo _('Home');?>
			</a>   
            <button data-click="api.save">
                <?php  echo _('Save');?>
            </button>
            <button data-click="api.create">
                <?php  echo _('Download');?>
            </button>

            <div class="divider"></div>
            <button data-lang="de">
                <?php echo _( 'German' ); ?>
            </button>
            <button data-lang="en">
                <?php echo _( 'English' ); ?>
            </button>
            <div class="divider"></div>

            <?php
            if( $this->user->is_admin() ){
                echo '<div class="divider"></div>';
                printf( '<a href="index.php?c=frontend&m=log" class="menu-link">%s</a>', _( 'Logfile' ) );
                printf( '<a href="index.php?c=frontend&m=sharepics" class="menu-link">%s</a>', _( 'Sharepics' ) );
                
                printf( '<button onClick="api.save(\'publish\')">%s</button>', _( 'Publish' ) );
                echo '<div class="divider"></div>';
            }
            ?>

            <div class="divider"></div>
            <a href="index.php?c=frontend&m=view&view=Imprint" class="menu-link">
                <?php  echo _('Imprint');?>
			</a>
            <a href="index.php?c=frontend&m=view&view=Privacy" class="menu-link">
                <?php  echo _('Privacy');?>
			</a>

            <div class="divider"></div>
            <a href="index.php?c=frontend&m=logout" class="menu-link">
                <?php  echo _('Logout');?>
			</a>
        </div>
    </div>

    <div class="dropdown" id="menu_edit">
        <span><?php echo _( 'Edit' ); ?></span>
        <div class="dropdown-content adder">   
           
            <button data-click="undo.undo" id="undo">
                <?php echo _( 'Undo' ); ?>
            </button>
            <div class="divider"></div>

            <button data-item="eyecatcher"><?php  echo _('Add sticker');?></button>
            <button data-item="addpicture"><?php  echo _('Add picture');?></button>
        </div>
    </div>

    <div class="dropdown" id="menu_mysharepics">
        <span><?php echo _( 'My templates' ); ?></span>
        <div class="dropdown-content" id="my-sharepics">
            <?php
                $last = 'users/' . $this->user->get_username() . '/workspace/sharepic.html';
                if (file_exists($last)) {
                    echo '<button data-load="' . $last . '">' . _('latest') . '</button>';
                    echo '<div class="divider"></div>';
                }
               
                if( 'auto' !== $this->user->get_role() ){
                    foreach( $templates as $path => $label ) {
                        printf( '<button data-load="templates/%s/start.html">%s</button>', $path, $label );
                    }
                    echo '<div class="divider"></div>';
                }

                foreach( $published as $dir => $name ){
                     echo '<div class="dropdown-item-double">';
                         printf( '<button class="did-1" data-load="%s">%s</button>', $dir, $name );

                         if( $this->user->is_admin() ){
                             //printf( '<button class="did-2" data-delete="%s"><img src="assets/icons/delete.svg">!!!</button>', $dir);
                         }
                     echo '</div>';
                }
                echo '<div class="divider"></div>';

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