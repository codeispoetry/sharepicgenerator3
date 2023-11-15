<section class="mainsection" id="cockpit_sharepic">
    <h2>Sharepic</h2>
    <section>
        <h3>Abmessungen</h3>
            <div style="display: flex">
                <input type="number" name="width" id="width" value="500" step="1" style="width: 25%" data-change="sg.set_size">
                <span style="margin: 0 0.5em">x</span>
                <input type="number" name="height" id="height" value="400" step="1" style="width: 25%" data-change="sg.set_size">
                <span style="margin-left: 0.5em">Pixel</span>
            </div>
        </label>
    </section>
    
    <section>
        <h3>Hintergrundbild</h3>
        <label style="display:block;margin-top:20px">
            <input type="text" name="pixabay_q" id="pixabay_q" placeholder="Suchbegriff">
        </label>

        <label style="display:block;margin-top:20px" class="file-upload">
           hochladen
            <input type="file" name="upload" id="upload">
        </label>

        <button data-click="sg.reset_background" style="margin-top:20px">Hintergrundbild zurücksetzen</button>
    </section>
    
</section>



