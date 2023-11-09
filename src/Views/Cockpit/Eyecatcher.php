<section class="mainsection" id="cockpit_eyecatcher">
    <h2>Störer</h2>
    <section>
        <h3>Größe</h3>
        <label>
            <input type="range" min="10" max="80" value="20" class="slider" id="eyecatcher_size">
        </label>
    </section>
    
    <section>
        <button class="to-front" data-target="eyecatcher">nach vorne</button>
        <button class="delete" data-target="eyecatcher">löschen</button>
    </section>
</section>

<script>
    document.getElementById('eyecatcher_size').addEventListener('input', function(event) {
        var element = event.target;

        const target = document.getElementById('eyecatcher');

        target.style.fontSize = element.value + "px";
    });
</script>


