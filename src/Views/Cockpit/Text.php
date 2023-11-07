<section id="cockpit_text">
    <h2>Text</h2>
    <label>
        Größe:
        <input type="range" min="0" max="100" value="50" class="slider" id="text_size">
    </label>
</section>

<script>
    document.getElementById('text_size').addEventListener('input', function(e) {
        var element = event.target;

        const target = document.getElementById('text1');

        target.style.fontSize = element.value + "px";
    });
</script>


