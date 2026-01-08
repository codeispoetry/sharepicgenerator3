<section class="mainsection" id="cockpit_eyecatcherbw">
    <h2>
        <?php  echo _('Eyecatcher');?> BW
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
    
    <section class="selected_only color">
        <h3><?php  echo _('Color');?></h3>

        <div class="standard-palette">
    
        <button class="" style="background-color: #e6fd53" onclick="eyecatcherbw.setColorScheme(1);"></button>
        <button class="" style="background-color: #78a08c" onclick="eyecatcherbw.setColorScheme(2);"></button>
        <button class="" style="background-color: #1c302a" onclick="eyecatcherbw.setColorScheme(3);"></button>
       
        </div>
    </section>

    
    <?php
        $delete_button_text = _('Eyecatcher'); 
        require ("./src/Views/Components/ToFrontAndBack.php"); 
    ?>
</section>

<script>
    class EyecatcherBW{
        setSize( input ) {
            cockpit.target.style.transform = 'scale(' + input.value + ')';
        }

        setColorScheme( scheme ) {

            switch(scheme) {
                case 1:
                    cockpit.target.querySelector('div').style.color = '#1c302a'
                    cockpit.target.querySelector('div').style.backgroundColor = '#e6fd53'
                    break;
                case 2:
                    cockpit.target.querySelector('div').style.backgroundColor = '#78a08c'
                    cockpit.target.querySelector('div').style.color = '#f5f1e9'
                    break;
                case 3:
                    cockpit.target.querySelector('div').style.backgroundColor = '#1c302a'
                    cockpit.target.querySelector('div').style.color = '#f5f1e9'
                    break;
            }
            

            undo.commit();
        }

    }
    const eyecatcherbw = new EyecatcherBW();
</script>


