<section class="mainsection" id="cockpit_europe">
    <h2><?php  echo _('Europe');?></h2>
    <section>
        <button onClick="component.add('europeangreens')"><?php  echo _('Add "Member of European Greens"');?></button>
        <button onClick="component.add('machenwaszaehlt')"><?php  echo _('Add "Machen, was zählt"');?></button>

    </section>
  
    
    <?php require ("./src/Views/Components/ToFrontAndBack.php"); ?>
</section>

<script>
    class Europe{
        addElement(input){
            
        }
    }
    const europe = new Europe();
</script>


