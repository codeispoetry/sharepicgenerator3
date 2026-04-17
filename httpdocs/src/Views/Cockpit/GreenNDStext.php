<section class="mainsection" id="cockpit_greenNDStext">
    <h2><?php echo _( 'Text'); ?> NDS</h2>
    <section>
        <button onClick="component.add('greenNDStext')"><?php  echo _('Add text');?></button>
    </section>
    <section class="selected_only">
        <h4><?php echo _('Size'); ?></h4>
        <label class="rangeandnumber">
            <input type="range" min="8" max="250" value="12" class="slider" step="0.5" id="greenNDStext_size"  oninput="greenNDStext.setSize(this)">
            <input type="number" min="8" max="250" value="12" step="0.5" id="greenNDStext_size_number" oninput="greenNDStext.setSize(this)">
        </label>

        <h4><?php echo _('Position'); ?></h4>
        <div style="display: flex; gap: 10px; align-items: center;">
           <input type="number" id="greenNDStext_x" name="greenNDStext_x" value="0" oninput="greenNDStext.setX(this)" style="width: 70px;">:<input type="number" id="greenNDStext_y" name="greenNDStext_y" value="0" oninput="greenNDStext.setY(this)" style="width: 70px;">
        </div>

        <h4><?php echo _('Font face'); ?></h4>
        <label>
            <input type="radio" name="greenNDStext_fontface" value="GrueneType"  oninput="greenNDStext.setFont(this)">
            GrueneType
        </label>
        <label>
            <input type="radio" name="greenNDStext_fontface" value="PT Sans"  oninput="greenNDStext.setFont(this)">
            PTSans
        </label>

        <h4><?php echo _('Font weight'); ?></h4>
        <label>
            <input type="radio" name="greenNDStext_fontweight" value="normal"  oninput="greenNDStext.setFontWeight(this)">
            <?php echo _('normal'); ?>
        </label>
        <label>
            <input type="radio" name="greenNDStext_fontweight" value="bold"  oninput="greenNDStext.setFontWeight(this)">
            <?php echo _('bold'); ?>
        </label>

        <h4>Hervorheben</h4>
        Um ein Wort zu betonen, klicke im Vorschaubereich doppelt darauf und wähle dann <br>
        
        <div style="">
            <button onclick="greenNDStext.setEmphasis(this)" class="btn-small">
                Markierten Text hervorheben
            </button>
            <button class="delete btn-small" onclick="greenNDStext.deleteEmphasis()">
                Alle Hervorhebungen entfernen
            </button>
        </div>
    </section>

    <section class="selected_only">
        <h3><?php  echo _('Color');?></h3>

        <?php
            $color = new stdClass();
            $color->value = "#000000";
            $color->id = "greenNDStext_color";
            $color->oninput = "greenNDStext.setFontColor(this.value)";
            $color->onclick = "greenNDStext.setFontColor";
            require ("./src/Views/Components/Color.php"); 
        ?>
    </section>


    
<?php require ("./src/Views/Components/ToFrontAndBack.php"); ?>

</section>

<script>
    class GreenNDStext{
        setSize(input){
            cockpit.target.style.fontSize = input.value + 'px';
            document.getElementById('greenNDStext_size').value = input.value
            document.getElementById('greenNDStext_size_number').value = input.value
            undo.commit()
        }

        setFontColor(color) {      
            document.getElementById('greenNDStext_color').value = color
            cockpit.target.style.color = color
           
            undo.commit()
        }

        setFont(element){
            cockpit.target.style.fontFamily = element.value

            document.querySelectorAll('input[name="greenNDStext_fontweight"]').forEach((input) => {
                input.disabled = (element.value === 'GrueneType');
            });
            
            undo.commit()
        }

        setFontWeight(element){
            cockpit.target.style.fontWeight = element.value
            undo.commit()
        }

        setX(element){
            const x = parseInt(element.value, 10)
            cockpit.target.style.left = `${x}px`
            undo.commit()
        }

        setY(element){
            const y = parseInt(element.value, 10)
            cockpit.target.style.top = `${y}px`
            undo.commit()
        }

        deleteEmphasis(){
            cockpit.target.innerHTML = '<div>' + cockpit.target.innerText + '</div>';

            undo.commit();
        }

        setEmphasis(element){
            const selection = window.getSelection();

            if (selection.rangeCount == 0) {
                alert("Bitte doppelklicke zuerst einen Text im Vorschaubereich, um ihn hervorzuheben.");
                return;
            }

            const selectedText = selection.toString();
            if (!selectedText) {
                return;
            }

            // Apply emphasis to selected text
            const range = selection.getRangeAt(0);
            // toDO in der folenden Zeile
            range.setEnd(element, element.childNodes.length);

            const emph = document.createElement('span');
            
            emph.style.color = 'red';
            emph.classList.add('emphasis');
            
            try {
                range.surroundContents(emph);
            } catch (e) {
                // Fallback if range spans multiple elements
                emph.innerHTML = range.extractContents();
                range.insertNode(emph);
            }
            
            selection.removeAllRanges();
    
            undo.commit();
        }

    }
    const greenNDStext = new GreenNDStext();
</script>



