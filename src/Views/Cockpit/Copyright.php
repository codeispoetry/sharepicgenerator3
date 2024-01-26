<section class="mainsection" id="cockpit_copyright">
    <h2><?php  echo _('Copyright');?></h2>
    <section>
        <h3><?php  echo _('Total size');?></h3>
        <label>
            <input type="range" min="10" max="50" value="20" class="slider" id="copyright_size">
        </label>
    </section>

    <section>
        <label class="horizontal">
            <h4><?php  echo _('Font color');?></h4>
            <input type="color" value="#ffffff" class="" id="copyright_color">
        </label>
    </section>
    
    <section class="row">
        <button class="to-front" data-target="text" title="<?php  echo _('to front');?>"><?php  echo _('to front');?></button>
        <button class="to-back" title="<?php  echo _('to back');?>"><?php  echo _('to back');?></button>
    </section>
</section>

<script>
    document.getElementById('copyright_size').addEventListener('input', function(e) {
        var element = event.target;
        const target = document.getElementById('copyright');
        target.style.fontSize = element.value + "px";
    });

    document.getElementById('copyright_color').addEventListener('input', () => {
      const color = document.getElementById('copyright_color').value
      cockpit.target.style.color = color;
    })
</script>
