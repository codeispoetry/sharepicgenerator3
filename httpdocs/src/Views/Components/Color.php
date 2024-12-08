<div class="color" id="<?php echo $color->id; ?>">   
    
    <h3><?php echo _('Colors');?></h3>
    <div class="standard-palette">
        <?php
        $standard_colors = array(
            "#000000","#ffffff","#BFC6D3","#FC5555","#FFBDF2","#FF9900","#FFDA46",
            "#5A4101","#9D7265","#7DC605","#66CBAF","#5F94F9","#7B61FF"
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

    <h3 style="margin-top:30px;"><?php echo _('My colors');?></h3>
    <div style="display:flex;justify-content:space-between;width:100%">
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

    <div onclick="ui.showTab('settings')">
        <?php
            printf(_("Colors can be edited in settings %s."), '<img src="assets/icons/settings.svg">');
        ?>
    </div>
</div>
