<section class="mainsection" id="cockpit_eyecatcher">
    <h2><?php  echo _('Eyecatcher');?></h2>
    <section>
        <button onClick="component.add('eyecatcher')"><?php  echo _('Add sticker');?></button>
    </section>
    <section class="selected_only">
        
        <h3><?php  echo _('Size');?></h3>
        <input type="range" min="10" max="90" value="20" step="1" class="slider" id="eyecatcher_size" oninput="eyecatcher.setSize(this)">
    
        <h3 class="no-greens"><?php  echo _('Rotation');?></h3>
        <input type="range" min="0" max="360" value="0" class="slider no-greens" id="eyecatcher_rotation" oninput="eyecatcher.rotate(this)">

        <h3 class="no-greens"><?php  echo _('Padding');?></h3>
        <input type="range" min="0" max="360" value="0" class="slider no-greens" id="eyecatcher_padding" oninput="eyecatcher.padding(this)">
    </section>

    <section id="eyecatcher_colors" class="selected_only no-greens">
        <h3><?php echo _('Colors');?></h3>
       
        <div class="horizontal">
            <h4><?php  echo _('Font');?></h4>
            <?php
                $color = new stdClass();
                $color->value = "#ffffff";
                $color->id = "eyecatcher_color";
                $color->oninput = "eyecatcher.setFontColor(this.value)";
                $color->onclick = "eyecatcher.setFontColor";
                require ("./src/Views/Components/Color.php"); 
            ?>
        </div>

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
        setSize(input){
            cockpit.target.style.fontSize = input.value + 'px';
        }

        setBgColor(color) {  
            cockpit.target.querySelector('#sticker_bg').style.fill = color
        }

        setFontColor(color) {      
            cockpit.target.style.color = color
        }

        rotate( input ) {
            cockpit.target.style.transform = 'rotate(' + input.value + 'deg)';
        }

        padding( input ) {
            cockpit.target.style.padding = input.value + 'px';
        }

        makeSquare( target ){
            const p = cockpit.target.querySelector('div').getBoundingClientRect();
            const length = Math.max( p.width, p.height );
            cockpit.target.style.width = length + 'px';
            cockpit.target.style.height = length + 'px';

        }

    }
    const eyecatcher = new Eyecatcher();
</script>


