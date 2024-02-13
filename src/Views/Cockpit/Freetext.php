<section class="mainsection" id="cockpit_freetext">
    <h2><?php  echo _('Text');?></h2>
    <section>
        <h3><?php  echo _('Total size');?></h3>
        <label>
            <input type="range" min="0" max="100" value="50" class="slider" id="text_size">
        </label>
    </section>
    <section class="row">
        <button class="to-front" data-target="text" title="<?php  echo _('to front');?>"><?php  echo _('to front');?></button>
        <button class="to-back" onClick="ui.toBack(this)" title="<?php  echo _('to back');?>"><?php  echo _('to back');?></button>
    </section>

</section>

<script>
    document.getElementById('text_size').addEventListener('input', function(e) {
        var element = event.target;
        const target = document.getElementById('text');
        target.style.fontSize = element.value + "px";
    });
</script>
