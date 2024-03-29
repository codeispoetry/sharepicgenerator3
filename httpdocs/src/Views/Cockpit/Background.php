<section class="mainsection" id="cockpit_background">
    <h2><?php  echo _('Edit background image');?></h2>

    <section>
        <h3><?php  echo _('Size');?></h3>
        <input type="range" min="10" max="300" value="100" class="slider" id="background_size">  
    </section>

    <section>
        <h3><?php  echo _('Preferences');?></h3>
        <button class="" onClick="sg.resetBackground()">
            <?php echo _("Fit automatically"); ?>
        </button>   
    
        <button class="" onClick="sg.deleteBackgroundImage()">
            <?php echo _("Delete background image"); ?>
        </button>   

        <label>
            <input type="checkbox" id="drag_background">
            <?php echo _("Change image crop"); ?>
        </label>
    </section>

    <section>  
        <h3><?php  echo _('Color');?></h3>
        <input type="color" value="#ffffff" class="" id="background_color" onInput="sg.backgroundColor(this)">
        <br>
        <div style="display: flex">
            <button class="no-button" onClick="sg.backgroundColorPreset('#005437');">
                <?php echo _("Tanne"); ?>
            </button>
            <button class="no-button" onClick="sg.backgroundColorPreset('#008939');">
                <?php echo _("Klee"); ?>
            </button>
            <button class="no-button" onClick="sg.backgroundColorPreset('#8abd24');">
                <?php echo _("Gras"); ?>
            </button>
            <button class="no-button" onClick="sg.backgroundColorPreset('#f5f1e9');">
                <?php echo _("Sand"); ?>
            </button>
        </div>
    
    </section>
    
</section>




