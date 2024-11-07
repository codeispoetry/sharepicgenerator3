<section class="mainsection" id="cockpit_eyecatcher">
    <h2><?php  echo _('Eyecatcher');?></h2>
    <section>
        <button onClick="component.add('eyecatcher')"><?php  echo _('Add sticker');?></button>
    </section>
    
    <section class="selected_only">
        <h3><?php  echo _('Size');?></h3>
        <input type="range" min="0" max="500" value="50" class="slider" id="eyecatcher_size" oninput="eyecatcher.setSize(this)">
    </section>
    <section class="selected_only">
        <h3 class="no-greens"><?php  echo _('Rotation');?></h3>
        <input type="range" min="0" max="360" value="0" class="slider no-greens" id="eyecatcher_rotation" oninput="eyecatcher.rotate(this)">
    </section>

    <section class="selected_only">
        <button onClick="eyecatcher.setForm('sticker_circle')"><?php  echo _('Circle');?></button>
        <button onClick="eyecatcher.setForm('sticker_square')"><?php  echo _('Square');?></button>
        <button onClick="eyecatcher.setForm('sticker_rect169')"><?php  echo _('Rect 16:9');?></button>
    </section>

    <section id="eyecatcher_colors" class="selected_only no-greens">
        <h3><?php echo _('Colors');?></h3>

        <div class="horizontal">
            <h4><?php echo _('Background');?></h4>
            <?php
                $color = new stdClass();
                $color->value = "#ffffff";
                $color->id = "eyecatcher_bgcolor";
                $color->oninput = "eyecatcher.setBgColor(this.value)";
                $color->onclick = "eyecatcher.setBgColor";
                require ("./src/Views/Components/Color.php"); 
            ?>
        </div>
    </section>
    
    <?php require ("./src/Views/Components/ToFrontAndBack.php"); ?>
</section>

<script>
    class Eyecatcher{
        setBgColor(color) {  
            cockpit.target.querySelector('#sticker_bg').style.fill = color
        }

        rotate( input ) {
            cockpit.target.style.transform = 'rotate(' + input.value + 'deg)';
        }

        setSize( input ) {
            cockpit.target.style.width = input.value + 'px';
            cockpit.target.style.height = input.value + 'px';

        }

        setForm( id ) {
            const svg = cockpit.target.querySelector('svg');

            const new_form = document.getElementById(id).cloneNode(true);
            svg.parentNode.replaceChild(new_form, svg)
        }

    }
    const eyecatcher = new Eyecatcher();
</script>


