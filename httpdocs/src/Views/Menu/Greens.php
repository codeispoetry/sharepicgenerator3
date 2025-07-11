<nav>
<a href="index.php">
    <img src="/assets/images/sunflower.svg" style="height: 56px; width: 56px; margin-right: 0;">
</a>
    <div class="dropdown" id="menu_mint">
        <span>Sharepicgenerator</span>
        <div class="dropdown-content">
            <a href="https://chatbegruenung.de/channel/sharepicgenerator" target="_blank" class="menu-link extern">
                <?php  echo _('Support');?>
			</a>
            <a href="index.php?c=frontend&m=view&view=Imprint" class="menu-link">
                <?php  echo _('Imprint');?>
			</a>
            <a href="index.php?c=frontend&m=view&view=Privacy" target="_blank" class="menu-link">
                <?php  echo _('Privacy');?>
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
            <button onClick="document.getElementById('upload').click()">
                <?php  echo _('Upload');?>
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
                                    printf( '<button class="did-1" onClick="api.load(\'save/%1$s/sharepic.html\')"><div class="description">%2$s</div><div class="thumb" style="background-image:url(index.php?c=proxy&r=%3$d&p=save/%1$s/thumbnail.png)"></div></button>', $dir, $name, rand() );
                                    printf( '<button class="did-2" onClick="ui.deleteSavedSharepic(this, \'%s\')" title="%s"><img src="assets/icons/delete.svg"></button>', $dir, _( 'delete' ) );
                                echo '</div>';
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="dropdown" id="menu_sharepics" style="display: none;">
        <span><?php echo _( 'Public sharepics' ); ?></span>
        <div class="dropdown-content">
            <button onClick="publics.publish()">
                <?php  echo _('Publish this sharepic');?>
            </button>
            <div class="submenu">
                <button>
                    <?php  echo _('My public sharepics');?>
                </button>
                <div id="my-public-sharepics" class="submenu-content">
                    <?php
                        $templates = $this->env->user->get_public_savings();

                        foreach( $templates as $dir ){
                                $id = basename($dir);
                                $name = json_decode( file_get_contents( $dir . '/info.json' ) )->name;
                                echo '<div class="dropdown-item-double">';
                                    printf( '<button class="did-1" onClick="api.load(\'%1$s/sharepic.html\')"><div class="description">%2$s</div><div class="thumb" style="background-image:url(%1$s/thumbnail.png)"></div></button>', $dir, $name );
                                    printf( '<button class="did-2" onClick="publics.delete(this, \'%s\', true)" title="%s"><img src="assets/icons/delete.svg"></button>', $id, _( 'delete' ) );
                                echo '</div>';
                        }
                    ?>
                </div>
            </div>
            <button onClick="publics.show()">
                <?php  echo _('Show all public sharepics');?>
            </button>
        </div>
    </div>
    <?php } ?>
    <div class="dropdown no-arrow" id="menu_nrw">
        <span onclick="api.load('templates/nrw/start.html')">NRW</span>
    </div> 
    <div class="dropdown no-arrow" id="menu_mv">
        <span onclick="api.load('templates/mv/start.html')">MV</span>
    </div> 
    <span class="info-in-menu" id="info-in-menu"></span>
</nav>
