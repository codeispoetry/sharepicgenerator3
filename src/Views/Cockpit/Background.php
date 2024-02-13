<section class="mainsection" id="cockpit_background">
    <h2><?php  echo _('Background image');?></h2>

    
    <section>
        <h3><?php echo _("Search"); ?></h3>
        <label style="display:flex; margin-top:20px;">
            <input type="text" style="width:100%;" name="pixabay_q" id="pixabay_q" placeholder="<?php  echo _('search query');?>">
            <button onClick="pixabay.search()" style="padding:2px;"><img src="assets/icons/search.svg"></button>
        </label>
    </section>
    
    
    <section>
        <h3><?php echo _("Own image"); ?></h3>
        <label style="display:flex; align-items: center;padding:3px;margin-top:1em; width: 160px;" class="file-upload">
            <img src="assets/icons/upload.svg"> <?php  echo _('upload image');?>
            <input type="file" name="upload" id="upload" onChange="api.upload(this)">
        </label>
    </section>

    <section>
        <h3><?php echo _("Preferences"); ?></h3>
        <label class="horizontal" style="margin-top:0">
            <h4><?php  echo _('Size');?></h4>
            <input type="range" min="10" max="300" value="100" class="slider" id="background_size">
        </label>
        <label style="display:flex;margin-top: 0">
            <button class="link" onClick="sg.resetBackground()">
                <?php echo _("Fit automatically"); ?>
            </button>   
        </label>
        <label style="display:flex;margin-top: 0">
            <button class="link" onClick="sg.deleteBackgroundImage()">
                <?php echo _("Delete background image"); ?>
            </button>   
        </label>
        <label>
            <input type="checkbox" id="drag_background">
            <?php echo _("Change image crop"); ?>
        </label>
    </section>
    <section>
        <label class="horizontal" style="margin:0">
            <h4><?php  echo _('Color');?></h4>
            <input type="color" value="#ffffff" class="" id="background_color">
        </label>
    </section>
    
</section>



