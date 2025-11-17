<section class="mainsection" id="cockpit_textnrw">
    <h2>Überschrift</h2>
    <section>
        <h3><?php echo _('Total size'); ?></h3>
        <label>
            <input type="range" min="0" max="3" value="1" class="slider" step="0.05" id="greentext_size" oninput="greenclassictext.setSize(this)">
        </label>
    </section>
    
    <section>
        <h3><?php echo _('Appearance'); ?></h3>
        <div style="display: flex;font-size: 75%;margin-bottom: 0;justify-content:space-between;">
            <span><?php echo _('Line'); ?></span>
            <span><?php echo _('Size'); ?></span>
        </div>
        <?php for( $i = 1; $i <= 12; $i++ ) { ?>
        <div class="cockpit_textnrw" style="display: flex;justify-content:space-between;align-items:center;">
            <?php echo $i; ?></strong>
            <select class="linesize" onChange="greenclassictext.setLineSize(this, <?php echo $i; ?>)">
                <option value="s">klein</option>
                <option value="m">mittel</option>
                <option value="l">groß</option>
            </select>

           
        </div>
        <?php } ?>
    </section>


    <section class="selected_only" style="display: block;">
    <h3>Ausrichtung</h3>
        <div class="">
            <button class="with-icon" onclick="textnrw.align('start')" title="linksbündig">
                <div class="icon">
                    <img src="assets/icons/align-left.svg" alt="linksbündig">
                </div>
                linksbündig
            </button>
            <button class="with-icon" onclick="textnrw.align('center')" title="zentriert">
                <div class="icon">
                    <img src="assets/icons/align-center.svg" alt="zentriert">
                </div>
                zentriert
            </button>
        </div>
    </section>


    <?php $nodelete = true; require ("./src/Views/Components/ToFrontAndBack.php"); ?>

</section>

<script>
    class TextNRW{
        setSize(input){
            cockpit.target.style.transform =  `scale(${input.value})`;
            undo.commit()
        }


        setLineSize(input, lineNr) {  
            const line = document.querySelector('#greentext > div:nth-child(' + lineNr + ')' );
            if( line == null ) return
            line.classList.remove('s', 'm', 'l')
            line.classList.add(input.value)
        }

        align(align) {
            cockpit.target.style.alignItems = align;
            undo.commit();
        }
    }
    const textnrw = new TextNRW();
</script>