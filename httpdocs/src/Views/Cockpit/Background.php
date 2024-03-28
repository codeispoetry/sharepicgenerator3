<section class="mainsection" id="cockpit_background">
    <h2><?php  echo _('Edit background image');?></h2>

    <section>
        <h3><?php  echo _('Size');?></h3>
        <input type="range" min="10" max="300" value="100" class="slider" id="background_size" oninput="background.zoom(this.value)">  
    </section>

    <section>
        <h3><?php  echo _('Image crop');?></h3>
        <button class="no-button" onClick="background.reset()">
            <?php echo _("Fit automatically"); ?>
        </button>   

        <label>
            <input type="checkbox" id="drag_background">
            <?php echo _("Change image crop"); ?>
        </label>
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
        <button class="outline" onClick="background.delete()">
            <?php echo _("Delete background image"); ?>
        </button>   
    </section>
    
</section>




