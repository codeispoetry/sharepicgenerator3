<section id="cockpit_eyecatcher">
    <h2>Störer</h2>
    <label>
        Größe:
        <input type="range" min="10" max="80" value="20" class="slider" id="eyecatcher_size">
    </label>
</section>

<script>
    document.getElementById('eyecatcher_size').addEventListener('input', function(event) {
        var element = event.target;

        const target = document.getElementById('eyecatcher');

        target.style.fontSize = element.value + "px";
    });

</script>


