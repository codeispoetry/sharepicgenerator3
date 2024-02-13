<section class="mainsection" id="cockpit_dalle">
    <h2><?php  echo _('Create image with AI');?></h2>
    <section>
        <h3><?php  echo _('Prompt');?></h3>
        <textarea placeholder="<?php  echo _('Describe the image you want');?>" 
                id="dalle_prompt" spellcheck="false" style="height: 25em;"></textarea>
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

</script>


