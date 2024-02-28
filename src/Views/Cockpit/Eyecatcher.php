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
    </section>

    <section id="eyecatcher_colors" class="selected_only">
        <h3><?php echo _('Colors');?></h3>
       
        <div class="horizontal">
            <input type="color" value="#ffffff" class="" id="eyecatcher_color" oninput="eyecatcher.setFontColor(this)">
            <h4><?php  echo _('Font');?></h4>
        </div>

        <div class="horizontal">
            <input type="color" value="#ffffff" class="" id="eyecatcher_bgcolor" oninput="eyecatcher.setBgColor(this)">
            <h4><?php  echo _('Background');?></h4>
        </div>
    </section>
    
    <?php require ("./src/Views/Components/ToFrontAndBack.php"); ?>
</section>

<script>
    class Eyecatcher{
        setSize(input){
            cockpit.target.style.fontSize = input.value + 'px';
            this.makeSquare(cockpit.target);
        }

        setBgColor(input) {  
            cockpit.target.querySelector('#sticker_bg').style.fill = input.value;
        }

        setFontColor(input) {      
            cockpit.target.style.color = input.value;
        }

        rotate( input ) {
            cockpit.target.style.transform = 'rotate(' + input.value + 'deg)';
        }

        makeSquare (whole) {
            if(cockpit.target === null) return

            const text = whole.querySelector('div')
            text.style.width = 'auto'
            text.style.height = 'auto'

            const width = parseInt(window.getComputedStyle(text).getPropertyValue('width'), 10)
            const height = parseInt(window.getComputedStyle(text).getPropertyValue('height'), 10)

            cockpit.target.style.width = Math.max(width, height) + 'px'
            cockpit.target.style.height = Math.max(width, height) + 'px'

            text.style.width = '100%'
            text.style.height = '100%'
        }

    }
    const eyecatcher = new Eyecatcher();
</script>


