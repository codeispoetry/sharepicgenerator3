<section class="mainsection" id="cockpit_freetext">
    <h2>Freier Text</h2>
    <section>
        <h3>Größe</h3>
        <label>
            <input type="range" min="0" max="100" value="50" class="slider" id="text_size">
        </label>
    </section>
    <section>
        <h3>Text:</h3>
      



        <div id="editor"></div>


    </section>
    <section>
        <button class="to-front" data-target="text">nach vorne</button>
    </section>

</section>

<script>
    document.getElementById('text_size').addEventListener('input', function(e) {
        var element = event.target;
        const target = document.getElementById('text');
        target.style.fontSize = element.value + "px";
    });

    window.onload = function () {
        var quill = new Quill('#editor', {
            theme: 'snow'
        });
    }

</script>


