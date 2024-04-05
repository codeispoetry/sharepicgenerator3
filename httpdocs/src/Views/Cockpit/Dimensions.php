<section class="mainsection" id="cockpit_dimensions">
    <h2><?php  echo _('Dimensions');?></h2>
    <section>
        <h3><?php  echo _('Set dimensions manually');?></h3>
        <input type="number" name="width" id="width" value="500" step="1" style="width: 25%;" onChange="sg.setSize()">
        x
        <input type="number" name="height" id="height" value="400" step="1" style="width: 25%;" onChange="sg.setSize()">       
    </section>
    <section>     
        <h3><?php echo _('Size presets');?></h3>
        <div class="dimensions"> 
            <button data-sizepreset="1280:1280" title="<?php echo _('square 1:1'); ?>">
                <img src="assets/icons/square1to1.svg">
            </button>
            <button data-sizepreset="1280:720" title="<?php echo _('square 16:9'); ?>">
                <img src="assets/icons/dim16to9.svg">
            </button>
            <button data-sizepreset="1200:630" title="Facebook">
                <img src="assets/icons/brands/facebook.svg">
            </button>
            <button data-sizepreset="1080:1350" title="Instagram">
                <img src="assets/icons/brands/instagram.svg">
            </button>
            <button data-sizepreset="1600:900" title="X">
                <img src="assets/icons/brands/x.svg">
            </button>
            <button data-sizepreset="1200:627" title="X">
                <img src="assets/icons/brands/linkedin.svg">
            </button>   
        </div>
        <div class="dimensions dimensions-column">
            <button data-sizepreset="1500:2102">Postkarte hockkant</button>
            <button data-sizepreset="2102:1500">Postkarte quer</button>

            <button data-sizepreset="3531:4984">Plakat DIN A3</button>
            <button data-sizepreset="2492:3520">Plakat DIN A2</button>
            <button data-sizepreset="3520:4972">Plakat DIN A1</button>
        </div>
        <div>
            <?php
                echo _('You are missing a particular size? Please feel free to edit the sizes manually above.');
            ?>
        </div>
    </section>

   <section id="qrcode-section" style="display:none">
        <h3><?php  echo _('QR-Code');?></h3>
        <div id="qrcode"></div>
        <?php
            echo _('You can scan this QR-Code with your smartphone to open your sharepic on your mobile.');
            echo ' ';
            echo _('This link is valid for 5 minutes.');
        ?>
       </section>
</section>
