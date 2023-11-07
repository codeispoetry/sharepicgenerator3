<section id="cockpit_sharepic">
    <h2>Sharepic</h2>
    <label>
        Breite:
        <input type="number" name="width" id="width" value="500" step="1">
    </label>
    <label>
        Höhe:
        <input type="number" name="height" id="height" value="400" step="1">
    </label>

    <label style="display:block;margin-top:20px">
        Hintergrundbild suchen:
        <input type="text" name="pixabay_q" id="pixabay_q" placeholder="Suchbegriff">
    </label>
</section>

<script>
document.getElementById('width').addEventListener('change', function() {
    document.getElementById('sharepic').style.width = this.value + 'px';

});
document.getElementById('height').addEventListener('change', function() {
    document.getElementById('sharepic').style.height = this.value + 'px';
});
</script>


