<section class="mainsection" id="cockpit_vorort">
    <h2><?php  echo _('Vor Ort');?></h2>
    <section>
        <h3>Gast</h3>
        <select id="celebrity">
            <option>bitte wählen</option>
        <?php
        $celebs = parse_ini_file("tenants/vorort/celebrities/celebrities.ini", true);
        foreach ($celebs as $image => $celeb) {
            printf('<option value="%s" data-info="%s">%s</option>', $image, $celeb['description'], $celeb['name']);
        }
        ?>
        </select>
      
    </section>
</section>

<script>
    document.getElementById('celebrity').addEventListener('change', function(event) {
        var element = event.target;

        const target = document.getElementById('celebrity');
        target.style.backgroundImage = "url(tenants/vorort/celebrities/" + element.value + "?rand=1)"

        const info = element.options[element.selectedIndex].getAttribute('data-info');
        let parts = info.split("|");

        document.getElementById('title').innerHTML = parts[0];
        document.getElementById('name').innerHTML = parts[1];       
    });

</script>


