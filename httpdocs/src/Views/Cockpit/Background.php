<section class="mainsection" id="cockpit_background">
    <h2>
        <a href="#" onClick="ui.showTab('search')" style="text-decoration: none;">
            <
        </a>
        <?php  echo _('Background image');?>
    </h2>

    <section>
        <h3><?php  echo _('Size');?></h3>
        <input type="range" min="10" max="300" value="100" class="slider" id="background_size" oninput="background.zoom(this.value)">  
    
        <div>
            <button class="no-button" onClick="background.reset()">
                <?php echo _("fit automatically"); ?>
            </button>   

            <label>
                <input type="checkbox" id="drag_background">
                <?php echo _("Make image draggable"); ?>
            </label>
        </div>
    </section>

    <section>
        <h3 onClick="ui.unfold(this,'filter-section');" class="folder-button"><?php  echo _('Filter');?></h3>
        <div id="filter-section" class="foldable folded">
            <strong><?php echo _('Brightness'); ?></strong>
            <input type="range" min="0" max="1" step="0.05" value="1" class="slider" id="background_brightness" oninput="background.filter('brightness', this.value)">  
            <strong><?php echo _('Saturate'); ?></strong>
            <input type="range" min="0" max="1" step="0.05" value="1" class="slider" id="background_saturate" oninput="background.filter('saturate', this.value)"> 
            <strong><?php echo _('Blur'); ?></strong>
            <input type="range" min="0" max="10" step="0.05" value="0" class="slider" id="background_blur" oninput="background.filter('blur', this.value)">   
        </div>
        </section>

    <section>
        <button class="outline" onClick="background.delete()">
            <?php echo _("Delete background image"); ?>
        </button>   
    </section>
    
    <button onClick="ui.showTab('copyright')">
        <?php echo _('Copyright'); ?>
    </button>

</section>




