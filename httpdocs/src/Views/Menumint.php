<nav>
    <div class="dropdown" id="menu_mint">
        <span><?php echo _( 'File' ); ?></span>
        <div class="dropdown-content">
           
            <button onClick="ui.setLang('de')">
                <?php echo _( 'German' ); ?>
            </button>
            <button onClick="ui.setLang('en')">
                <?php echo _( 'English' ); ?>
            </button>
            <div class="divider"></div>
            <a href="index.php?c=frontend&m=create" class="menu-link">
                <?php  echo _('Create');?>
			</a>
            <a href="index.php?c=frontend&m=view&view=Imprint" class="menu-link">
                <?php  echo _('Imprint');?>
			</a>
            <a href="index.php?c=frontend&m=view&view=Privacy" class="menu-link">
                <?php  echo _('Privacy');?>
			</a>    
            <a href="index.php?c=frontend&m=logout" class="menu-link">
                <?php  echo _('Logout');?>
			</a>
        </div>
    </div>

    <div class="dropdown" id="menu_de">
        <span><?php echo _( 'Sharepic' ); ?></span>
        <div class="dropdown-content">
            <button onClick="api.load('templates/mint/start.html');">
                <?php  echo _('Create Sharepic');?>
            </button> 
        </div>
    </div>

    <?php if( isset( $show_my_sharepics ) && $show_my_sharepics === true) { ?>
    <div class="dropdown" id="menu_sharepics">
        <span><?php echo _( 'My Sharepics' ); ?></span>
        <div id="my-sharepics" class="dropdown-content">
            <button onClick="api.save()">
                <?php  echo _('Save');?>
            </button>
            <div class="divider"></div>
            <?php
                // Saved
                $savings = $this->env->user->get_savings();
                foreach( $savings as $dir => $name ){
                        echo '<div class="dropdown-item-double">';
                            printf( '<button class="did-1" onClick="api.load(\'save/%s/sharepic.html\')">%s</button>', $dir, $name );
                            printf( '<button class="did-2" onClick="ui.deleteSavedSharepic(this, \'%s\')" title="%s"><img src="assets/icons/delete.svg"></button>', $dir, _( 'delete' ) );
                        echo '</div>';
                }
            ?>
        </div>
    </div>
    <?php } ?>

    <span class="info-in-menu"><?php echo _( 'Time to logout:'); ?> <span id="time-to-logout">30:00</span><?php echo _('minutes'); ?></span>
</nav>