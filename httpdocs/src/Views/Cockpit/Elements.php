<section class="mainsection" id="cockpit_elements">
    <h2><?php  echo _('Elements');?></h2>
    <section>
        <button onClick="component.add('elements')"><?php  echo _('Add element');?></button>
    </section>

    <section class="selected_only">  
        <h3><?php echo _('Elements');?></h3>
        <select id="elements_file" onchange="elements.setFile(this)">

        </select>
    </section>
  
    <section class="selected_only">  
        <h3><?php  echo _('Size');?></h3>
        <input type="range" min="50" max="1200" value="200" step="5" class="slider" id="elements_size" oninput="elements.setSize(this)">      
    </section>
    
    <?php require ("./src/Views/Components/ToFrontAndBack.php"); ?>
</section>

<script>
    class Elements{
        setSize(input){
            cockpit.target.getElementsByTagName('img')[0].style.width = input.value + 'px'
        }
        setFile(input){
            cockpit.target.querySelector('img').src = input.value
        }
    }
    const elements = new Elements();
</script>


