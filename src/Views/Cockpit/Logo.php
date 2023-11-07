<section id="cockpit_logo">
    <h2>Logo</h2>
    <label>
        Größe:
        <input type="range" min="10" max="300" value="40" class="slider" id="logo_size">
    </label>

    <button class="to-front" data-target="logo">nach vorne</button>
    
</section>

<script>
    document.getElementById('logo_size').addEventListener('input', function(event) {
        var element = event.target;

        const target = document.getElementById('logo');

        target.style.width = element.value + "px";
    });

</script>


