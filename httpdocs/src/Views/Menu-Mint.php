<nav>

    <div class="dropdown" id="menu_mint">
        <span><?php echo _( 'MINTSharepic' ); ?></span>
        <div class="dropdown-content">
            <button onClick="ui.setLang('de')">
                <?php echo _( 'German' ); ?>
            </button>
            <button onClick="ui.setLang('en')">
                <?php echo _( 'English' ); ?>
            </button>
            <div class="divider"></div>
            <a href="index.php?c=frontend&m=view&view=Imprint" class="menu-link">
                <?php  echo _('Imprint');?>
			</a>
            <a href="index.php?c=frontend&m=view&view=Privacy" class="menu-link">
                <?php  echo _('Privacy');?>
			</a>

            <?php
            if( $this->user->is_admin() ){
                echo '<div class="divider"></div>';
                printf( '<a href="index.php?c=frontend&m=log" class="menu-link">%s</a>', _( 'Logfile' ) );
                printf( '<a href="index.php?c=frontend&m=sharepics" class="menu-link">%s</a>', _( 'Sharepics' ) );
                
                printf( '<button onClick="api.save(\'publish\')">%s</button>', _( 'Publish' ) );
            }
            ?>

            <div class="divider"></div>
            <a href="index.php?c=frontend&m=logout" class="menu-link">
                <?php  echo _('Logout');?>
			</a>

            <div class="divider"></div>
            <a href="index.php" class="menu-link">
                <?php  echo _('Back to home');?>
			</a>
        </div>
    </div>

    <div class="dropdown" id="menu_file">
        <span><?php echo _( 'File' ); ?></span>
        <div class="dropdown-content">
            <button onClick="ui.reload()">
                <?php  echo _('New template');?>
            </button>
            <button onClick="api.save()">
                <?php  echo _('Save');?>
            </button>
            <button onClick="api.create()">
                <?php  echo _('Download');?>
            </button>
        </div>
    </div>

    <div class="dropdown" id="menu_sharepics">
        <span><?php echo _( 'My Sharepics' ); ?></span>
        <div id="my-sharepics" class="dropdown-content">
            <?php
                // Last
                $last = '../users/' . $this->user->get_username() . '/workspace/sharepic.html';
                if (file_exists($last)) {
                    printf('<button onClick="api.load(\'%s\')">%s</button>', $last, _('latest'));
                    echo '<div class="divider"></div>';
                }
            
                // Published
                foreach( $published as $dir => $name ){
                    echo '<div class="dropdown-item-double">';
                        printf( '<button class="did-1" onClick="api.load(\'%s\')">%s</button>', $dir, $name );
                    echo '</div>';
                }
                echo '<div class="divider"></div>';

                // Saved
                $savings = $this->user->get_savings();
                foreach( $savings as $dir => $name ){
                        echo '<div class="dropdown-item-double">';
                            printf( '<button class="did-1" onClick="api.load(\'users/%s/save/%s/sharepic.html\')">%s</button>', $this->user->get_username(), $dir, $name );
                            printf( '<button class="did-2" onClick="ui.deleteSavedSharepic(this, \'%s\')"><img src="assets/icons/delete.svg"></button>', $dir);
                        echo '</div>';
                }
            ?>
        </div>
    </div>
</nav>