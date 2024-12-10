<section class="mainsection" id="cockpit_greentext">
    <h2>Text</h2>
    <section>
        <h3><?php echo _('Total size'); ?></h3>
        <label>
            <input type="range" min="0" max="3" value="1" class="slider" step="0.05" id="greentext_size" oninput="greentext.setSize(this)">
        </label>
    </section>
    
    <?php $nodelete = true; require ("./src/Views/Components/ToFrontAndBack.php"); ?>

</section>

<script>
    class Greentext{
        setSize(input){
            cockpit.target.style.transform =  `scale(${input.value})`;
            undo.commit()
        }
    }
    const greentext = new Greentext();
</script>
