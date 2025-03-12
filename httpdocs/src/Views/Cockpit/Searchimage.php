<section class="mainsection" id="cockpit_search">
    <h2>
        <div class="set-target" data-target="background">
            <?php  echo _('Background image');?>
        </div>
        <div class="set-target" data-target="addpic">
            <?php  echo _('Foreground image or graphic');?>
        </div>
    </h2>

    <section>
        <h3><?php echo _('Upload an image from your local computer');?></h3>

        <div class="set-target image-uploader" style="margin-bottom:0" data-target="background">
            <button onClick="document.getElementById('upload').click()">
                <img src="assets/icons/upload.svg">
                <?php echo _('Drop an image or choose it to upload from your computer');?>
            </button>
        </div>

        <div class="set-target image-uploader" data-target="addpic">
            <button onClick="component.add('addpicture'); document.getElementById('addAddPicture').click();">
                <img src="assets/icons/upload.svg">
                <?php echo _('Drop an image or choose it to upload from your computer');?>
            </button>
        </div>

        <input type="file" name="upload" id="upload" onChange="api.upload(this)" style="display:none">
       
    </section>

    <section id="search_imagedb">
        <h3><?php  echo _('Image from database');?></h3>
        
        <div class="imagesearch"> 
                <?php if( $this->env->config->get( 'Main', 'tenant' ) === 'mint' ) { ?>
                    <div style="margin-bottom: 10px;"><?php echo _('Choose an image database');?></div>
                        <select name="image_db_source" class="mint" id="image_db_source">
                            <option value="mint">
                                MINT-Mediendatenbank
                            </option>
                            <option value="pixabay">Pixabay</option>
                        </select>
                <?php } else { ?>
                    <input type="hidden" name="image_db_source" id="image_db_source" value="unsplash">
                <?php } ?>
           
            
        <div class="button-group" style="display:flex">
            <input type="text" name="imagedb_q" id="imagedb_q" placeholder="<?php  echo _('search ...');?>">
            <button onClick="imagedb.search( document.getElementById('imagedb_q').value )"><img src="assets/icons/search.svg"></button>   
        </div>
    </section>


    <section id="add_new_from" class="set-target no-greens" data-target="addpic">
        <h3><?php  echo _('Add from');?></h3>
        <p>
            <?php echo _('Choose a form and colorize later.'); ?>
        </p>

        <div id="forms">
        <?php
            $forms = scandir( 'assets/forms' );
            foreach ( $forms as $form ) {
                if ( $form[0] === '.' ) continue;
        
                printf(
                    "<button class=\"no-button\" onClick=\"component.add('myform'); myform.setForm('sticker_%s')\">%s</button>",
                    preg_replace( '/\.svg/', '', $form ),
                    '<img src="/assets/forms/' . $form . '">'
                );
            }
        ?>
        </div>

        <div style="display:none">
            <svg id="sticker_star" style="position: absolute;width: 100%; height: 100%;" width="82" height="82" viewBox="0 0 82 82" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path id="sticker_bg" d="M41.4749 0L31.3436 31H0L25.9614 50.3333L15.5135 82L41.4749 62.6667L67.4363 82L56.9884 50.3333L82 31H51.2896L41.4749 0Z" fill="#ff0000"/>
            </svg>

            <svg id="sticker_speech_bubble" style="position: absolute;width: 100%; height: 100%;" width="83" height="66" viewBox="0 0 83 66" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path id="sticker_bg" d="M82.0541 0H0V50.3953H16.7264V66L47.0233 50.3953H82.0541V0Z" fill="#D9D9D9"/>
            </svg>

            <svg id="sticker_button" style="position: absolute;width: 100%; height: 100%;" width="110" height="48" viewBox="0 0 110 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path id="sticker_bg"  d="M0 8C0 3.58172 3.58172 0 8 0H102C106.418 0 110 3.58172 110 8V40C110 44.4183 106.418 48 102 48H8C3.58172 48 0 44.4183 0 40V8Z" fill="#D9D9D9"/>
            </svg>

            <svg id="sticker_circle" style="position: absolute;width: 100%; height: 100%;" width="82" height="82" viewBox="0 0 82 82" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect id="sticker_bg" width="82" height="82" rx="41" fill="#D9D9D9"/>
            </svg>

            <svg id="sticker_pill" style="position: absolute;width: 100%; height: 100%;" width="110" height="48" viewBox="0 0 110 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect id="sticker_bg" width="110" height="48" rx="24" fill="#D9D9D9"/>
            </svg>   
        
            <svg id="sticker_rectangle" style="position: absolute;width: 100%; height: 100%;" width="110" height="70" viewBox="0 0 110 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path id="sticker_bg" d="M0 0H110V70H0V0Z" fill="#D9D9D9"/>
            </svg>

            <svg id="sticker_square" style="position: absolute;width: 100%; height: 100%;"  width="82" height="82" viewBox="0 0 82 82" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect id="sticker_bg" width="82" height="82" fill="#D9D9D9"/>
            </svg>

            <svg id="sticker_star_2" style="position: absolute;width: 100%; height: 100%;" width="82" height="82" viewBox="0 0 82 82" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path id="sticker_bg" d="M40.8429 0L32.9885 11.3103L20.4215 5.341L19.4789 19.7931L5.341 20.7356L11.3103 32.9885L0 41.1571L11.3103 48.6973L5.341 61.5785L19.4789 62.5211L20.4215 76.9732L32.9885 70.3755L40.8429 82L49.0115 70.3755L61.5785 76.9732L62.5211 62.5211L76.659 61.5785L70.6897 48.6973L82 41.1571L70.6897 32.9885L76.659 20.7356L62.5211 19.7931L61.5785 5.341L49.0115 11.3103L40.8429 0Z" fill="#D9D9D9"/>
            </svg>

            <svg id="sticker_heart"  style="position: absolute;width: 100%; height: 100%;" width="89" height="78" viewBox="0 0 89 78" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path id="sticker_bg" d="M2.8463 35.1323C10.4292 52.9341 33.1577 70.9607 44.4811 77.9997C55.8045 70.9607 78.5334 52.9341 86.1162 35.1323C87.9319 31.7689 88.9625 27.9193 88.9625 23.8289C88.9625 10.6686 78.294 0 65.1336 0C56.3055 0 48.5986 6.80056 44.4813 13.934C40.3639 6.80056 32.6571 0 23.8289 0C10.6686 0 0 10.6686 0 23.8289C0 27.9193 1.0306 31.7689 2.8463 35.1323Z" fill="#D9D9D9"/>
            </svg>

            <svg  id="sticker_arrow"  style="position: absolute;width: 100%; height: 100%;" width="58" height="37" viewBox="0 0 58 37" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path id="sticker_bg" fill-rule="evenodd" clip-rule="evenodd" d="M2.2061e-07 18.5C2.34148e-07 19.6353 0.918679 20.5556 2.05193 20.5556L50.9943 20.5556L38.0817 33.4909C37.2804 34.2937 37.2804 35.5952 38.0817 36.3979C38.8831 37.2007 40.1823 37.2007 40.9836 36.3979L57.399 19.9535C58.2003 19.1508 58.2003 17.8492 57.399 17.0465L40.9836 0.602058C40.1823 -0.200687 38.8831 -0.200687 38.0817 0.602058C37.2804 1.4048 37.2804 2.70631 38.0817 3.50906L50.9943 16.4444L2.05193 16.4444C0.918679 16.4444 2.07073e-07 17.3647 2.2061e-07 18.5Z" fill="#D9D9D9"/>
            </svg>

            <svg id="sticker_arrow-big" style="position: absolute;width: 100%; height: 100%;" width="104" height="86" viewBox="0 0 104 86" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path id="sticker_bg" d="M0 64.1356V22.0466H59.0544V0L104 42.6963L59.0544 86V64.1356H0Z" fill="#D9D9D9"/>
            </svg>

            <svg id="sticker_arrow-long" width="99" height="38" viewBox="0 0 99 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path id="sticker_bg" fill-rule="evenodd" clip-rule="evenodd" d="M-0.000976342 18.5317C-0.000976328 19.6689 0.920903 20.5908 2.0581 20.5908L91.171 20.5908L78.2134 33.5483C77.4093 34.3524 77.4093 35.6561 78.2134 36.4603C79.0175 37.2644 80.3213 37.2644 81.1254 36.4603L97.598 19.9877C98.4021 19.1835 98.4021 17.8798 97.598 17.0757L81.1254 0.603088C80.3213 -0.201031 79.0175 -0.201031 78.2134 0.603088C77.4093 1.40721 77.4093 2.71094 78.2134 3.51506L91.171 16.4726L2.0581 16.4726C0.920903 16.4726 -0.000976355 17.3945 -0.000976342 18.5317Z" fill="#D9D9D9"/>
            </svg>
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

<script>
    const select_element = document.getElementById('image_db_source')
    
    select_element.addEventListener('change', function() {

        const options = select_element.options;
        Array.from(options).forEach(option => {
            select_element.classList.remove(option.value);
        });
        
        select_element.classList.add(this.value);
    });
</script>
