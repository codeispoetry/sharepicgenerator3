<nav>
<a href="index.php">
    <img src="/assets/images/mint.svg" style="height: 56px; width: 56px; margin-right: 0;">
</a>
<div class="dropdown" id="menu_mint">
        <span>
            MINT-Sharepic Generator
            <span class="hint">
                <?php echo _('Beta');?>
        </span>
        <div class="dropdown-content"> 
            <a href="https://community.mint-vernetzt.de/help#sharepic-generator" class="menu-link extern">
                <?php  echo _('Help and FAQ');?>
			</a>
            <a href="https://github.com/codeispoetry/sharepicgenerator3" class="menu-link extern">
                <?php  echo _('Sourcecode');?>
			</a>
            <a href="https://www.mint-vernetzt.de/privacy-policy-community-platform/" target="_blank" class="menu-link extern">
                <?php  echo _('Privacy');?>
			</a>
            <a href="index.php?c=frontend&m=view&view=ImprintMint" class="menu-link">
                <?php  echo _('Imprint');?>
			</a>
        </div>
    </div>

    <?php if( isset( $show_my_sharepics ) && $show_my_sharepics === true) { ?>
    <div class="dropdown" id="menu_sharepics">
        <span><?php echo _( 'File' ); ?></span>
        <div class="dropdown-content">
            <button onClick="api.load('templates/' + config.starttemplate + '/start.html')">
                <?php  echo _('New');?>
            </button>
            <button onClick="api.save()">
                <?php  echo _('Save as');?>
            </button>

            <div class="submenu">
                <button>
                    <?php  echo _('Open saved sharepics');?>
                </button>
                <div id="my-sharepics" class="submenu-content">
                    <?php
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
        </div>
    </div>
    <?php } ?>

    <span class="info-in-menu" id="info-in-menu"></span>
</nav>
