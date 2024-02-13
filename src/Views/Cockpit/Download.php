<section class="mainsection" id="cockpit_download">
    <h2><?php  echo _('Download');?></h2>
    <section>
        <h3><?php  echo _('Dimensions');?></h3>
            <div style="display: flex; align-items: center;">
                <input type="number" name="width" id="width" value="500" step="1" style="width: 25%" onChange="sg.setSize()">
                <span style="margin: 0 0.5em">x</span>
                <input type="number" name="height" id="height" value="400" step="1" style="width: 25%" onChange="sg.setSize()">
                <span style="margin-left: 0.5em"><?php  echo _('pixel');?></span>
            </div>
        
            
            <div class="brands">
                <small><?php echo _('Size presets');?>:</small>
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
    
    <section>
        <button class="create flat" onClick="api.create()"><img src="assets/icons/download.svg"> Herunterladen</button>
    </section>
</section>



