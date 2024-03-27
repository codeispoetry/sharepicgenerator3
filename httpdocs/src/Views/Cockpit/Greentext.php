<section class="mainsection" id="cockpit_greentext">
    <h2>Text</h2>
    <section>
        <h3><?php echo _('Total size'); ?></h3>
        <label>
            <input type="range" min="0" max="3" value="1" class="slider" step="0.05" id="greentext_size">
        </label>
    </section>
    
    <?php $nodelete = true; require ("./src/Views/Components/ToFrontAndBack.php"); ?>

    <section>
        Du kannst den Text direkt in der Vorschau bearbeiten.
        Klicke ihn mit der <u>rechten Maustaste</u> an und wähle dann die Größe und Farbe.
        Zudem kannst du den Text dann auch direkt verschieben.
    </section>
</section>

<script>
    document.getElementById('greentext_size').addEventListener('input', function(e) {
        var element = event.target;
        const target = document.getElementById('greentext');
        target.style.transform = `scale(${element.value})`;
    });
</script>



