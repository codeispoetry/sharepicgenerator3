<section class="mainsection" id="cockpit_eyecatcher">
    <h2><?php  echo _('Eyecatcher');?></h2>
    <section>
        <h3><?php  echo _('Size');?></h3>
        <label>
            <input type="range" min="10" max="80" value="20" class="slider" id="eyecatcher_size">
        </label>
    </section>

    <section style="margin-top:4em">
        <label class="horizontal">
            <h4><?php  echo _('Font color');?></h4>
            <input type="color" value="#ffffff" class="" id="eyecatcher_color">
        </label>
  
        <label class="horizontal" style="margin-top: 0">
            <h4><?php  echo _('Background color');?></h4>
            <input type="color" value="#ffffff" class="" id="eyecatcher_bgcolor">
        </label>
    </section>
    
    <section style="display: flex">
        <button class="to-front" title="<?php  echo _('to front');?>"><?php  echo _('to front');?></button>
        <button class="delete" title="<?php  echo _('delete');?>"><?php  echo _('delete');?></button>
    </section>
</section>

<script>
    document.getElementById('eyecatcher_size').addEventListener('input', function(event) {
        var element = event.target;
        cockpit.target.style.fontSize = element.value + "px";
    });

    document.getElementById('eyecatcher_bgcolor').addEventListener('input', () => {
      const color = document.getElementById('eyecatcher_bgcolor').value
      
      cockpit.target.style.backgroundColor = color;
    })

    document.getElementById('eyecatcher_color').addEventListener('input', () => {
      const color = document.getElementById('eyecatcher_color').value
      cockpit.target.style.color = color;
    })

</script>


