<section class="mainsection" id="cockpit_logo">
    <h2><?php  echo _('Logo');?></h2>
    <section>
        <h3><?php  echo _('Size');?></h3>
        <label>
            <input type="range" min="10" max="2000" value="400" class="slider" id="logo_size" oninput="logo.setSize(this)">
        </label>
    </section>

    <section id="logo_colors">
        <h3><?php echo _('Colors');?></h3>
        <select id="logo_file" onchange="logo.setFile(this)">
            <option value="templates/de/logo.svg"><?php echo _('yellow'); ?></option>
            <option value="templates/de/logo-grashalm.svg"><?php echo _('green'); ?></option>
        </select>
    </section>

<?php $nodelete = true; require ("./src/Views/Components/ToFrontAndBack.php"); ?>
    
</section>

<script>
    class Logo{
        setSize(input){
            cockpit.target.style.width = input.value + 'px';
        }

        setFile(input){
            cockpit.target.style.backgroundImage = "url(" + input.value + ")";
        }
    }
    const logo = new Logo();

</script>


