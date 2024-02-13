<section class="mainsection" id="cockpit_greentext">
    <h2>Text</h2>
    <section>
        <h3><?php echo _('Total size'); ?></h3>
        <label>
            <input type="range" min="0" max="3" value="1" class="slider" step="0.05" id="greentext_size">
        </label>
    </section>
    
    <section class="row">
        <button class="to-front" onClick="ui.toFront(this)" title="<?php  echo _('to front');?>"><?php  echo _('to front');?></button>
        <button class="to-back" onClick="ui.toBack(this)" title="<?php  echo _('to back');?>"><?php  echo _('to back');?></button>
    </section>

</section>

<script>
    document.getElementById('greentext_size').addEventListener('input', function(e) {
        var element = event.target;
        const target = document.getElementById('greentext');
        target.style.zoom = element.value;
    });
</script>



