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
            ?>
                <label class="line_indent show" id="line_indent_<?php echo $i;?>">Zeile <?php echo $i+1;?>:
                    <input type="range" value="0" class="slider" min="-100" max="200" data-i="<?php echo $i;?>">
                </label>
               <select id="line_layout_<?php echo $i;?>" data-i="<?php echo $i;?>" class="line_layout show">
                   <option value="sandtanne">sand/tanne</option>
                   <option value="tannesand">tanne/sand</option>

                   <option value="sandklee">sand/klee</option>
                   <option value="kleesand">klee/sand</option>

                   <option value="grastanne">gras/tanne</option>
                   <option value="tannegras">tanne/gras</option>
                </select>
            <?php
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

    const sliders = document.querySelectorAll('.line_indent input');
    sliders.forEach(slider => {
        slider.addEventListener('input', function(e) {
            const allP = document.querySelectorAll('#text p');
            const i = e.target.dataset.i;
            allP[i].style.marginLeft = e.target.value + 'px';
        });
    });

    const layouts = document.querySelectorAll('.line_layout');
    layouts.forEach(layout => {
        layout.addEventListener('change', function(e) {
            const allP = document.querySelectorAll('#text p');
            const i = e.target.dataset.i;
            allP[i].classList.remove('sandtanne', 'tannesand','sandklee', 'kleetand', 'grastanne', 'tannegras');
            allP[i].classList.add(e.target.value)
        });
    });
</script>


