<section class="mainsection" id="cockpit_settings">
    <h2><?php  echo _('Design settings');?></h2>
    <section>
        <?php
            echo _('Please select a color and add it to the palette.');
        ?>

        <h3 style="margin-top:30px;"><?php echo _('Add color');?></h3>
        <div class="info">
        <img src="/assets/icons/info.svg" alt="info">
        <?php
            echo _('Select a color and add it to the palette.');
            echo _('To remove a color, click on the color in the palette.');
        ?>
        </div>
        <div style="display:flex;flex-direction:row;justify-content:space-between;width:100%" class="color">
            <div class="palette add-color">
                <button 
                    class="no-button" 
                    data-blueprint="true" 
                    style="background-color: #ffffff" 
                    title="<?php echo _('Remove color');?>"
                    onClick="settings.removeColor(this.style.backgroundColor);">
                </button>
            </div>
            <div style="display:flex;flex-direction:column;justify-content:flex-end">
                <input type="color" 
                    value="#FFFFFF" 
                    class="" 
                    id="settings_remove_color_picker" 
                    onchange="settings.addColor(this.value)">

                <button 
                    class="colorpicker" 
                    title="<?php echo _('Add color');?>"
                    onclick="this.previousElementSibling.click();">
                </button>
            </div>
     </div>

        
    </section>
</section>
