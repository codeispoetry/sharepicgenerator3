<section class="mainsection" id="cockpit_copyright">
    <h2><?php  echo _('Copyright');?></h2>
    
    <section>
        <h3><?php  echo _('Size');?></h3>
        <input type="range" min="10" max="50" value="20" class="slider" id="copyright_size" oninput="copyright.setSize(this)">
    </section>

    <section>
        <h3><?php  echo _('Color');?></h3>
        <input type="color" value="#ffffff" class="" id="copyright_color" oninput="copyright.setFontColor(this)">
    
        <?php require ("./src/Views/Components/ToFrontAndBack.php"); ?>
    </section>
</section>

<script>
    class Copyright{
        setSize(input){
            document.getElementById('copyright').style.fontSize = input.value + 'px';
            undo.commit()
        }

        setFontColor(input) {      
            document.getElementById('copyright').style.color = input.value;
            undo.commit()
        }
    }
    const copyright = new Copyright();
</script>




