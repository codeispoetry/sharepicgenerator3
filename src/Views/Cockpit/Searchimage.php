<section class="mainsection" id="cockpit_search">
    <h2><?php  echo _('Background image');?></h2>
    <section>
        <label style="display:flex; margin-top:20px;">
            <input type="text" style="width:100%;" name="pixabay_q" id="pixabay_q" placeholder="<?php  echo _('search in image database');?>">
            <button onClick="pixabay.search()" style="padding:2px;"><img src="assets/icons/search.svg"></button>
        </label>
    </section>
    
    <section>
        <label style="display:flex; align-items: center;padding:3px;margin-top:1em; width: 160px;" class="file-upload">
            <img src="assets/icons/upload.svg"> <?php  echo _('upload own image');?>
            <input type="file" name="upload" id="upload" onChange="api.upload(this)">
        </label>
    </section>

    <section>
        <h3><?php  echo _('Create image with AI');?></h3>
        <textarea placeholder="<?php  echo _('Describe the image you want');?>" 
                id="dalle_prompt" spellcheck="false" rows="1"
                oninput="this.rows=this.value.split('\n').length"></textarea>
        <button class="create flat" onClick="api.dalle()">
            <?php  echo _('Create');?>
        </button>
    </section>

    <section id="dalle_result">
        <div id="dalle_result_waiting" style="display: none">
            <img src="assets/icons/waiting.svg" style="width: 50px; margin: 0 10px 30px 0; float:left;">
            <?php
                echo _('Please wait while AI generates your image.');
                echo _('This can take up to one minute.');
            ?>
            <br>
            <span id="dalle_result_waiting_progress">0</span>
            <?php echo _('seconds'); ?>
        </div>

        <div id="dalle_result_response" style="display: none">
            <h3><?php  echo _('Result');?></h3>
            <div id="dalle_result_image"></div>
            <button class="create flat" onClick="api.useDalle()">
                <?php  echo _('Use this image');?>
            </button>
            <button class="create flat" onClick="api.dalle()">
                <?php  echo _('Try again');?>
            </button>
        </div>
        <style>
            #dalle_result img {
                max-width: 100%;
            }
        </style>
    </section>
    
</section>

