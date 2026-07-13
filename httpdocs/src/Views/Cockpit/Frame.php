<section class="mainsection" id="cockpit_frame">
    <h2>
        <?php  echo _('Edit frame');?>
    </h2>

    <section>
        <h3>Rahmen</h3>
       
        <div style="display: flex; flex-direction: column; gap: 10px;">
            <label>
                <input type="radio" name="frame_thickness" value="0" oninput="frame.setThickness(0)" checked> ohne Rahmen
            </label>
            <label>
               <input type="radio" name="frame_thickness" value="1" oninput="frame.setThickness(30)"> mit Rahmen
            </label>
        </div> 
    </section>

    <section>
        <h3>Machs's grün</h3>
       
        <div style="display: flex; flex-direction: column; gap: 10px;">
            <label>
                <input type="radio" name="mg_settings" value="0" oninput="mg.set(0)" checked> verbergen
            </label>
            <label>
                <input type="radio" name="mg_settings" value="1" oninput="mg.set(1)"> anzeigen
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

            if(config.template === 'templates/nds/start.html') {
                ndsClaim.setBgColor('#1c302a');
                const bgColor = document.getElementById('background').style.backgroundColor;
                if(bgColor+"" == 'rgb(138, 189, 36)') {
                    document.getElementById('background').style.backgroundColor ='#1c302a';
                } 

            }
        }

      
       
    }
    const frame = new Frame();

    class MG{

        set(value){
            const element = document.getElementById("claim");
            element.style.display = value === 1 ? 'block' : 'none';
            undo.commit()
        }

    }
    const mg = new MG();
</script>
