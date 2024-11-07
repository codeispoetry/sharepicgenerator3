<section class="mainsection" id="cockpit_dimensions">
    <h2>
        <a href="#" onClick="ui.showTab('canvas');" style="text-decoration: none;">
            <
        </a>
        <?php  echo _('Change size');?></h2>
    <section>
        <h3><?php  echo _('Set dimensions manually');?></h3>
        <input type="number" name="width" id="width" max="6000" value="500" step="1" style="width: 25%;" onChange="sg.setSize()">
        x
        <input type="number" name="height" id="height" max="6000" value="400" step="1" style="width: 25%;" onChange="sg.setSize()">       
    </section>
    <section>  
        
    
        <?php
            $presets = json_decode( file_get_contents("templates/mint/dimensions.json" ) );

            foreach( $presets as $title => $entries ) {
                setTitle($title);

                foreach( $entries as $entry ){
                    setPreset( $entry );
                }
            }
         
        
        
        function setTitle($title) {
            echo '<h4>' . $title . '</h4>';
        }

        
        function setPreset( $entry ) {
            $pattern = '<button class="size-preset" onClick="sg.setPreset(\'%1$s\', \'%2$s\', %3$s,%4$s)">
                <div class="left format">%1$s</div>
                <div class="right">
                    <div class="title">%2$s</div>
                    <div class="description">%3$s x %4$s px</div>
                </div>
            </button>';

            printf($pattern, $entry->format, $entry->title, $entry->width, $entry->height);
        }

        ?>
       
       
    </section>

   

</section>
