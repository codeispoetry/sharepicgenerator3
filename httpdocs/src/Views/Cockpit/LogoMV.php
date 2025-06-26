<section class="mainsection" id="cockpit_logomv">
    <h2><?php  echo _('Logo');?></h2>
    <section class="selected_only">
        <h3><?php  echo _('Size');?></h3>
        <input type="range" min="0" max="500" value="50" class="slider" id="logo_size" oninput="logo.setSize(this)">
    </section>
        
    <section id="logomv_variants">
        <h3>Varianten</h3>
        <select id="logo_file" onchange="logomv.setFile(this.value)">
            <option value="templates/mv/logos/HRO.png">HRO</option><option value="templates/mv/logos/LRO.png">LRO</option><option value="templates/mv/logos/LUP.png">LUP</option><option value="templates/mv/logos/MSE.png">MSE</option><option value="templates/mv/logos/MV.svg">MV</option><option value="templates/mv/logos/NWM.png">NWM</option><option value="templates/mv/logos/SN.png">SN</option><option value="templates/mv/logos/VG.png">VG</option><option value="templates/mv/logos/VR.png">VR</option></select>
    </section>

</section>


<script>
    class LogoMV{
        setSize( input ) {
            cockpit.target.style.width = input.value + 'px';
            cockpit.target.style.height = input.value + 'px';
        }

        setFile( file ) {
            console.log(file)
            cockpit.target.style.backgroundImage = 'url(' + file + ')';
        }

    }
    const logomv = new LogoMV();
</script>

