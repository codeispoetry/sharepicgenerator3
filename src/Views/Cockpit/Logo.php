<section class="mainsection" id="cockpit_logo">
    <h2><?php  echo _('Logo');?></h2>
    <section>
        <h3><?php  echo _('Size');?></h3>
        <label>
            <input type="range" min="10" max="2000" value="400" class="slider" id="logo_size">
        </label>
    </section>

    <section id="logo_colors">
        <h3><?php echo _('Colors');?></h3>
        <select id="logo_file">
            <option value="templates/de/logo.svg"><?php echo _('yellow'); ?></option>
            <option value="templates/de/logo-grashalm.svg"><?php echo _('green'); ?></option>
        </select>
    </section>

<?php $nodelete = true; require ("./src/Views/Components/ToFrontAndBack.php"); ?>
    
</section>

<script>
    document.getElementById('logo_size').addEventListener('input', function(event) {
        var element = event.target;

        const target = document.getElementById('logo');

        target.style.width = element.value + "px";
        target.style.height = element.value + "px";

    });

    document.getElementById('logo_file').addEventListener('change', function(event) {
        var element = event.target;

        const target = document.getElementById('logo');

        target.style.backgroundImage = "url(" + element.value + ")"
    });

</script>


