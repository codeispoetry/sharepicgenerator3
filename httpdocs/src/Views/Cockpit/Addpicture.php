<section class="mainsection" id="cockpit_addpicture">
    <h2>
        <a href="#" onClick="ui.showSearchImageTab('addpic');" style="text-decoration: none;">
            <img src="assets/icons/back-arrow.svg">
        </a>
    <?php  echo _('Edit foreground image');?>
</h2>

    <section class="selected_only">
        <h3><?php  echo _('Total size');?></h3>
        <input type="range" min="0" max="500" value="50" class="slider" id="addpicture_size" oninput="addpicture.setSize(this.value)">

        <div class="may_be_hidden">
            <h3 class=""><?php  echo _('Rotation');?></h3>
            <input type="range" min="0" max="360" value="0" class="slider" id="myform_rotation" oninput="myform.rotate(this)">
        </div>
    </section>

 
    <input type="file" id="addAddPicture" name="upload" onChange="api.uploadAddPic(this)" style="display: none";>

    <section class=" may_be_hidden">
        <h3><?php echo _("Image"); ?></h3>
        <div class="">
            <button id="addpic_pic_round" onclick="addpicture.picRound()" class="with-icon" title="<?php  echo _('round');?>">
                <div class="icon">
                    <img src="assets/icons/circle.svg">
                </div><?php  echo _('image as round');?>
            </button>
            <button id="addpic_pic_angular" onclick="addpicture.picAngular()" class="with-icon" title="<?php  echo _('angular');?>">
                <div class="icon">
                    <img src="assets/icons/square.svg">
                </div>
                <?php  echo _('image as angular');?>
            </button>      
            <button id="addpic_pic_original" onclick="addpicture.picOriginal()" class="with-icon" title="<?php  echo _('original');?>">
                <div class="icon">
                    <img src="assets/icons/rectangle.svg">
                </div>
                <?php  echo _('Image original');?>
            </button>      
        </div>
        </section>

    <section class="selected_only no-mint no-greens">
        <h3><?php echo _("Text"); ?></h3>
        <div class="">
            <button id="addpic_text_right" onclick="addpicture.textRight()" class="no-button" title="<?php  echo _('text floats right');?>"><img src="assets/icons/text-right.svg"></button>
            <button id="addpic_text_below" onclick="addpicture.textBelow()" class="no-button"  title="<?php  echo _('text below');?>"><img src="assets/icons/text-below.svg"></button>
            
            <?php
                $color = new stdClass();
                $color->value = "#ffffff";
                $color->id = "addpic_color";
                $color->oninput = "addpicture.setFontColor(this.value)";
                $color->onclick = "addpicture.setFontColor";
                require ("./src/Views/Components/Color.php"); 
            ?>
        </div>
    </section>
    <?php 
        $delete_button_text = _('Foreground image');
        require ("./src/Views/Components/ToFrontAndBack.php"); 
    ?>

</section>

<script>
   class AddPicture{
        setFontColor(color) {      
            document.getElementById('addpic_color').value = color
            cockpit.target.querySelector('.ap_text').style.color = color
            undo.commit()
        }  

        picRound() {
            const apImage = cockpit.target.querySelector('.ap_image')
            apImage.style.borderRadius = '50%';
            apImage.dataset.aspectRatio = 1;
            addpicture.setSize(document.getElementById('addpicture_size').value)
            undo.commit()
        }

        picAngular() {
            const apImage = cockpit.target.querySelector('.ap_image')
            apImage.style.borderRadius = '0';
            apImage.dataset.aspectRatio = 1;
            addpicture.setSize(document.getElementById('addpicture_size').value)
            undo.commit()
        }

        picOriginal() {
            const apImage = cockpit.target.querySelector('.ap_image')
            apImage.style.borderRadius = '0';
        
            const img = new Image();
            img.onload = function() {
                const w = this.width;
                const h = this.height;
                const ratio = w / h;

                const factor = 0.1
                const new_w = w * factor;
                const new_h = h * factor;
                
                const apImage = cockpit.target.querySelector('.ap_image')
                apImage.style.height= new_h + 'px';
                apImage.style.width = new_w + 'px';
                apImage.dataset.aspectRatio = new_w / new_h;
            }
            img.src = apImage.style.backgroundImage.replace('url("', '').replace('")', '');

            undo.commit()
        }

        textBelow() {
            cockpit.target.style.display = 'block';
            undo.commit()
        }

        textRight() {
            cockpit.target.style.display = 'flex';
            undo.commit()
        }

        setSize(value) {
            const apImage = cockpit.target.querySelector('.ap_image')
            const aspectRatio = apImage.dataset.aspectRatio;

            apImage.style.width = value + 'px';
            apImage.style.height = value / aspectRatio + 'px';
    
            const fontSize = Math.min( 22, Math.max( 18, value * 0.1 ) );
            cockpit.target.querySelector('.ap_text').style.fontSize = fontSize + 'px';
        }
    }
    
    const addpicture = new AddPicture();

</script>
