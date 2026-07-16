<section class="mainsection" id="cockpit_greenNDStext">
    <h2><?php echo _( 'Text'); ?></h2>
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
        <select name="greenNDStext_style" id="greenNDStext_style" oninput="greenNDStext.setStyle()">
            <option value="sand/tanne"  data-color="#f5f1e9" data-bg="#005538">sand/tanne</option>
            <option value="tanne/sand" data-color="#005538" data-bg="#f5f1e9">tanne/sand</option>
            <option value="tanne/gras" data-color="#005538" data-bg="#8ABD24">tanne/gras</option>
            <option value="sand/klee" data-color="#f5f1e9" data-bg="#008939">sand/klee</option>
            <option value="klee/sand" data-color="#008939" data-bg="#f5f1e9">klee/sand</option>

            <option value="sand/tanne_gerade" data-color="#f5f1e9" data-bg="#005538" data-skew="0">sand/tanne gerade</option>
            <option value="sand/magenta_gerade" data-color="#f5f1e9" data-bg="#e61981" data-skew="0">sand/magenta gerade</option>

            <option value="ohne_hintergrund_sand" data-color="#f5f1e9" data-bg="transparent" data-skew="0" data-font="PT Sans">ohne Hintergrund, sand</option>
        </select>
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

        setStyle() {
            const select = document.getElementById('greenNDStext_style');
            const selectedOption = select.options[select.selectedIndex];
            const color = selectedOption.getAttribute('data-color');
            const bg = selectedOption.getAttribute('data-bg');
            const font = selectedOption.getAttribute('data-font') ;

            if(selectedOption.hasAttribute('data-skew')) {
                cockpit.target.style.transform = `skewY(${selectedOption.getAttribute('data-skew')}deg)`;
            } else {
                cockpit.target.style.transform = 'skewY(-12deg)';
            }
            
            cockpit.target.querySelectorAll('div').forEach(div => {
                div.style.color = color;
                div.style.backgroundColor = bg;
                div.style.fontFamily = font;
            });

            cockpit.target.dataset.style = select.value;

            undo.commit();
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
