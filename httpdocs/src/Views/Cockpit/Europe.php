<section class="mainsection" id="cockpit_europe">
    <h2><?php  echo _('Europe');?></h2>
    <section>
        <button onClick="component.add('machenwaszaehlt')"><?php  echo _('Add "Machen, was zählt"');?></button>
    </section>
  
    <section class="selected_only">  
        <h3><?php  echo _('Size');?></h3>
        <input type="range" min="50" max="600" value="200" step="5" class="slider" id="europe_size" oninput="europe.setSize(this)">      
    </section>
    
    <?php require ("./src/Views/Components/ToFrontAndBack.php"); ?>
</section>

<script>
    class Europe{
        setSize(input){
            cockpit.target.getElementsByTagName('img')[0].style.width = input.value + 'px'
        }

    }
    const europe = new Europe();
</script>


