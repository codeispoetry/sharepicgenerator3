<section class="mainsection" id="cockpit_canvas">
    <h2><?php  echo _('Canvas');?></h2>

    <section>
        <h3><?php  echo _('Size');?></h3>
        <button onClick="ui.showTab('dimensions')" id="size-preset" class="size-preset">
            <div class="left format"></div>
            <div class="right">
                <div class="title"></div>
                <div class="description"></div>
            </div>
            <div> >&nbsp;</div>
        </button>

    </section> 

    <section>  
        <?php
            $color = new stdClass();
            $color->value = "#ffffff";
            $color->id = "background_color";
            $color->oninput = "background.color(this.value)";
            $color->onclick = "background.color";
            require ("./src/Views/Components/Color.php"); 
        ?>
    </section>
</section>
