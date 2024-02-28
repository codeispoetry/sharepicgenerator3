<section class="mainsection" id="cockpit_copyright">
    <h2><?php  echo _('Copyright');?></h2>

    <section>
        <button onClick="component.add('copyright')" id="add_copyright"><?php  echo _('Add copyright');?></button>
    </section>
    
    <section class="selected_only">
        <h3><?php  echo _('Size');?></h3>
        <input type="range" min="10" max="50" value="20" class="slider" id="copyright_size" oninput="copyright.setSize(this)">
    </section>

    <section class="selected_only">
        <h3><?php  echo _('Color');?></h3>
        <input type="color" value="#ffffff" class="" id="copyright_color" oninput="copyright.setFontColor(this)">
    </section>

    <?php require ("./src/Views/Components/ToFrontAndBack.php"); ?>
</section>

<script>
    class Copyright{
        setSize(input){
            cockpit.target.style.fontSize = input.value + 'px';
            undo.commit()
        }

        setFontColor(input) {      
            cockpit.target.style.color = input.value;
            undo.commit()
        }
    }
    const copyright = new Copyright();
</script>




