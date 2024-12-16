<section class="mainsection" id="cockpit_greentext">
    <h2>Text</h2>
    <section>
        <h3><?php echo _('Total size'); ?></h3>
        <label>
            <input type="range" min="0" max="3" value="1" class="slider" step="0.05" id="greentext_size" oninput="greentext.setSize(this)">
        </label>
    </section>

    <section class="selected_only">
        <h3><?php  echo _('Font');?></h3>

        <div class="">
            <button class="with-icon" onClick="greentext.toogleCapitalization()" title="<?php  echo _('toggle capitalization');?>">
                <div class="icon">
                    <img src="assets/icons/align-justify.svg" alt="<?php  echo _('toggle capitalization');?>" />
                </div>
                <?php  echo _('toggle capitalization');?>
            </button> 
            <button class="with-icon" onClick="greentext.toggleAlignment()" title="<?php  echo _('toggle alignment');?>">
                <div class="icon">
                    <img src="assets/icons/align-left.svg" alt="<?php  echo _('toggle alignment');?>" />
                </div>
                <?php  echo _('toggle alignment');?>
            </button>  
        </div>
    </section>


    <section class="selected_only">
        <h3><?php  echo _('Style');?></h3>

        <div class="">
            <button class="with-icon" onClick="greentext.sameWidth()" title="<?php  echo _('to front');?>">
                <div class="icon">
                    <img src="assets/icons/align-justify.svg" alt="<?php  echo _('to front');?>" />
                </div>
                <?php  echo _('same width');?>
            </button>
            <button class="with-icon" onClick="greentext.sameSize()" title="<?php  echo _('to front');?>">
                <div class="icon">
                    <img src="assets/icons/align-left.svg" alt="<?php  echo _('to front');?>" />
                </div>
                <?php  echo _('same size');?>
            </button>
    
        </div>
    </section>

    <section>  
        <?php
            $color = new stdClass();
            $color->value = "#ffffff";
            $color->id = "greentext_color";
            $color->oninput = "greentext.setFontColor(this.value)";
            $color->onclick = "greentext.setFontColor";
            require ("./src/Views/Components/Color.php"); 
        ?>
    </section>
    
    <?php $nodelete = true; require ("./src/Views/Components/ToFrontAndBack.php"); ?>

</section>

<script>
    class Greentext{
        setSize(input){
            cockpit.target.style.transform =  `scale(${input.value})`;
            undo.commit()
        }

        setFontColor(color) { 
            this.getSelectionParentElement().style.color = color
            //cockpit.target.style.color = color
            undo.commit()
        }

        toggleAlignment(){
            if( 'flex-start' === cockpit.target.style.alignItems){
                cockpit.target.style.alignItems = 'center';
            }else{
                cockpit.target.style.alignItems = 'flex-start';
            }
            undo.commit()
        }

        toogleCapitalization(){
            if( 'uppercase' === cockpit.target.style.textTransform){
                cockpit.target.style.textTransform = "none"; 
            }else{
               cockpit.target.style.textTransform = "uppercase"; 
            }
            undo.commit();
        }

        getSelectionParentElement() {
            const selection = window.getSelection();
            if (selection.rangeCount > 0) {
                const range = selection.getRangeAt(0);
                const parentElement = range.commonAncestorContainer.nodeType === 1
                    ? range.commonAncestorContainer
                    : range.commonAncestorContainer.parentNode;
                return parentElement;
            }
            return null;
        }

        sameWidth(){
            const lines = cockpit.target.querySelectorAll('p');
            let totalWidth = cockpit.target.offsetWidth + 1;
            lines.forEach(line => {
                console.log(line.offsetWidth)
                let i = 0;
                while(line.offsetWidth < totalWidth && i < 300){
                    line.style.fontSize = i + "px";
                    i++
                }
                
            }); 
        }

        sameSize(){
            const lines = cockpit.target.querySelectorAll('p');
            lines.forEach(line => {
                line.style.fontSize = "1em";
            }); 
        }
    }
    const greentext = new Greentext();
</script>
