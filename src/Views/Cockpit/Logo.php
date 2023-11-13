<section class="mainsection" id="cockpit_logo">
    <h2>Logo</h2>
    <section>
        <h3>Größe</h3>
        <label>
            <input type="range" min="10" max="2000" value="400" class="slider" id="logo_size">
        </label>
    </section>

    <section>
        <h3>Farbe</h3>
        <select id="logo_file">
            <option value="/tenants/de/logo.svg">gelb</option>
            <option value="/tenants/de/logo-grashalm.svg">grün</option>
        </select>
    </section>

    <section>
        <button class="to-front" data-target="logo">nach vorne</button>
    </section>
    
</section>

<script>
    document.getElementById('logo_size').addEventListener('input', function(event) {
        var element = event.target;

        const target = document.getElementById('logo');

        target.style.width = element.value + "px";
    });

    document.getElementById('logo_file').addEventListener('change', function(event) {
        var element = event.target;

        const target = document.getElementById('logo');

        target.style.backgroundImage = "url(" + element.value + ")"
    });

</script>


