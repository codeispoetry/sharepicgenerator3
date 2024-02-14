<section class="mainsection" id="cockpit_freetext">
    <h2><?php  echo _('Text');?></h2>
    <section>
        <button onClick="component.add('freetext')"><?php  echo _('Add text');?></button>
    </section>
    <section>
        <h3><?php  echo _('Total size');?></h3>
        <label>
            <input type="range" min="0" max="100" value="50" class="slider" id="text_size" oninput="freetext.setSize(this)">
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
    }
    const freetext = new Freetext();
</script>
