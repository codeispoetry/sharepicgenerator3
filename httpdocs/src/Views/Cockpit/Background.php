<section class="mainsection" id="cockpit_background">
    <h2>
        <?php  echo _('Edit background image');?>
    </h2>

    <section>
        <h3><?php  echo _('Image size');?></h3>
        <input type="range" min="10" max="300" value="100" class="slider" id="background_size" oninput="background.zoom(this.value)">  
    
        <div>
            <button class="no-button" onClick="background.reset()">
                <?php echo _("fit automatically"); ?>
            </button>   

            <label>
                <input type="checkbox" id="drag_background">
                <?php echo _("Make image draggable"); ?>
            </label>
        </div>
    </section>

    <section>
        <h3 onClick="ui.unfold(this,'filter-section');" class="folder-button"><?php  echo _('Filter');?></h3>
        <div id="filter-section" class="foldable folded">
            <strong><?php echo _('Brightness'); ?></strong>
            <input type="range" min="0" max="1" step="0.05" value="1" class="slider" id="background_brightness" oninput="background.filter('brightness', this.value)">  
            <strong><?php echo _('Saturate'); ?></strong>
            <input type="range" min="0" max="1" step="0.05" value="1" class="slider" id="background_saturate" oninput="background.filter('saturate', this.value)"> 
            <strong><?php echo _('Blur'); ?></strong>
            <input type="range" min="0" max="10" step="0.05" value="0" class="slider" id="background_blur" oninput="background.filter('blur', this.value)">   
        </div>
        </section>
    
    <h3 onClick="ui.unfold(this,'copyright-section');" class="folder-button"><?php  echo _('Copyright');?></h3>
    <div id="copyright-section" class="foldable folded">
        <section id="add_copyright_section">
            <button onClick="component.add('copyright')" id="add_copyright"><?php  echo _('Add copyright');?></button>
        </section>
        
        <section class="with_copyright d-none">
            <h3><?php  echo _('Size');?></h3>
            <input type="range" min="10" max="50" value="20" class="slider" id="copyright_size" oninput="copyright.setSize(this)">
        </section>

        <section class="with_copyright d-none">
            <?php
                $color = new stdClass();
                $color->value = "#ffffff";
                $color->id = "copyright_color";
                $color->oninput = "copyright.setFontColor(this.value)";
                $color->onclick = "copyright.setFontColor";
                require ("./src/Views/Components/Color.php"); 
            ?>
        </section>

        <section class="with_copyright d-none">
            <h3><?php  echo _('Position');?></h3>
            <div style="display: flex; justify-content:space-between;">
                <button class="no-button" onclick="copyright.setPostion(1);">
                    <?php  echo _('bottom left');?>
                </button>
                <button class="no-button" onclick="copyright.setPostion(2);">
                    <?php  echo _('bottom right');?>
                </button>
            </div>
        </section>
    </div>

    <section>
        <button class="delete" onClick="background.delete()">
            <?php echo _("Delete background image"); ?>
        </button>   
    </section>
</section>

<script>
    class Copyright{

        setSize(input){
            const sharepic = document.getElementById('sharepic')
            const target = sharepic.querySelector('[id^="copyright_"]');
            target.style.fontSize = input.value + 'px';
            undo.commit()
        }

        setFontColor(color) { 
            const sharepic = document.getElementById('sharepic')     
            const target = sharepic.querySelector('[id^="copyright_"]');
            document.getElementById('copyright_color').value = color
            target.style.color = color
            undo.commit()
        }

        setPostion( pos ) {
            const sharepic = document.getElementById('sharepic')
            const target = sharepic.querySelector('[id^="copyright_"]');

            switch( pos ) {
                case 2:
                    target.style.bottom = '8px';
                    target.style.left = 'auto';
                    target.style.right = '10px';
                    target.style.transform = 'rotate(0deg)';
                    break;
                default:
                    target.style.bottom = '0';
                    target.style.right = 'auto'
                    target.style.left = '10px';
                    target.style.transform = 'rotate(-90deg)';
                    break;
            }    
            undo.commit()
        }
    }
    const copyright = new Copyright();
</script>
