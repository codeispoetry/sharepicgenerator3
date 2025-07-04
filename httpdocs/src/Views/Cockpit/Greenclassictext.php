<section class="mainsection" id="cockpit_greenclassictext">
    <h2>Text Classic</h2>
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
            <span class="no-nrw"><?php echo _('Colorset'); ?></span>
            <span><?php echo _('Size'); ?></span>
            <span class="no-nrw"><?php echo _('Indent'); ?></span>
        </div>
        <?php for( $i = 1; $i <= 12; $i++ ) { ?>
        <div class="cockpit_greentext">
            <?php echo $i; ?></strong>
            <select class="linecolor" onChange="greenclassictext.setLineColorset(this, <?php echo $i; ?>)">
                <option value="tannesand"><?php echo _('tanne/sand'); ?></option>
                <option value="sandtanne"><?php echo _('sand/tanne'); ?></option>

                <option value="kleesand"><?php echo _('klee/sand'); ?></option>
                <option value="sandklee"><?php echo _('sand/klee'); ?></option>

                <option value="grastanne"><?php echo _('gras/tanne'); ?></option>
                <option value="tannegras"><?php echo _('tanne/gras'); ?></option>
            </select>
            <select class="linesize" onChange="greenclassictext.setLineSize(this, <?php echo $i; ?>)">
                <option value="s"><?php echo _('S'); ?></option>
                <option value="m"><?php echo _('M'); ?></option>
                <option value="l"><?php echo _('L'); ?></option>
            </select>
        
            <input type="range" class="lineindent" min="-300" max="300" value="0" oninput="greenclassictext.setLineIndent(this, <?php echo $i; ?>)">
           
        </div>
        <?php } ?>
    </section>
    <?php $nodelete = true; require ("./src/Views/Components/ToFrontAndBack.php"); ?>

</section>

<script>
    class Greenclassictext{
        setSize(input){
            cockpit.target.style.transform =  `scale(${input.value})`;
            undo.commit()
        }

        setLineIndent(input, lineNr) {      
            const line = document.querySelector('#greentext > div:nth-child(' + lineNr + ')' )
            if( line == null ) return
            line.style.marginLeft = input.value + 'px'
        }

        setLineSize(input, lineNr) {  
            const line = document.querySelector('#greentext > div:nth-child(' + lineNr + ')' );
            if( line == null ) return
            line.classList.remove('s', 'm', 'l')
            line.classList.add(input.value)
        }

        setLineColorset(input, lineNr) {  
            const line = document.querySelector('#greentext > div:nth-child(' + lineNr + ')' );
            if( line == null ) return
            line.classList.remove('tannesand', 'sandtanne', 'kleesand', 'sandklee', 'grastanne', 'tannegras')
            line.classList.add(input.value)
        }
    }
    const greenclassictext = new Greenclassictext();
</script>