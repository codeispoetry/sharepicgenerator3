<section class="mainsection" id="cockpit_bwtext">
    <h2><?php  echo _('Text');?></h2>
    <section>
        <button onClick="component.add('bwtext')"><?php  echo _('Add text');?></button>
        <button class="outline" onClick="rtebw.deinit();"><?php  echo _('Close text editor');?></button>
    </section> 

        <section> 
        <div style="">
            <section class="selected_only">
                <h3><?php  echo _('Size');?></h3>
                <input type="range" min="10" max="100" value="20" class="slider" id="text_size" oninput="bwtext.setSize(this)">      
            </section>
        </div>
    </section>
   

    <?php 
        $delete_button_text = _('Text');
        require ("./src/Views/Components/ToFrontAndBack.php"); 
    ?>
</section>

<script>
    class BWText{
        setSize(input){
            cockpit.target.style.fontSize = input.value + 'px';
        }
    }
    const bwtext = new BWText();
</script>