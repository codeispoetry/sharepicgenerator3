<section class="mainsection" id="cockpit_eyecatcher">
    <h2><?php  echo _('Eyecatcher');?></h2>
    <section>
        <h3><?php  echo _('Size');?></h3>
        <input type="range" min="10" max="90" value="20" step="1" class="slider" id="eyecatcher_size">

        <h3><?php  echo _('Rotation');?></h3>
        <input type="range" min="0" max="360" value="0" class="slider" id="eyecatcher_rotation" oninput="rotateElement(event)">

    </section>

    <section id="eyecatcher_colors">
        <h3><?php echo _('Colors');?></h3>
        <div class="grid">
           
                <h4><?php  echo _('Font');?></h4>
                <input type="color" value="#ffffff" class="" id="eyecatcher_color">
           
                <h4><?php  echo _('Background');?></h4>
                <input type="color" value="#ffffff" class="" id="eyecatcher_bgcolor">
            </label>
        </div>
    </section>
    
    <section class="row">
        <button class="to-front" title="<?php  echo _('to front');?>"><?php  echo _('to front');?></button>
        <button class="to-back" title="<?php  echo _('to back');?>"><?php  echo _('to back');?></button>
        <button class="delete" title="<?php  echo _('delete');?>"><?php  echo _('delete');?></button>
    </section>
</section>

<script>
    document.getElementById('eyecatcher_size').addEventListener('input', function(event) {
        var element = event.target;
        cockpit.target.style.fontSize = element.value + 'px';
        makeSquare(cockpit.target);
    });

    document.getElementById('eyecatcher_bgcolor').addEventListener('input', () => {
      const color = document.getElementById('eyecatcher_bgcolor').value
      
      cockpit.target.style.backgroundColor = color;
    })

    document.getElementById('eyecatcher_color').addEventListener('input', () => {
      const color = document.getElementById('eyecatcher_color').value
      cockpit.target.style.color = color;
    })

    function rotateElement(event) {
        var angle = event.target.value;
        cockpit.target.style.transform = 'rotate(' + angle + 'deg)';
    }

</script>


