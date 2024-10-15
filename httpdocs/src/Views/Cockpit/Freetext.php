<section class="mainsection" id="cockpit_freetext">
    <h2><?php  echo _('Text');?></h2>
    <section>
        <button onClick="component.add('freetext')"><?php  echo _('Add text');?></button>
        <button class="outline" onClick="rte.deinit();"><?php  echo _('Close text editor');?></button>
    </section> 

    <section> 
        <div style="">
            <section class="selected_only">
                <h3><?php  echo _('Rotation');?></h3>
                <input type="range" min="-180" max="180" value="0" class="slider" id="text_rotation" oninput="freetext.setRotation(this.value)">      
            </section>
        </div>
    </section>

    <?php require ("./src/Views/Components/ToFrontAndBack.php"); ?>
</section>

<script>
    class Freetext{
        setSize(input){
            cockpit.target.style.fontSize = input.value + 'px';
        }

        setContent(content) {
           // const editor = tinymce.get('rte');
            //editor.setContent( content );
        }

        setRotation(value){
            document.getElementById(cockpit.target.id).style.rotate = `${value}deg`
        }

        font_family_formats = '<?php echo Sharepicgenerator\Controllers\Fonts::get_font_family_formats(); ?>';
    }
    const freetext = new Freetext();
</script>
<script src="/node_modules/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
