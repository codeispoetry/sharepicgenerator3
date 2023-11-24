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
    
    <section style="margin-top:4em">
        <h3>Hintergrundbild</h3>
        <label style="display:flex; margin-top:20px">
            <input type="text" style="width:100%;" name="pixabay_q" id="pixabay_q" placeholder="Suchbegriff">
            <button data-click="pixabay.search" style="padding:2px;"><img src="/assets/icons/search.svg"></button>
        </label>
        

        <label style="display:flex;padding:3px;margin-top:2em" class="file-upload">
            <img src="/assets/icons/upload.svg"> hochladen
            <input type="file" name="upload" id="upload">
        </label>

        <label>
            <input style="margin-top:20px" type="radio" name="background" checked data-click="sg.reset_background">
            Hintergrundbild einpassen
        </label>
        <label>
            <input type="radio" name="background" data-click="sg.make_background_adjustable">
            Hintergrundbild anpassen
        </label>
    </section>
    
</section>



