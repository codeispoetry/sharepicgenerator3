<section class="mainsection" id="cockpit_freetext">
    <h2><?php  echo _('Text');?></h2>
    <section>
        <button onClick="component.add('freetext')"><?php  echo _('Add text');?></button>
    </section> 

    <section class="selected_only">
        <h3><?php  echo _('Total size');?></h3>
        <input type="range" min="0" max="100" value="50" class="slider" id="text_size" oninput="freetext.setSize(this)">      
    </section>
    <?php require ("./src/Views/Components/ToFrontAndBack.php"); ?>

    <div id="rte-container">
        <textarea id="rte"></textarea>
    </div>
</section>

<script>
    class Freetext{
        setSize(input){
            cockpit.target.style.fontSize = input.value + 'px';
        }

        setContent(content) {
            const editor = tinymce.get('rte');
            editor.setContent( content );
        }
    }
    const freetext = new Freetext();
</script>


<script src="/node_modules/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#rte',
        menubar: 'tools',
        plugins: 'lists',
        skin: 'oxide-dark',
        font_family_formats: '<?php echo Sharepicgenerator\Controllers\Fonts::get_font_family_formats(); ?>',
        font_size_formats: '12pt 14pt 18pt 24pt 30pt 36pt 48pt 60pt 72pt 96pt',
        toolbar: 'undo redo | bold italic underline | fontfamily fontsize fontsizeinput lineheight | forecolor | removeformat',
        color_map: [
            '000000', 'Black',
            'FFFFFF', 'White',
            'FF0000', 'Red',
            '00FF00', 'Green',
            '0000FF', 'Blue',
        ],
        setup: function ( editor ) {
            editor.on('change keyup', function () {
                cockpit.target.innerHTML = editor.getContent();
            });
            editor.on('focus', function () {
               document.getElementById('rte-container').classList.add('rte-focus');
            });
            editor.on('blur', function () {
                document.getElementById('rte-container').classList.remove('rte-focus');
            });
        },
        'license_key': 'gpl',
        'branding': false,    
        'content_css': '/src/Views/rte/rte.css',
    });
 </script>
