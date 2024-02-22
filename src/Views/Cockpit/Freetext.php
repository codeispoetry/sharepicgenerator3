<section class="mainsection" id="cockpit_freetext">
    <h2><?php  echo _('Text');?></h2>
    <section>
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
    <section class="row">
        <button class="to-front" onClick="component.toFront(this)" title="<?php  echo _('to front');?>"><?php  echo _('to front');?></button>
        <button class="to-back" onClick="component.toBack(this)" title="<?php  echo _('to back');?>"><?php  echo _('to back');?></button>
    </section>

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
