<section class="mainsection" id="cockpit_background">
    <h2><?php  echo _('Edit background image');?></h2>

    <section>
        <h3><?php  echo _('Size');?></h3>
        <input type="range" min="10" max="300" value="100" class="slider" id="background_size" oninput="background.zoom(this.value)">  
    </section>

    <section>
        <h3><?php  echo _('Image crop');?></h3>

        <div>
            <button class="no-button" onClick="background.reset()">
                <?php echo _("Fit automatically"); ?>
            </button>   

            <label>
                <input type="checkbox" id="drag_background">
                <?php echo _("Drag image"); ?>
            </label>
        </div>
    </section>

    <section>  
        <h3><?php  echo _('Color');?></h3>   
        <?php
            $color = new stdClass();
            $color->value = "#ffffff";
            $color->id = "background_color";
            $color->oninput = "background.color(this.value)";
            $color->onclick = "background.color";
            require ("./src/Views/Components/Color.php"); 
        ?>
    </section>

    <section>
        <h3><?php  echo _('Filter');?></h3>
        <strong><?php echo _('Brightness'); ?></strong>
        <input type="range" min="0" max="1" step="0.05" value="1" class="slider" id="background_brightness" oninput="background.filter('brightness', this.value)">  
        <strong><?php echo _('Saturate'); ?></strong>
        <input type="range" min="0" max="1" step="0.05" value="1" class="slider" id="background_saturate" oninput="background.filter('saturate', this.value)"> 
        <strong><?php echo _('Blur'); ?></strong>
        <input type="range" min="0" max="10" step="0.05" value="0" class="slider" id="background_blur" oninput="background.filter('blur', this.value)">   
    </section>

    <section>
        <button class="outline" onClick="background.delete()">
            <?php echo _("Delete background image"); ?>
        </button>   
    </section>
    
</section>




