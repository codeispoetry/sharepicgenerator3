<section class="mainsection" id="cockpit_settings">
    <h2><?php  echo _('Settings');?></h2>
    <section>
        <input type="color" id="addColor">
        <button id="addColor" onClick="settings.addColor(document.getElementById('addColor').value)">
            <?php echo _('Add color'); ?>
        </button>

        <?php
                $color = new stdClass();
                $color->value = "#ffffff";
                $color->id = "eyecatcher_bgcolor";
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
