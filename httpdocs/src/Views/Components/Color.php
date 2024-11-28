<div class="color" id="<?php echo $color->id; ?>">   
    
    <div class="standard-palette">
        <?php
        $standard_colors = array(
            "#ffffff", "#000000", "#ff0000", "#00ff00", "#0000ff", "#ffff00", "#ff00ff", "#00ffff"
        );

        foreach($standard_colors as $standard_color){
            printf('<button 
                        class="no-button" 
                        style="background-color: %s" 
                        onClick="%s(this.style.backgroundColor);">
                    </button>', 
                    $standard_color,
                    $color->onclick
                );
            }
        ?>
       
    </div>

    <div style="margin-top:30px;display:flex;justify-content:space-between;width:100%">
        <div class="palette">
            <button 
                class="no-button" 
                data-blueprint="true" 
                style="background-color: #ffffff" 
                onClick="<?php echo $color->onclick; ?>(this.style.backgroundColor);">
            </button>
        </div>
        <div style="display:flex;flex-direction:column;justify-content:flex-end">
            <input type="color" 
                value="<?php echo $color->value; ?>" 
                class="" 
                id="<?php echo $color->id; ?>" 
                oninput="<?php echo $color->oninput; ?>">

            <button 
                class="colorpicker" 
                onclick="this.previousElementSibling.click();">
            </button>
        </div>
    </div>
</div>
