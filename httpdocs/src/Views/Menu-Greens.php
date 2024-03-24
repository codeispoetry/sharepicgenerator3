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

    <div class="dropdown" id="menu_de">
        <span><?php echo _( 'Deutschland' ); ?></span>
        <div class="dropdown-content">
            <button onClick="api.load('templates/de/start.html'); bwstory=false;">
                <?php  echo _('Standardlayout');?>
            </button> 
        </div>
    </div>

    <div class="dropdown" id="menu_file">
        <span><?php echo _( 'Baden-Württemberg' ); ?></span>
        <div class="dropdown-content">
            <button onClick="api.load('templates/bw-kandi-vorstellung/start.html'); bwstory=false;">
                <?php  echo _('KW 24 Kandi Vorstellung');?>
            </button>
            <button onClick="api.load('templates/bw-kandi-vorstellung/start.html'); bwstory=true;" class="story">
                <?php  echo _('Hochformat/Story');?>
            </button>
            <div class="divider"></div>

            <button onClick="api.load('templates/bw-thema/start.html'); bwstory=false;">
                <?php  echo _('KW 24 Thema');?>
            </button>
            <button onClick="api.load('templates/bw-thema/start.html'); bwstory=true;" class="story">
                <?php  echo _('Hochformat/Story');?>
            </button>
            <div class="divider"></div>
            
            <button onClick="api.load('templates/bw-bilanz/start.html'); bwstory=false;">
                <?php  echo _('KW 24 Erfolgsbilanz');?>
            </button>
            <button onClick="api.load('templates/bw-bilanz/start.html'); bwstory=true;" class="story">
                <?php  echo _('Hochformat/Story');?>
            </button>
            <div class="divider"></div>
            
            <button onClick="api.load('templates/bw-zitat/start.html'); bwstory=false;">
                <?php  echo _('KW 24 Zitat');?>
            </button>
            <button onClick="api.load('templates/bw-zitat/start.html'); bwstory=true;" class="story">
                <?php  echo _('Hochformat/Story');?>
            </button>
            <div class="divider"></div>
            
            <button onClick="api.load('templates/bw-steckbrief/start.html'); bwstory=false;">
                <?php  echo _('KW 24 Steckbrief');?>
            </button>
            <button onClick="api.load('templates/bw-steckbrief/start.html'); bwstory=true;" class="story">
                <?php  echo _('Hochformat/Story');?>
            </button>
            <div class="divider"></div>
            
            <button onClick="api.load('templates/vorort/start.html'); bwstory=false;">
                <?php  echo _('Vor Ort');?>
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
                $savings = $this->user->get_savings();
                foreach( $savings as $dir => $name ){
                        echo '<div class="dropdown-item-double">';
                            printf( '<button class="did-1" onClick="api.load(\'save/%s/sharepic.html\')">%s</button>', $dir, $name );
                            printf( '<button class="did-2" onClick="ui.deleteSavedSharepic(this, \'%s\')"><img src="assets/icons/delete.svg"></button>', $dir);
                        echo '</div>';
                }
            ?>
        </div>
    </div>
    <?php } ?>
</nav>
