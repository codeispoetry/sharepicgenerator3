<nav>
<div class="dropdown" id="menu_mint">
        <span>MINT Sharepic-Generator</span>
        <div class="dropdown-content"> 
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

    <?php if( isset( $show_my_sharepics ) && $show_my_sharepics === true) { ?>
    <div class="dropdown" id="menu_sharepics">
        <span><?php echo _( 'File' ); ?></span>
        <div id="my-sharepics" class="dropdown-content">
            <button onClick="api.load('templates/' + config.starttemplate + '/start.html')">
                <?php  echo _('New');?>
            </button>

            <button style="display:none" onClick="api.autosave();alert('Ok');">
                <?php  echo _('Save');?>
            </button>
            <button onClick="api.save()">
                <?php  echo _('Save as');?>
            </button>
            <button onClick="api.load('templates/' + config.starttemplate + '/start.html')">
                <?php  echo _('Template');?>
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

    <span class="info-in-menu" id="info-in-menu"></span>
</nav>
