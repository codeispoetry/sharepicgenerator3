<section class="mainsection" id="cockpit_greentext">
    <h2>Text</h2>

    <section>
        <button onClick="component.add('greentext')"><?php  echo _('Add text');?></button>
    </section>

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
                    <img src="assets/icons/text.svg" alt="<?php  echo _('toggle capitalization');?>" />
                </div>
                <?php  echo _('toggle capitalization');?>
            </button> 
            <button class="with-icon" onClick="greentext.toggleAlignment()" title="<?php  echo _('toggle alignment');?>">
                <div class="icon">
                    <img src="assets/icons/text.svg" alt="<?php  echo _('toggle alignment');?>" />
                </div>
                <?php  echo _('toggle alignment');?>
            </button>  
            <button class="with-icon" onClick="greentext.toggleFontFamily()" title="<?php  echo _('toggle font family');?>">
                <div class="icon">
                    <img src="assets/icons/text.svg" alt="<?php  echo _('toggle font family');?>" />
                </div>
                <?php  echo _('toggle font family');?>
            </button>  
            <button class="with-icon" onClick="greentext.toggleStyle()" title="<?php  echo _('toggle style');?>">
                <div class="icon">
                    <img src="assets/icons/text.svg" alt="<?php  echo _('toggle style');?>" />
                </div>
                <?php  echo _('toggle style');?>
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

        toggleFontFamily(){
            if( 'GrueneType' === cockpit.target.style.fontFamily){
                cockpit.target.style.fontFamily = 'Gotham XNarrow';
            }else{
                cockpit.target.style.fontFamily = 'GrueneType';
            }
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

        toggleStyle(){
            if( cockpit.target.dataset.style === 'samewidth'){
                this.sameSize();
            }else{
                this.sameWidth();
            }
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
            cockpit.target.dataset.style = 'samewidth';
        }

        sameSize(){
            const lines = cockpit.target.querySelectorAll('p');
            lines.forEach(line => {
                line.style.fontSize = "1em";
            }); 
            cockpit.target.dataset.style = 'samesize';
        }
    }
    const greentext = new Greentext();

</script>
