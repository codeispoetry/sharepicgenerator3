<section class="mainsection" id="cockpit_addpicture">
    <h2><?php  echo _('Picture');?></h2>
    NOCH NICHT FERTIG
    <section>
        <h3><?php  echo _('Total size');?></h3>
        <label>
            <input type="range" min="0" max="300" value="50" class="slider" id="addpicture_size">
        </label>
    </section>

    <section>
        <h3><?php echo _("Upload image"); ?></h3>
        <label style="display:flex; align-items: center;padding:3px;margin-top:1em; width: 160px;" class="file-upload">
            <img src="assets/icons/upload.svg"> <?php  echo _('upload image');?>
            <input type="file" name="upload" id="upload_addpic">
        </label>
    </section>

    <section style="display: flex">
        <button class="to-front" title="<?php  echo _('to front');?>"><?php  echo _('to front');?></button>
        <button class="to-back" title="<?php  echo _('to back');?>"><?php  echo _('to back');?></button>
        <button class="delete" title="<?php  echo _('delete');?>"><?php  echo _('delete');?></button>
    </section>

</section>

<script>
    document.getElementById('addpicture_size').addEventListener('input', function(e) {
        var element = event.target;
        const target = document.querySelector('#addpicture3 .ap_image');
        target.style.width = element.value + 'px';
    });
</script>
