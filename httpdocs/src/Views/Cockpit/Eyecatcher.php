<section class="mainsection" id="cockpit_eyecatcher">
    <h2>
        <?php  echo _('Eyecatcher');?>
    </h2>

    <section>
        <button onClick="component.add('eyecatcher')"><?php  echo _('Add eyecatcher');?></button>
    </section>

    
    <section class="selected_only">
        <h3><?php  echo _('Size');?></h3>
        <input type="range" min="0.5" max="3" value="1" step="0.025" class="slider" id="eyecatcher_size" oninput="eyecatcher.setSize(this)">
    </section>

    <div style="display:none">
       
        <section class="selected_only">
            <h3 class=""><?php  echo _('Rotation');?></h3>
            <input type="range" min="0" max="360" value="0" class="slider" id="eyecatcher_rotation" oninput="eyecatcher.rotate(this)">
        </section>

        <section class="selected_only">
            <button onClick="eyecatcher.setForm('sticker_circle')"><?php  echo _('Circle');?></button>
            <button onClick="eyecatcher.setForm('sticker_square')"><?php  echo _('Square');?></button>
            <button onClick="eyecatcher.setForm('sticker_rect169')"><?php  echo _('Rect 16:9');?></button>
        </section>
    </div>
    
    <?php
        $delete_button_text = _('Eyecatcher'); 
        require ("./src/Views/Components/ToFrontAndBack.php"); 
    ?>
</section>

<script>
    class Eyecatcher{
        setSize( input ) {
            cockpit.target.style.transform = 'scale(' + input.value + ')';
        }

    }
    const eyecatcher = new Eyecatcher();
</script>


