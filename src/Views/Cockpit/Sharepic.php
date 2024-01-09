<section class="mainsection" id="cockpit_sharepic">
    <h2><?php  echo _('Sharepic');?></h2>
    <section>
        <h3><?php  echo _('Dimensions');?></h3>
            <div style="display: flex; align-items: center;">
                <input type="number" name="width" id="width" value="500" step="1" style="width: 25%" data-change="sg.set_size">
                <span style="margin: 0 0.5em">x</span>
                <input type="number" name="height" id="height" value="400" step="1" style="width: 25%" data-change="sg.set_size">
                <span style="margin-left: 0.5em"><?php  echo _('pixel');?></span>
            </div>
        
            
            <div class="brands">
                <h4><?php  echo _('Presets');?>:</h4>
                <button data-sizepreset="1280:1280" title="<?php echo _('square 1:1'); ?>">
                    <img src="assets/icons/square1to1.svg">
                </button>
                <button data-sizepreset="1200:630" title="Facebook">
                    <img src="assets/icons/brands/facebook.svg">
                </button>
                <button data-sizepreset="1080:1080" title="Instagram">
                    <img src="assets/icons/brands/instagram.svg">
                </button>
                <button data-sizepreset="1600:900" title="X">
                    <img src="assets/icons/brands/x.svg">
                </button>
                <button data-sizepreset="1200:627" title="X">
                    <img src="assets/icons/brands/linkedin.svg">
                </button>
            </div>
    </section>
    
    <section style="margin-top:4em">
        <h3><?php echo _("Background image"); ?></h3>
        <label style="display:flex; margin-top:20px;">
            <input type="text" style="width:100%;" name="pixabay_q" id="pixabay_q" placeholder="<?php  echo _('search query');?>">
            <button data-click="pixabay.search" style="padding:2px;"><img src="assets/icons/search.svg"></button>
        </label>
        

        <label style="display:flex; align-items: center;padding:3px;margin-top:2em; width: 160px;" class="file-upload">
            <img src="assets/icons/upload.svg"> <?php  echo _('upload image');?>
            <input type="file" name="upload" id="upload">
        </label>
    </section>

    <section style="margin-top:4em">
        <label class="horizontal" style="margin:0">
            <h4><?php  echo _('Background color');?></h4>
            <input type="color" value="#ffffff" class="" id="background_color">
        </label>
        <label class="horizontal" style="margin-top:0">
            <h4><?php  echo _('Background size');?></h4>
            <input type="range" min="10" max="300" value="100" class="slider" id="background_size">
        </label>
        <label style="display:flex;margin-top: 0">
            <button class="link" data-click="sg.reset_background">
                <?php echo _("Fit background automatically"); ?>
            </button>   
        </label>
        <label>
            <input type="checkbox" id="drag_background">
            <?php echo _("Make background draggable"); ?>
        </label>
    </section>
    
</section>



