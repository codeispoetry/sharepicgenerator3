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


        <h4>Textvariante</h4>
        <label>
            <input type="radio" name="greenNDStext_style" value="sand-tanne"  oninput="greenNDStext.setStyle(this.value)">
            sand/tanne
        </label>
        <label>
            <input type="radio" name="greenNDStext_style" value="sand-gras"  oninput="greenNDStext.setStyle(this.value)">
            sand/gras
        </label>
        <label>
            <input type="radio" name="greenNDStext_style" value="tanne-gras"  oninput="greenNDStext.setStyle(this.value)">
            tanne/gras
        </label>
        <label>
            <input type="radio" name="greenNDStext_style" value="straight"  oninput="greenNDStext.setStyle(this.value)">
            gerade
        </label>
         <label>
            <input type="radio" name="greenNDStext_style" value="normal"  oninput="greenNDStext.setStyle(this.value)">
            Fließtext
        </label>

       
    </section>
    
<?php require ("./src/Views/Components/ToFrontAndBack.php"); ?>

<section class="selected_only btn_delete">
    <button class="delete" onClick="component.delete()" title="<?php  echo _('delete');?>">
        <?php  
        echo $delete_button_text . ' ' .  _('delete');
        $delete_button_text = '';
        ?>
    </button>
</section>

</section>

<script>
    class GreenNDStext{
        setSize(input){
            cockpit.target.style.fontSize = input.value + 'px';
            document.getElementById('greenNDStext_size').value = input.value
            document.getElementById('greenNDStext_size_number').value = input.value
            undo.commit()
        }

        setStyle(cssclass) {      
            
            cockpit.target.classList.remove('sand-tanne', 'sand-gras', 'tanne-gras', 'straight', 'normal')
            cockpit.target.classList.add(cssclass)
           
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

  

      
    }
    const greenNDStext = new GreenNDStext();
</script>



