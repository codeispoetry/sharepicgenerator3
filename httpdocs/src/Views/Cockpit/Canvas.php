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
            <div> <img src="assets/icons/chevron-up.svg" style="rotate:90deg;"></div>
        </button>

    </section> 

    <section class="no-greens">  
        <?php
            $color = new stdClass();
            $color->value = "#ffffff";
            
            if( $this->env->config->get( 'Main', 'tenant' ) ==='greens' ){
                $color->colorset= ["#000000","#ffffff","#005437","#008939","#8abd24","#f5f1e9","#0ba1dd","#fff17a"];
            }
            
            $color->id = "background_color";
            $color->oninput = "background.color(this.value)";
            $color->onclick = "background.color";
            require ("./src/Views/Components/Color.php"); 
        ?>
    </section>
</section>
