<section class="mainsection" id="cockpit_search">
    <h2><?php  echo _('Create background ');?></h2>

    <section>
        <h3><?php  echo _('Own image');?></h3>
        <p><?php echo _('Upload an image from your local computer');?></p>

        <button onClick="document.getElementById('upload').click()">
            <img src="assets/icons/upload.svg">
            <?php  echo _('upload image');?>
        </button>

        <input type="file" name="upload" id="upload" onChange="api.upload(this)" style="display:none">
       
    </section>

    <section>
        <h3><?php  echo _('Search image');?></h3>
        <p><?php echo _('Search in an online image database');?></p>
        
        <div class="button-group">
            <input type="text" style="width:100%;" name="pixabay_q" id="pixabay_q" placeholder="<?php  echo _('search in image database');?>">
            <button onClick="pixabay.search()"><img src="assets/icons/search.svg"></button>   
        </div>
    </section>

    <section>
        <h3><?php  echo _('Create image with AI');?></h3>
        <p><?php echo _('Use artificial intelligence to create a unique image');?></p>
        <textarea placeholder="<?php  echo _('Describe the image you want');?>" 
                id="dalle_prompt" spellcheck="false" rows="2"
                oninput="this.rows=this.value.split('\n').length"></textarea>
        <button class="create" onClick="api.dalle()">
            <?php  echo _('Create');?>
        </button>
    </section>

    <section id="dalle_result" style="display: none";>
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
            <button class="create" onClick="api.useDalle()">
                <?php  echo _('Use this image');?>
            </button>
            <button class="create" onClick="api.dalle()">
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

