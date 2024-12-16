<section class="mainsection" id="cockpit_logo">
    <h2><?php  echo _('Logo');?></h2>
    <section class="selected_only">
        <h3><?php  echo _('Size');?></h3>
        <input type="range" min="0" max="500" value="50" class="slider" id="logo_size" oninput="eyecatcher.setSize(this)">
    </section>

    <section id="eyecatcher_colors" class="selected_only">
        
        <div class="color" id="logo_bgcolor">   
    
            <h3><?php echo _('Colors');?></h3>
            <div class="standard-palette">
                <?php
                $standard_colors = array(
                   "#ffffff","#fff17a"
                );

                foreach($standard_colors as $standard_color){
                    printf('<button 
                                class="no-button" 
                                style="background-color: %s" 
                                onClick="eyecatcher.setBgColor(this.style.backgroundColor);">
                            </button>', 
                            $standard_color
                        );
                    }
                ?>
            
            </div>
        </div>

    </section>

<?php $nodelete = true; require ("./src/Views/Components/ToFrontAndBack.php"); ?>
    
</section>
