<section class="mainsection" id="cockpit_vorort">
    <h2><?php  echo _('Vor Ort');?></h2>
    <section>
        <h3><?php  echo _('Guest');?></h3>
        <select id="celebrity">
            <option><?php  echo _('please choose');?></option>
        <?php
        $celebs = parse_ini_file("templates/vorort/celebrities/celebrities.ini", true);
        foreach ($celebs as $image => $celeb) {
            printf('<option value="%s" data-info="%s">%s</option>', $image, $celeb['description'], $celeb['name']);
        }
        ?>
        </select>
    </section>
    <h2><?php  echo _('Text');?></h2>
    <section>
        <?php
            echo _('Please edit the text directly in the image.');
        ?>
    </section>
</section>

<script>
    document.getElementById('celebrity').addEventListener('change', function(event) {
        var element = event.target;

        const target = document.getElementById('celebrity');
        target.style.backgroundImage = "url(templates/vorort/celebrities/" + element.value + "?rand=1)"

        const info = element.options[element.selectedIndex].getAttribute('data-info');
        let parts = info.split("|");

        document.getElementById('title').innerHTML = parts[0];
        document.getElementById('name').innerHTML = parts[1];
        
        logger.log('vor ort: used ' + element.value )
    });

</script>


