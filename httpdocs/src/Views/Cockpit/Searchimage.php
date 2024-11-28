<section class="mainsection" id="cockpit_search">
    <h2>
        <div class="set-target" data-target="background">
            <?php  echo _('Background image');?>
        </div>
        <div class="set-target" data-target="addpic">
            <?php  echo _('Foreground image');?>
        </div>
    </h2>



    <section>
        <p><?php echo _('Upload an image from your local computer');?></p>

        <div class="set-target" data-target="background">
            <button onClick="document.getElementById('upload').click()">
                <img src="assets/icons/upload.svg">
                <?php  echo _('upload own image');?>
            </button>
        </div>

        <div class="set-target" data-target="addpic">
            <button onClick="component.add('addpicture'); document.getElementById('addAddPicture').click();">
                <img src="assets/icons/upload.svg">
                <?php  echo _('upload image');?>
            </button>
        </div>

        <input type="file" name="upload" id="upload" onChange="api.upload(this)" style="display:none">
       
    </section>

    <section id="search_imagedb">
        <h3><?php  echo _('Image from database');?></h3>
        <p class="no-mint">
            <?php echo _('Search in  image database');?>
            <?php if ( $this->env->config->get( 'Main', 'imagedb') === 'unsplash' ) { ?>
                <a href="https://unsplash.com?utm_source=sharepicgenerator&utm_medium=referral" target="_blank">
                    <?php echo _('Unsplash'); ?>
                </a>
            <?php } else { ?>
                <a href="https://pixabay.com" target="_blank">
                    <?php echo _('Pixabay'); ?>
                </a>
            <?php } ?>
        </p>
        
        <div>
            <?php echo _('Choose an image database');?>
            <select name="image_db_source" id="image_db_source">
                <option value="mint">MINT-Mediendatenbank</option>
                <option value="pixabay">Pixabay</option>
            </select>
            
        <div class="button-group">
            <input type="text" style="width:100%;" name="imagedb_q" id="imagedb_q" placeholder="<?php  echo _('search in image database');?>">
            <button onClick="imagedb.search( document.getElementById('imagedb_q').value )"><img src="assets/icons/search.svg"></button>   
        </div>
    </section>

    <?php if ( $this->env->user->may_openai() ) { ?>
    <section id="search_dalle" style="display: none">
        <h3><?php  echo _('Create image with AI');?></h3>
        <p>
            <?php 
                echo _('Use artificial intelligence to create a unique image.');
                echo _('Please keep in mind, that generated images may reproduce stereotypes or be inappropriate.');
                echo _('Adjust your prompt accordingly.');
            ?>
        </p>
        <textarea placeholder="<?php  echo _('Describe the image you want');?>" 
                id="dalle_prompt" spellcheck="false" rows="2"
                oninput="this.rows=this.value.split('\n').length"></textarea>
        <button class="create" onClick="api.dalle()">
            <?php  echo _('Create');?>
        </button>
    </section>
    <?php } ?>

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
            <button class="create" onClick="api.useDalle()" style="margin-bottom: 1em">
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

