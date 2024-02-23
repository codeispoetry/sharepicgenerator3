<section class="mainsection" id="cockpit_freetext">
    <h2><?php  echo _('Text');?></h2>
    <section>
        <p><?php echo _('For a text in your sharepic, edit it directly in the sharepic.');?></p>
        <button onClick="component.add('freetext')"><?php  echo _('Add text');?></button>
    </section>
    
    <section>
        <label class="horizontal">
            <h4><?php  echo _('Total size');?></h4>
            <input type="range" min="0" max="100" value="50" class="slider" id="text_size" oninput="freetext.setSize(this)">
        </label>
        <label style="display:flex;margin-top: 0">
            <button class="" onClick="freetext.toggleShadow(this)">
                <?php echo _("Toggle text shadow"); ?>
            </button>   
        </label>
    </section>
    <?php require ("./src/Views/Components/ToFrontAndBack.php"); ?>

</section>

<script>
    class Freetext{
        setSize(input){
            cockpit.target.style.fontSize = input.value + 'px';
        }

        toggleShadow(button){
            if(cockpit.target.style.textShadow){
                cockpit.target.style.textShadow = '';
            }else{
                cockpit.target.style.textShadow = '2px 2px 5px rgba(0,0,0,0.5)  ';
            }
            undo.commit()
        }
    }
    const freetext = new Freetext();
</script>
