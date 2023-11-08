<section class="mainsection" id="cockpit_text">
    <h2>Text</h2>
    <section>
        <h3>Größe</h3>
        <label>
            <input type="range" min="0" max="100" value="50" class="slider" id="text_size">
        </label>
    </section>
    <section>
        <h3>Einrückungen:</h3>
        <div style="display: flex;flex-direction: column;align-items: flex-start;">
            <?php
            for($i = 0; $i < 10; $i++) {

                printf('<label class="text_indent show" id="text_indent_%d">Zeile %d:', $i, $i + 1);
                printf( '<input type="range" value="0" class="slider" min="-100" max="200" data-i="%d">', $i);
                echo '</label>';
            }
            ?>
        </div>
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

    const sliders = document.querySelectorAll('.text_indent input');
    sliders.forEach(slider => {
        slider.addEventListener('input', function(e) {
            const allP = document.querySelectorAll('#text p');
            const i = e.target.dataset.i;
            allP[i].style.marginLeft = e.target.value + 'px';
        });
    });
</script>


