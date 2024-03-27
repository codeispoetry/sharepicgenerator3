<section class="mainsection" id="cockpit_addpicture">
    <h2><?php  echo _('Foreground pictures');?></h2>

    <section>
        <button onClick="component.add('addpicture'); document.getElementById('addAddPicture').click();"><?php  echo _('Add picture');?></button>
    </section>

    <section class="selected_only">
        <h3><?php  echo _('Total size');?></h3>
        <input type="range" min="0" max="500" value="50" class="slider" id="addpicture_size">
    </section>

    <section class="selected_only">
        <h3><?php echo _("Upload image"); ?></h3>

        <button onClick="document.getElementById('addAddPicture').click()">
            <img src="assets/icons/upload.svg">
            <?php  echo _('upload new image');?>
        </button>

        <input type="file" id="addAddPicture" name="upload" onChange="api.uploadAddPic(this)" style="display: none";>
    </section>

    <section class="selected_only">
        <h3><?php echo _("Image"); ?></h3>
        <div class="horizontal">
            <button id="addpic_pic_round" class="no-button" title="<?php  echo _('round');?>"><img src="assets/icons/circle.svg"></button>
            <button id="addpic_pic_angular" class="no-button" title="<?php  echo _('angular');?>"><img src="assets/icons/square.svg"></button>      
            <button id="addpic_pic_original" class="no-button" title="<?php  echo _('original');?>"><img src="assets/icons/rectangle.svg"></button>      
        </div>
        </section>

    <section class="selected_only">
        <h3><?php echo _("Text"); ?></h3>
        <div class="horizontal">
            <button id="addpic_text_right" class="no-button" title="<?php  echo _('text floats right');?>"><img src="assets/icons/text-right.svg"></button>
            <button id="addpic_text_below" class="no-button"  title="<?php  echo _('text below');?>"><img src="assets/icons/text-below.svg"></button>
            <input type="color" value="#ffffff" class="" id="addpic_color" title="<?php echo _('Color'); ?>">
        </div>
    </section>

    <?php require ("./src/Views/Components/ToFrontAndBack.php"); ?>

</section>

<script>
    document.getElementById('addpicture_size').addEventListener('input', function(e) {
        cockpit.target.querySelector('.ap_image').style.width = e.target.value + 'px';
    
        const fontSize = Math.min( 22, Math.max( 18, e.target.value * 0.1 ) );
        cockpit.target.querySelector('.ap_text').style.fontSize = fontSize + 'px';
    });

    document.getElementById('addpic_text_right').addEventListener('click', function(e) {
        cockpit.target.style.display = 'flex';
        undo.commit()
    });

    document.getElementById('addpic_text_below').addEventListener('click', function(e) {
        cockpit.target.style.display = 'block';
        undo.commit()
    });

    document.getElementById('addpic_pic_round').addEventListener('click', function(e) {
        cockpit.target.querySelector('.ap_image').style.borderRadius = '50%';
        cockpit.target.querySelector('.ap_image').style.width = '100px';
        cockpit.target.querySelector('.ap_image').style.height = '100px';
        undo.commit()
    });

    document.getElementById('addpic_pic_angular').addEventListener('click', function(e) {
        cockpit.target.querySelector('.ap_image').style.borderRadius = '0';
        cockpit.target.querySelector('.ap_image').style.width = '100px';
        cockpit.target.querySelector('.ap_image').style.height = '100px';
        undo.commit()
    });

    document.getElementById('addpic_pic_original').addEventListener('click', function(e) {
        cockpit.target.querySelector('.ap_image').style.borderRadius = '0';
        
        var img = new Image();
        img.onload = function() {
            const w = this.width;
            const h = this.height;
            const ratio = w / h;

            let factor = 0.1
            new_w = w * factor;
            new_h = h * factor;
            
            cockpit.target.querySelector('.ap_image').style.height= new_h + 'px';
            cockpit.target.querySelector('.ap_image').style.width = new_w + 'px';

        }
        img.src = cockpit.target.querySelector('.ap_image').style.backgroundImage.replace('url("', '').replace('")', '');

        undo.commit()
    });

    document.getElementById('addpic_color').addEventListener('input', function(e) {
        cockpit.target.querySelector('.ap_text').style.color = e.target.value;
    });

</script>
