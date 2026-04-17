<section class="mainsection" id="cockpit_frame">
    <h2>
        <?php  echo _('Edit frame');?>
    </h2>

    <section>
        <h3><?php  echo _('Frame thickness');?></h3>
       
        <div style="display: flex; flex-direction: column; gap: 10px;">
            <label>
                <input type="radio" name="frame_thickness" value="0" oninput="frame.setThickness(0)" checked> <?php  echo _('None');?>
            </label>
            <label>
                <input type="radio" name="frame_thickness" value="1" oninput="frame.setThickness(30)"> <?php  echo _('With frame');?>
            </label>

        </div> 
       
    </section>
   
    
    
</section>

<script>
    class Frame{

        setThickness(size){
            const element = document.getElementById("background");
            element.style.borderWidth = size + 'px';
            element.style.borderBottomWidth = ( 3 * size ) + 'px';
            undo.commit()
        }

      
       
    }
    const frame = new Frame();
</script>
