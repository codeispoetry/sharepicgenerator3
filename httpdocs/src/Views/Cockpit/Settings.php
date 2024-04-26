<section class="mainsection" id="cockpit_settings">
    <h2><?php  echo _('Settings');?></h2>
    <section>
        <?php
            echo _('Please select a color and add it to the palette.');
        ?>
        <div class="horizontal">
            <input type="color" id="addColor">
            <button id="addColor" onClick="settings.addColor(document.getElementById('addColor').value)">
                <?php echo _('Add color'); ?>
            </button>
        </div>
        <style>
            #settings_remove_color_picker {
                display: none;
            }
        </style>
        <?php
            echo _('By clicking on a color below, you can remove it.');
            $color = new stdClass(); 
            $color->value = "#ffffff";
            $color->id = "settings_remove_color_picker";
            $color->oninput = "settings.removeColor(this.value)";
            $color->onclick = "settings.removeColor";
            require ("./src/Views/Components/Color.php"); 
        ?>
    </section>
</section>

<script>

    class Settings {
        addColor ( color ) {
            if (config.palette.includes(color)) return

            config.palette.push(color)
            ui.addColorButton(color)
        }

        removeColor ( color ) {
            config.palette = config.palette.filter( c => c !== ui.rgbToHex(color) )
            ui.removeColorButton(color)
        }
    }

    const settings = new Settings();
</script>
