<section class="mainsection" id="cockpit_background">
    <h2><?php  echo _('Edit background image');?></h2>

    <section>
        <label class="horizontal" style="margin-top:0">
            <h4><?php  echo _('Size');?></h4>
            <input type="range" min="10" max="300" value="100" class="slider" id="background_size">
        </label>
        <label style="display:flex;margin-top: 0">
            <button class="" onClick="sg.resetBackground()">
                <?php echo _("Fit automatically"); ?>
            </button>   
        </label>
        <label style="display:flex;margin-top: 0.5em">
            <button class="" onClick="sg.deleteBackgroundImage()">
                <?php echo _("Delete background image"); ?>
            </button>   
        </label>
        <label style="display: block;margin-top: 0.5em">
            <input type="checkbox" id="drag_background">
            <?php echo _("Change image crop"); ?>
        </label>
 
        <label class="horizontal" style="margin:0">
            <h4><?php  echo _('Color');?></h4>
            <input type="color" value="#ffffff" class="" id="background_color" onInput="sg.backgroundColor(this)">
        </label>
    </section>

    <h2><?php  echo _('Copyright');?></h2>
    <section>
        <h3><?php  echo _('Size');?></h3>
        <label>
            <input type="range" min="10" max="50" value="20" class="slider" id="copyright_size" oninput="copyright.setSize(this)">
        </label>

        <label class="horizontal">
            <h4><?php  echo _('Color');?></h4>
            <input type="color" value="#ffffff" class="" id="copyright_color" oninput="copyright.setFontColor(this)">
        </label>
    
        <div class="row" style="justify-content: flex-start">
            <button class="to-front" onClick="component.toFront(this)" title="<?php  echo _('to front');?>"><?php  echo _('to front');?></button>
            <button class="to-back" onClick="component.toBack(this)" title="<?php  echo _('to back');?>"><?php  echo _('to back');?></button>
        </div>

    </section>

    
</section>

<script>
    class Copyright{
        setSize(input){
            document.getElementById('copyright').style.fontSize = input.value + 'px';
        }

        setFontColor(input) {      
            document.getElementById('copyright').style.color = input.value;
        }
    }
    const copyright = new Copyright();
</script>




