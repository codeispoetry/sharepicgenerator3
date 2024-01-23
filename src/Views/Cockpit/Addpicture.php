<section class="mainsection" id="cockpit_addpicture">
    <h2><?php  echo _('Picture');?></h2>
    <section>
        <h3><?php  echo _('Total size');?></h3>
        <label>
            <input type="range" min="0" max="300" value="50" class="slider" id="addpicture_size">
        </label>
    </section>
    <section style="display: flex">
        <button class="to-front" title="<?php  echo _('to front');?>"><?php  echo _('to front');?></button>
    </section>

</section>

<script>
    document.getElementById('addpicture_size').addEventListener('input', function(e) {
        var element = event.target;
        const target = document.querySelector('#addpicture3 .ap_image');
        target.style.width = element.value + 'px';
    });
</script>
