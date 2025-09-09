<section class="mainsection" id="cockpit_greenaddtext">
    <h2><?php echo _( 'Text'); ?></h2>
    <section>
        <button onClick="component.add('greenaddtext')"><?php  echo _('Add text');?></button>
    </section>
    <section class="selected_only">
        <h4><?php echo _('Size'); ?></h4>
        <label>
            <input type="range" min="8" max="250" value="12" class="slider" step="0.5" id="greenaddtext_size"  oninput="greenaddtext.setSize(this)">
        </label>

        <h4><?php echo _('Font face'); ?></h4>
        <label>
            <input type="radio" name="greenaddtext_fontface" value="GrueneType"  oninput="greenaddtext.setFont(this)">
            GrueneType
        </label>
        <label>
            <input type="radio" name="greenaddtext_fontface" value="PT Sans"  oninput="greenaddtext.setFont(this)">
            PTSans
        </label>

        <h4><?php echo _('Font weight'); ?></h4>
        <label>
            <input type="radio" name="greenaddtext_fontweight" value="normal"  oninput="greenaddtext.setFontWeight(this)">
            <?php echo _('normal'); ?>
        </label>
        <label>
            <input type="radio" name="greenaddtext_fontweight" value="bold"  oninput="greenaddtext.setFontWeight(this)">
            <?php echo _('bold'); ?>
        </label>
    </section>

    <section class="selected_only">
        <h3><?php  echo _('Color');?></h3>

        <?php
            $color = new stdClass();
            $color->value = "#000000";
            $color->id = "greenaddtext_color";
            $color->oninput = "greenaddtext.setFontColor(this.value)";
            $color->onclick = "greenaddtext.setFontColor";
            require ("./src/Views/Components/Color.php"); 
        ?>
    </section>


    
<?php require ("./src/Views/Components/ToFrontAndBack.php"); ?>

</section>

<script>
    class Greenaddtext{
        setSize(input){
            cockpit.target.style.fontSize = input.value + 'px';
            undo.commit()
        }

        setFontColor(color) {      
            document.getElementById('greenaddtext_color').value = color
            cockpit.target.style.color = color
           
            undo.commit()
        }

        setFont(element){
            cockpit.target.style.fontFamily = element.value

            document.querySelectorAll('input[name="greenaddtext_fontweight"]').forEach((input) => {
                input.disabled = (element.value === 'GrueneType');
            });
            
            undo.commit()
        }

        setFontWeight(element){
            cockpit.target.style.fontWeight = element.value
            undo.commit()
        }
    }
    const greenaddtext = new Greenaddtext();
</script>



