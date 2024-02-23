<section class="mainsection" id="cockpit_download">
    <h2><?php  echo _('Dimensions');?></h2>
    <section>
        <h3><?php  echo _('Set dimensions manually');?></h3>

            <div class="center-row">
                <?php echo _(   'Width' ); ?>: 
                <input type="number" name="width" id="width" value="500" step="1" style="width: 25%; text-align: right;" onChange="sg.setSize()">
            </div>
            <div class="center-row">
                <?php echo _(   'Height' ); ?>: 
                <input type="number" name="height" id="height" value="400" step="1" style="width: 25%; text-align: right;" onChange="sg.setSize()">
            </div>     
           
            <br><?php echo _('Size presets');?>:<br>
            <div class="brands"> 
                <button data-sizepreset="1280:1280" title="<?php echo _('square 1:1'); ?>">
                    <img src="assets/icons/square1to1.svg">
                </button>
                <button data-sizepreset="1280:720" title="<?php echo _('square 1:1'); ?>">
                    <img src="assets/icons/dim16to9.svg">
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
</section>



