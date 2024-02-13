<section class="mainsection" id="cockpit_eyecatcher">
    <h2><?php  echo _('Eyecatcher');?></h2>
    <section>
        <button onClick="component.add('eyecatcher')"><?php  echo _('Add sticker');?></button>
    </section>
    <section>
        <h3><?php  echo _('Size');?></h3>
        <input type="range" min="10" max="90" value="20" step="1" class="slider" id="eyecatcher_size" oninput="eyecatcher.setSize(this)">

        <h3><?php  echo _('Rotation');?></h3>
        <input type="range" min="0" max="360" value="0" class="slider" id="eyecatcher_rotation" oninput="eyecatcher.rotate(this)">

    </section>

    <section id="eyecatcher_colors">
        <h3><?php echo _('Colors');?></h3>
        <div class="grid">
           
                <h4><?php  echo _('Font');?></h4>
                <input type="color" value="#ffffff" class="" id="eyecatcher_color" oninput="eyecatcher.setFontColor(this)">
           
                <h4><?php  echo _('Background');?></h4>
                <input type="color" value="#ffffff" class="" id="eyecatcher_bgcolor" oninput="eyecatcher.setBgColor(this)">
            </label>
        </div>
    </section>
    
    <section class="row">
        <button class="to-front" onClick="component.toFront(this)" title="<?php  echo _('to front');?>"><?php  echo _('to front');?></button>
        <button class="to-back" onClick="component.toBack(this)" title="<?php  echo _('to back');?>"><?php  echo _('to back');?></button>
        <button onClick="cockpit.target.remove()" class="delete" title="<?php  echo _('delete');?>"><?php  echo _('delete');?></button>
    </section>
</section>

<script>
    class Eyecatcher{
        setSize(input){
            cockpit.target.style.fontSize = input.value + 'px';
            this.makeSquare(cockpit.target);
        }

        setBgColor(input) {      
            cockpit.target.style.backgroundColor = input.value;
        }

        setFontColor(input) {      
            cockpit.target.style.color = input.value;
        }

        rotate( input ) {
            cockpit.target.style.transform = 'rotate(' + input.value + 'deg)';
        }

        makeSquare (target) {
            target.style.width = 'auto'
            target.style.height = 'auto'

            const width = parseInt(window.getComputedStyle(target).getPropertyValue('width'), 10)
            const height = parseInt(window.getComputedStyle(target).getPropertyValue('height'), 10)

            target.style.width = Math.max(width, height) + 'px'
            target.style.height = Math.max(width, height) + 'px'
        }

    }
    const eyecatcher = new Eyecatcher();
</script>


