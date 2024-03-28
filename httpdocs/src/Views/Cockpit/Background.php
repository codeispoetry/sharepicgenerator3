<section class="mainsection" id="cockpit_background">
    <h2><?php  echo _('Edit background image');?></h2>

    <section>
        <h3><?php  echo _('Size');?></h3>
        <input type="range" min="10" max="300" value="100" class="slider" id="background_size" oninput="background.zoom(this.value)">  
    </section>

    <section>
        <h3><?php  echo _('Preferences');?></h3>
        <button class="" onClick="background.reset()">
            <?php echo _("Fit automatically"); ?>
        </button>   
    
        <button class="" onClick="background.delete()">
            <?php echo _("Delete background image"); ?>
        </button>   

        <label>
            <input type="checkbox" id="drag_background">
            <?php echo _("Change image crop"); ?>
        </label>
    </section>

    <section>  
        <h3><?php  echo _('Color');?></h3>
        <input type="color" value="#ffffff" class="" id="background_color" onInput="background.color(this.value)">
        <br>
        <div style="display: flex">
            <button class="no-button" onClick="background.color('#005437');">
                <?php echo _("Tanne"); ?>
            </button>
            <button class="no-button" onClick="background.color('#008939');">
                <?php echo _("Klee"); ?>
            </button>
            <button class="no-button" onClick="background.color('#8abd24');">
                <?php echo _("Gras"); ?>
            </button>
            <button class="no-button" onClick="background.color('#f5f1e9');">
                <?php echo _("Sand"); ?>
            </button>
        </div>
    
    </section>
    
</section>




