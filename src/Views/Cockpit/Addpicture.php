<section class="mainsection" id="cockpit_addpicture">
    <h2><?php  echo _('Picture');?></h2>
    <section>
        <button onClick="component.add('addpicture')"><?php  echo _('Add picture');?></button>
    </section>
    <section>
        <h3><?php  echo _('Total size');?></h3>
        <label>
            <input type="range" min="0" max="500" value="50" class="slider" id="addpicture_size">
        </label>
    </section>

    <section>
        <h3><?php echo _("Upload image"); ?></h3>
        <label style="display:flex; align-items: center;padding:3px;margin-top:1em; width: 160px;" class="file-upload">
            <img src="assets/icons/upload.svg"> <?php  echo _('upload image');?>
            <input type="file" name="upload" onChange="api.uploadAddPic(this)">
        </label>
    </section>

    <section>
        <h3><?php echo _("Image"); ?></h3>
        <button id="addpic_pic_round" class="blankbutton" title="<?php  echo _('round');?>"><img src="assets/icons/circle.svg"></button>
        <button id="addpic_pic_angular" class="blankbutton" title="<?php  echo _('angular');?>"><img src="assets/icons/square.svg"></button>      
    </section>

    <section>
        <h3><?php echo _("Text"); ?></h3>
        <button id="addpic_text_right" class="blankbutton" title="<?php  echo _('text floats right');?>"><img src="assets/icons/text-right.svg"></button>
        <button id="addpic_text_below" class="blankbutton"  title="<?php  echo _('text below');?>"><img src="assets/icons/text-below.svg"></button>
        <input type="color" value="#ffffff" class="" id="addpic_color" title="<?php echo _('Color'); ?>">
      
    </section>

    <section style="display: flex">
        <button class="to-front" onClick="component.toFront(this)" title="<?php  echo _('to front');?>"><?php  echo _('to front');?></button>
        <button class="to-back" onClick="component.toBack(this)" title="<?php  echo _('to back');?>"><?php  echo _('to back');?></button>
        <button onClick="cockpit.target.remove()" class="delete" title="<?php  echo _('delete');?>"><?php  echo _('delete');?></button>
    </section>

</section>

<script>
    document.getElementById('addpicture_size').addEventListener('input', function(e) {
        cockpit.target.querySelector('.ap_image').style.width = e.target.value + 'px';
        cockpit.target.querySelector('.ap_text').style.fontSize = Math.max( 20, e.target.value * 0.1 ) + 'px';

    });

    document.getElementById('addpic_text_right').addEventListener('click', function(e) {
        cockpit.target.style.display = 'flex';
    });

    document.getElementById('addpic_text_below').addEventListener('click', function(e) {
        cockpit.target.style.display = 'block';
    });

    document.getElementById('addpic_pic_round').addEventListener('click', function(e) {
        cockpit.target.querySelector('.ap_image').style.borderRadius = '50%';
    });

    document.getElementById('addpic_pic_angular').addEventListener('click', function(e) {
        cockpit.target.querySelector('.ap_image').style.borderRadius = '0';
    });

    document.getElementById('addpic_color').addEventListener('input', function(e) {
        cockpit.target.querySelector('.ap_text').style.color = e.target.value;
    });

</script>
