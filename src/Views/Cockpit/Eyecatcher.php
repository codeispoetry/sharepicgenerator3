<section class="mainsection" id="cockpit_eyecatcher">
    <h2><?php  echo _('Eyecatcher');?></h2>
    <section>
        <h3><?php  echo _('Size');?></h3>
        <label>
            <input type="range" min="10" max="80" value="20" class="slider" id="eyecatcher_size">
        </label>
    </section>
    
    <section style="display: flex">
        <button class="to-front" data-target="eyecatcher" title="<?php  echo _('to front');?>"><?php  echo _('to front');?></button>
        <button class="delete" data-target="eyecatcher" title="<?php  echo _('delete');?>"><?php  echo _('delete');?></button>
    </section>
</section>

<script>
    document.getElementById('eyecatcher_size').addEventListener('input', function(event) {
        var element = event.target;

        const target = document.getElementById('eyecatcher');

        target.style.fontSize = element.value + "px";
    });
</script>


