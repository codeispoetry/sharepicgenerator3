<section class="mainsection" id="cockpit_greenaddtext">
    <h2><?php echo _( 'Additional text'); ?></h2>
    <section>
        <button onClick="component.add('greenaddtext')"><?php  echo _('Add text');?></button>
    </section>
    <section class="selected_only">
        <h3><?php echo _('Total size'); ?></h3>
        <label>
            <input type="range" min="8" max="30" value="12" class="slider" step="0.5" id="greenaddtext_size"  oninput="greenaddtext.setSize(this)">
        </label>
    </section>

    <section class="selected_only">
        <h3><?php  echo _('Color');?></h3>
        <input type="color" value="#000000" class="" id="greenaddtext_color" oninput="greenaddtext.setFontColor(this)">
    </section>
    
<?php require ("./src/Views/Components/ToFrontAndBack.php"); ?>

</section>

<script>
    class Greenaddtext{
        setSize(input){
            cockpit.target.style.fontSize = input.value + 'px';
            undo.commit()
        }

        setFontColor(input) {      
            cockpit.target.style.color = input.value;
           
            undo.commit()
        }
    }
    const greenaddtext = new Greenaddtext();
</script>



